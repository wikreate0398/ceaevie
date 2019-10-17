<?php

namespace App\Http\Controllers\Profile;
 
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use App\Models\User;
  
class AccountController extends Controller
{ 
    public function index()
    { 
        return view('profile.account');
    }  

    public function edit(Request $request){
    	if(!$request->name or !$request->email or !$request->lastname or !$request->phone)
        {
            return \JsonResponse::error(['messages' => \Constant::get('REQ_FIELDS')]);
        }

        if(User::withTrashed()->whereEmail($request->email)->where('id', '<>', \Auth::user()->id)->count())
        {
            return \JsonResponse::error(['messages' => \Constant::get('USER_EXIST')]);
        }

        User::whereId(\Auth::user()->id)->update([
        	'name'     => $request->name,
        	'lastname' => $request->lastname,
        	'phone'    => $request->phone,
        	'email'    => $request->email,
        	'payment_signature' => $request->payment_signature
        ]);

        return \JsonResponse::success(['messages' => \Constant::get('DATA_SAVED'), 'reload' => true]);
    }

    public function changePassword(Request $request)
    {
        if(!$request->old_password or !$request->new_password or !$request->repeat_password)
        {
            return \JsonResponse::error(['messages' => \Constant::get('REQ_FIELDS')]);
        }

        if (!\Hash::check($request->old_password, \Auth::user()->password)) 
        {
        	return \JsonResponse::error(['messages' => 'Старый пароль не верный']);
        }

        if($request->new_password != $request->repeat_password)
        {
            return \JsonResponse::error(['messages' => \Constant::get('PASS_NOT_MATCH')]);
        }

        if(strlen($request->new_password) < 8)
        {
            return \JsonResponse::error(['messages' => \Constant::get('PASS_RESTRICTION')]);
        }

        User::whereId(\Auth::user()->id)->update([
            'password' => bcrypt($request->new_password),
        ]);

        return \JsonResponse::success(['messages' => \Constant::get('PASS_SAVED'), 'reload' => true]);
    }

    public function saveAvatar(Request $request)
    {
        if (empty($request->avatar)) 
        {
            return \App\Utils\JsonResponse::error(['messages' => 'Изображение не выбрано']); 
        }

        $filename = 'user-' . \Auth::user()->id . '-' . md5(microtime()) . '.png';
        $avatarImagePath = public_path() . '/uploads/clients/' . $filename;  
        uploadBase64($request->avatar, $avatarImagePath); 
        
        User::where('id', \Auth::user()->id)->
          update([ 
            'image'  => $filename 
        ]); 

        return \App\Utils\JsonResponse::success();
    }
}