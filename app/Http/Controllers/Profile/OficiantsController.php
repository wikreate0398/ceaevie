<?php

namespace App\Http\Controllers\Profile;
 
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;  
use App\Notifications\OficiantCreated;
use App\Notifications\InviteFromLocation; 
use App\Utils\QrCodeGenerator;
use App\Utils\Ballance; 

use App\Models\User; 
use App\Models\AdminUser;
use App\Models\ProfileMenu; 
use App\Models\LocationUser;
use App\Models\QrCode;

class OficiantsController extends Controller
{ 
    public function index()
    { 
        $data = [
            'menu'  => ProfileMenu::where('route', 'my_oficiants')->first(),
            'users' => LocationUser::where('id_location', \Auth::id())->with('user')->has('user')->orderBy('id', 'desc')->get()
        ]; 

        return view('profile.oficiants', $data);
    }   

    public function addNewOficiant(Request $request)
    {
    	if (!$request->email or !$request->lastname or !$request->name or !$request->card_signature) 
    	{
    		return \JsonResponse::error(['messages' => \Constant::get('REQ_FIELDS')]);
    	}

    	$oficiant = User::whereEmail($request->email)->first();
    	if (!empty($oficiant)) 
    	{
    		return \JsonResponse::error(['messages' => 'Пользователь существует.']);
    	}

        \DB::beginTransaction();
        try {
            $user = User::create([
                'name'         => $request->name,
                'type'         => 'user',
                'lastname'     => $request->lastname, 
                'phone'        => $request->phone,
                'email'        => $request->email, 
                'lang'         => lang(),
                'rand'         => generate_id(7)
            ]);

            $hash = md5(microtime());
            $locationUser = LocationUser::create([
                'id_user'     => $user->id,
                'id_location' => \Auth::user()->id,
                'hash'        => $hash
            ]);

            $this->createQrCode($locationUser, $request->card_signature);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return \JsonResponse::error(['messages' => 'На сервере возникла ошибка']);
        } 

        $link = route('finish_registration', ['lang' => lang(), 'hash' => $hash]);
        $user->notify(new OficiantCreated($locationUser->location, $link));

    	return \JsonResponse::success(['messages' => 'Приглашение успешно отправлено', 'reload' => true]);
    }

    public function requestMoney(Request $request, Ballance $userBallance)
    {
        try {
            $this->validateRequestMoney($request);
        } catch (\Exception $e) {
            return \JsonResponse::error(['messages' => $e->getMessage()]);
        }

        $user = User::whereId($request->id_user)->first();  
        $userBallance->setUser($user) 
                     ->setPrice($request->amount)
                     ->setSender(\Auth::id())
                     ->replenish();

        $location = User::whereId(\Auth::user()->id)->first(); 
        $userBallance->setUser($location) 
                     ->setPrice($request->amount)
                     ->off();

        return \JsonResponse::success(['messages' => 'Cредства были успешно отправлены', 'reload' => true]);
    }

    private function validateRequestMoney($request)
    {
        if (!$request->amount or !$request->id_user) 
        {
            throw new \Exception(\Constant::get('REQ_FIELDS')); 
        }

        if (!LocationUser::where('id_location', \Auth::id())->where('id_user', $request->id_user)->count()) 
        {
            throw new \Exception('Вы не можете отпарвить деньги этому пользователю');  
        }

        if ($request->amount > \Auth::user()->ballance) 
        {
            throw new \Exception('На вашем счету нет столько средств');  
        }
    }

    private function createQrCode($locationUser, $card_signature)
    {
        $qrGenerator = new QrCodeGenerator;
        $qrImage = $qrGenerator->generateCode()
                               ->genereateImage();

        QrCode::create([
            'id_user'          => $locationUser->id_user,
            'id_location'      => $locationUser->id_location,
            'card_signature'   => $card_signature,
            'institution_name' => $locationUser->location->institution_name,
            'id_background'    => 1,
            'code'             => $qrGenerator->getCode(),
            'qr_code'          => $qrImage
        ]);
    }

    public function inviteOficiant(Request $request)
    {
    	if (!$request->email or !$request->card_signature) 
    	{
    		return \JsonResponse::error(['messages' => \Constant::get('REQ_FIELDS')]);
    	}

    	$user = User::whereEmail($request->email)->registered()->where('type', 'user')->where('active', '1')->first();
    	if (empty($user)) 
    	{
    		return \JsonResponse::error(['messages' => 'Пользователь не найден или не активен']);
    	}

    	$locationUser = LocationUser::where('id_user', $user->id)->where('id_location', \Auth::user()->id)->first();

    	if (!empty($locationUser)) 
    	{
    		if ($locationUser->status == 'confirmed') 
    		{
    			$msg = 'Этот пользователь уже привязан к вашему заведению';
    		}
    		else
    		{
    			$msg = 'Мы ранее отправляли приглашение этому пользователю';
    		}
    		return \JsonResponse::error(['messages' => $msg]);
    	}

    	$hash         = md5(microtime());
    	$locationUser = LocationUser::create([
    		'id_user'     => $user->id,
    		'id_location' => \Auth::user()->id,
            'card_signature' => $request->card_signature,
    		'hash'           => $hash
    	]); 

        $user->notify(new InviteFromLocation($locationUser->location));

    	return \JsonResponse::success(['messages' => 'Приглашение успешно отправлено', 'reload' => true]);
    } 
}
