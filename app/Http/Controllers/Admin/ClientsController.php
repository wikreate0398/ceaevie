<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Utils\UploadImage;  
use App\Utils\Encryption; 
use App\Notifications\SendLetter;
use App\Notifications\ChangeVerificationStatus;

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
            'data'      => $this->model->orderByRaw('id desc')->filter()->get(),
            'table'     => $this->model->getTable(),
            'method'    => $this->method,
            'today_reg' => $this->model->registered('today')->count(),
            'week_reg'  => $this->model->registered('week')->count(),
            'total_reg' => $this->model->registered()->count(),
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

        $this->input['active'] = 1;
        $this->input['confirm'] = 1; 
        $this->input['rand'] = generate_id(7);  
 
        $this->model->create($this->input)->id; 

        return \App\Utils\JsonResponse::success(['redirect' => route($this->redirectRoute)], trans('admin.save')); 
    }

    public function showeditForm($id)
    { 
        return view('admin.'.$this->folder.'.edit', [
            'method'        => $this->method,
            'table'         => $this->model->getTable(),
            'data'          => $this->model->withTrashedCard()->findOrFail($id),
            'encriptionService' => new Encryption
        ]);
    }

    public function autologin($id)
    {
        $client = User::findOrFail($id);
        \Auth::guard('web')->login($client);
        return redirect()->route('workspace', ['lang' => 'ru']);
    }

    public function update($id, Request $request)
    {
        $data        = $this->model->findOrFail($id);
        $this->input = $this->prepareData($data, $request->all());

        $this->input['rbk']            = @$request->rbk ? 1 : 0;
        $this->input['payment_center'] = @$request->payment_center ? 1 : 0;

        if(!is_array($this->input))
        {
            return \JsonResponse::error(['messages' => $this->input]);
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
        }else{
            unset($input['password']);
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
