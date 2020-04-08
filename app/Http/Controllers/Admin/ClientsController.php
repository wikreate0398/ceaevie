<?php

namespace App\Http\Controllers\Admin;

use App\Notifications\SendBill;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Utils\Payments\PaymentCenterService\VisaPayment;
use App\Utils\UploadImage;  
use App\Utils\Encryption;
use App\Utils\Order;
use App\Notifications\SendLetter;
use App\Notifications\ChangeVerificationStatus;
use App\Models\User;
use App\Models\UserType;
use App\Models\EnrollmentPercents;
use App\Models\QrCode;

class ClientsController extends Controller
{
    private $method = 'clients';

    private $folder = 'clients';

    private $uploadFolder = 'clients';

    private $redirectRoute = 'admin_clients';

    private $requiredFields = ['name', 'email'];

    private $input;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() 
    {
        $this->model  = new User;
        $this->method = config('admin.path') . '/' . $this->method;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {  
        $data = [
            'data'      => $this->model->orderByRaw('id desc')->with('typeData')->filter()->get()->sortBy('typeData.page_up'),
            'table'     => $this->model->getTable(),
            'method'    => $this->method,
            'today_reg' => $this->model->registered('today')->count(),
            'week_reg'  => $this->model->registered('week')->count(),
            'total_reg' => $this->model->registered()->count(),
            'userTypes' => UserType::all(),
            'total_pending_verification' => $this->model->registered()->where('verification_status', 'pending')->count(),
        ]; 

        return view('admin.'.$this->folder.'.list', $data);
    } 

    public function showAddForm()
    {  
        return view('admin.'.$this->folder.'.add', [
            'method'        => $this->method
        ]);
    }

    public function create(Request $request)
    {
        $this->input = $this->prepareData(false, $request->all());

        if(!is_array($this->input))
        {
            return \JsonResponse::error(['messages' => $this->input]);
        }

        if (User::whereEmail($request->email)->count()) 
        {
            return \JsonResponse::error(['messages' => 'Пользователь с таким имейлом уже существует']);
        }

        if (!empty($request->agent_code) && !User::where('code', '=', $request->agent_code)->count()) 
        { 
            return \JsonResponse::error(['messages' => 'Код партнера не действителен']);
        } 

        $this->input['active']           = 1;
        $this->input['confirm']          = 1; 
        $this->input['rand']             = generate_id(7);
        $this->input['type']             = $request->type;
        $this->input['work_type']        = ($request->type == 'admin') ? 'common_sum' : '';
        $this->input['special_payout']   = $request->special_payout ? 1 : 0;
        $this->input['institution_name'] = $request->institution_name ?: '';
        $this->input['code']             = ($request->type == 'agent') ? generate_id(4) : '';
        $this->input['agent_code']       = $request->agent_code ?: '';

        $this->model->create($this->input)->id; 

        return \App\Utils\JsonResponse::success(['redirect' => route($this->redirectRoute)], trans('admin.save')); 
    }

    public function showeditForm($id)
    {
        $user    = $this->model->withTrashedCard()->findOrFail($id);
        $qrCodes = QrCode::where('id_user', $user->id)->with('location')->get();

        return view('admin.'.$this->folder.'.edit', [
            'method'            => $this->method,
            'table'             => $this->model->getTable(),
            'data'              => $user,
            'encriptionService' => new Encryption,
            'qr_codes'          => $qrCodes
        ]);
    }

    public function waiterBill($id, Request $request)
    {
        try {
            $waiter  = User::whereId($id)->active()->first();

            if (!$waiter or @$waiter->type != 'user') {
                throw new \Exception('Официант не активен');
            }

            if (!$request->type or !$request->price or !$request->qr_code) {
                throw new \Exception('Заполните все поля');
            }

            if ($request->type === '2' && !$request->email) {
                throw new \Exception('Укажите email плательщика');
            }

            $orderCreator = new Order;
            $orderCreator->requestData([
                'code'  => $request->qr_code,
                'price' => $request->price
            ])->make();

            $order = $orderCreator->getOrder();

            $paymentClass = new VisaPayment;
            $paymentClass->setOrderId($order->rand)
                         ->setAmount(toFloat($order->total_amount))
                         ->setDescription( $request->description ?: 'Чаевые официанту ' . $order->user->name);

            $link = $paymentClass->getToken();

            if ($request->type === '1') {
                return \JsonResponse::success(['message' => 'Ссылка успешно сгенерирована', 'link' => $link]);
            } else {
                $waiter->notify(new SendBill($order->rand, $request->price, $link));
                return \JsonResponse::success(['message' => 'Выписка успешно отправлена']);
            }

        } catch (\Exception $e) {
            return \JsonResponse::error(['message' => $e->getMessage()]);
        }
    }

    public function autologin($id)
    {
        $client = User::findOrFail($id);
        \Auth::guard('web')->login($client);

        $route = ($client->type == 'agent') ? 'my_referrals' : 'workspace';

        return redirect()->route($route, ['lang' => 'ru']);
    }

    public function update($id, Request $request)
    {
        $data        = $this->model->findOrFail($id);
        $this->input = $this->prepareData($data, $request->all());
 
        if(!is_array($this->input))
        {
            return \JsonResponse::error(['messages' => $this->input]);
        }

        if (!empty($request->agent_code) && !User::where('code', '=', $request->agent_code)->count()) 
        { 
            return \JsonResponse::error(['messages' => 'Код партнера не действителен']);
        } 
        
        $this->input['rbk']              = !empty($request->rbk) ? 1 : 0;
        $this->input['payment_center']   = !empty($request->payment_center) ? 1 : 0;
        $this->input['institution_name'] = $request->institution_name ?: '';
        $this->input['agent_code']       = $request->agent_code ?: '';
        $this->input['special_payout']   = $request->special_payout ? 1 : 0;

        if ($request->fee){
            $percents           = EnrollmentPercents::all()->keyBy('type');
            $permissiblePercent = $percents['agent']['percent'] + $percents['income']['percent'];

            if ($permissiblePercent < $request->fee){
                return \JsonResponse::error(['messages' => 'Процент может быть не больше ' . $permissiblePercent]);
            }
        }
  
        $data->fill($this->input)->save();
        return \App\Utils\JsonResponse::success(['redirect' => route($this->redirectRoute)], trans('admin.save')); 
    }

    public function sendLetter($id, Request $request)
    { 
        $user = $this->model->findOrFail($id);
        $user->notify(new SendLetter($request->theme, $request->message));
        return \App\Utils\JsonResponse::success(['reload' => true], 'Сообщение успешно отправлено'); 
    }

    private function validation($input)
    {
        foreach($this->requiredFields as $key => $field)
        {
            if(empty($input[$field])) return false;
        }
        return true;
    }

    private function prepareData($data = false, $input)
    {
        if($this->validation($input) != true)
        {
            return trans('admin.req_fields');
        } 

        if(!empty($input['password']) or !empty($input['repeat_password']))
        {
            if($input['password'] != $input['repeat_password'])
            {
                return 'Пароль не совпадает';
            }

            $input['password'] = bcrypt($input['password']);
        }

        $uploadImage = new UploadImage;
        $image       = $uploadImage->upload('image', $this->uploadFolder);

        if (!empty($image)) {
            $input['image'] = $image;
        } 

        return $input;
    }

    public function changeVerificationStatus($id, $status)
    {
        $user = User::whereId($id)->whereNotIn('verification_status', ['decline', 'confirm'])->firstOrFail();    
        $user->verification_status = $status;
        $user->save();
        $user->notify(new ChangeVerificationStatus('verification_' . $status)); 
        return redirect()->back()->with('admin_flash_message', 'Статус успешно изменен');
    }
}
