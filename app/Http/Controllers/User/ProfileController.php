<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ProfileController extends Controller
{
    public function personalData()
    {
        return view('user.profile.personal_data');
    }

    public function savePersonalData(Request $request)
    {
        if(!$request->name or !$request->email)
        {
            return \JsonResponse::error(['messages' => \Constant::get('REQ_FIELDS')]);
        }

        if(User::withTrashed()->whereEmail($request->email)->where('id', '<>', \Auth::user()->id)->count())
        {
            return \JsonResponse::error(['messages' => \Constant::get('USER_EXIST')]);
        }

        User::whereId(\Auth::user()->id)->update([
           'email' => $request->email,
           'name'  => $request->name
        ]);

        return \JsonResponse::success(['messages' => \Constant::get('DATA_SAVED'), 'reload' => true]);
    }

    public function changePass()
    {
        return view('user.profile.change_pass');
    }

    public function savePassword(Request $request)
    {
        if(!$request->password or !$request->repeat_password)
        {
            return \JsonResponse::error(['messages' => \Constant::get('REQ_FIELDS')]);
        }

        if($request->password != $request->repeat_password)
        {
            return \JsonResponse::error(['messages' => \Constant::get('PASS_NOT_MATCH')]);
        }

        if(strlen($request->password) < 8)
        {
            return \JsonResponse::error(['messages' => \Constant::get('PASS_RESTRICTION')]);
        }

        User::whereId(\Auth::user()->id)->update([
            'password'     => bcrypt($request->password),
        ]);

        return \JsonResponse::success(['messages' => \Constant::get('PASS_SAVED'), 'reload' => true]);
    }
}