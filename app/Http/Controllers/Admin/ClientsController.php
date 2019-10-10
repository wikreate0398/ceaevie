<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Utils\UploadImage;  
use App\Notifications\SendLetter;

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
            'data'   => $this->model->orderByRaw('id desc')->filter()->get(),
            'table'  => $this->model->getTable(),
            'method' => $this->method
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

        $this->input['active'] = 1;
        $this->input['confirm'] = 1;
        $this->input['account_number'] = generateAccountNumber();
 
        $id = $this->model->create($this->input)->id; 

        return \App\Utils\JsonResponse::success(['redirect' => route($this->redirectRoute)], trans('admin.save')); 
    }

    public function showeditForm($id)
    { 
        return view('admin.'.$this->folder.'.edit', [
            'method'        => $this->method,
            'table'         => $this->model->getTable(),
            'data'          => $this->model->findOrFail($id)
        ]);
    }

    public function autologin($id)
    {
        $client = User::findOrFail($id);
        \Auth::guard('web')->login($client);
        return redirect('/');
    }

    public function update($id, Request $request)
    {
        $data        = $this->model->findOrFail($id);
        $this->input = $this->prepareData($data, $request->all());

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
        return \App\Utils\JsonResponse::success(['reload' => true], 'Message sent successfully'); 
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

        return $input;
    }

}
