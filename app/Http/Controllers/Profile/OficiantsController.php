<?php

namespace App\Http\Controllers\Profile;
 
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;  
use App\Models\User; 
use App\Models\AdminUser;
use App\Models\ProfileMenu; 
use App\Models\LocationUser;

class OficiantsController extends Controller
{ 
    public function index()
    { 
        $data = [
            'menu'  => ProfileMenu::where('route', 'my_oficiants')->first(),
            'users' => LocationUser::where('id_location', \Auth::id())->with('user')->get()
        ]; 

        return view('profile.oficiants', $data);
    }   

    public function addNewOficiant(Request $request)
    {
    	if (!$request->email or !$request->lastname or !$request->name) 
    	{
    		return \JsonResponse::error(['messages' => \Constant::get('REQ_FIELDS')]);
    	}

    	$oficiant = User::whereEmail($request->email)->where('type', 'user')->first();
    	if (!empty($oficiant)) 
    	{
    		return \JsonResponse::error(['messages' => 'Пользователь существует. Попробуйте отправить приглашение']);
    	}

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
    	LocationUser::create([
    		'id_user'     => $user->id,
    		'id_location' => \Auth::user()->id,
    		'hash'        => $hash
    	]);

    	return \JsonResponse::success(['messages' => 'Приглашение успешно отправлено', 'reload' => true]);
    }

    public function inviteOficiant(Request $request)
    {
    	if (!$request->email) 
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

    	$hash = md5(microtime());
    	LocationUser::create([
    		'id_user'     => $user->id,
    		'id_location' => \Auth::user()->id,
    		'hash'        => $hash
    	]);

    	return \JsonResponse::success(['messages' => 'Приглашение успешно отправлено']);
    } 
}
