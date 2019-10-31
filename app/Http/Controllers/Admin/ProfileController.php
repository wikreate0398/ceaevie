<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;  
use Illuminate\Support\Facades\Validator;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{ 

    private $method = 'profile';
  
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->method = config('admin.path') . '/' . $this->method;
    }
  
    public function showForm()
    {
        $users = AdminUser::where('id', '!=', Auth::id())->orderBy('id', 'desc');
        if(Auth::user()->parent_id != 0){
            $users->where('parent_id', Auth::id());
        }

        return view('admin.profile.view', [
            'users' => $users->get(),
            'table' => (new AdminUser)->getTable(),
            'method' => $this->method
        ]);
    }

    public function addNewUser(Request $request)
    {
        $validator = Validator::make($request->all(), $rules = [ 
            'password'              => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
            'email'                 => 'required|min:4|unique:admin_users' 
        ], ['unique' => trans('admin.already_exits')]);

        $validator->setAttributeNames([
            'password'         => 'Пароль',
            'repeat_password'  => 'Повторить пароль',
            'email'            => 'Login/E-mail'
        ]); 
 
        if ($validator->fails()) 
        {   
            return \App\Utils\JsonResponse::error(['messages' => $validator->errors()->toArray()]); 
        }

        $checkLogin = AdminUser::whereEmail($request->input('login'))->first();
        if (!empty($checkLogin)) 
        {
            return \App\Utils\JsonResponse::error(['messages' => trans('admin.already_exits')]);
        }

        $data = $request->all();

        AdminUser::create([
            'name'      => $data['name'],
            'password'  => bcrypt($data['password']),
            'email'     => $data['email'], 
            'parent_id' => Auth::user()->id,
            'type'      => $data['type']
        ]);

        return \App\Utils\JsonResponse::success(['redirect' => route('profile')], trans('admin.added_user')); 
    }

    public function editAdminUser($id)
    {  
        return view('admin.profile.edit', [
            'data'   => AdminUser::findOrFail($id),
            'table'  => (new AdminUser)->getTable(),
            'method' => $this->method
        ]);
    }

    public function updateAdminUser($id, Request $request)
    {
         
        if (!$request->name or !$request->email) 
        {
            return \App\Utils\JsonResponse::error(['messages' => 'Заполните все обязательные поля']);
        }

        if($request->password && !$request->repeat_password)
        {
            return \App\Utils\JsonResponse::error(['messages' => 'Заполните поле повторите Пароль']);  
        }

        if ($request->password && $request->repeat_password && ($request->password != $request->repeat_password)) 
        {
            return \App\Utils\JsonResponse::error(['messages' => 'Пароль не совпадает']); 
        }

        if (AdminUser::where('email', $request->email)->where('id', '<>', $id)->count() > 0) 
        {
            return \App\Utils\JsonResponse::error(['messages' => 'Пользователь ужу существует']); 
        } 
 
        if ($request->password && $request->repeat_password) 
        {
            $update['password'] = bcrypt($request->password);
        }
         
        $update['name']  = $request->name;
        $update['email'] = $request->email;
        $update['type'] = $request->type;
        $data = AdminUser::findOrFail($id);
        $data->fill($update)->save(); 
        return \App\Utils\JsonResponse::success(['redirect' => route('profile')], trans('admin.update_pass')); 
    }

    public function edit(Request $request)
    { 
        $validator = Validator::make($request->all(), $rules = [ 
            'password'              => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',
        ], ['unique' => trans('admin.already_exits')]);

        $validator->setAttributeNames([
            'password'         => 'Пароль',
            'repeat_password'  => 'Повторить пароль'
        ]); 

        if ($validator->fails()) 
        {  
            return \App\Utils\JsonResponse::error(['messages' => $validator->errors()->toArray()]); 
        }
 
        $data   = AdminUser::findOrFail(Auth::id());     
        $update = $request->all();
        $update['password'] = bcrypt($request->input('password'));
        $data->fill($update)->save(); 
        return \App\Utils\JsonResponse::success(['redirect' => route('profile')], trans('admin.update_pass')); 
    }
}