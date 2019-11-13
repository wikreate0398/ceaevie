<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProfileMenu; 
use App\Models\UserType;
use App\Models\ProfileMenuGuard;

class ProfileMenuController extends Controller
{
    private $method = 'profile-menu';

    private $folder = 'profile_menu';

    private $redirectRoute = 'admin_profile_menu';

    private $returnDataFields = ['name'];
  
    private $requiredFields = ['name_ru'];
  
    private $input;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() 
    {
        $this->model  = new ProfileMenu;
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
            'data'       => $this->model->with('access')->orderByRaw('page_up asc, id desc')->get(),
            'user_types' => UserType::all(),
            'table'      => $this->model->getTable(),
            'method'     => $this->method
        ]; 

        return view('admin.'.$this->folder.'.list', $data);
    }  

    public function create(Request $request)
    {
        $this->input = $this->prepareData(false, $request->all());

        if(!is_array($this->input))
        {
            return \JsonResponse::error(['messages' => $this->input]);
        }

        $this->model->create($this->input);
        return \App\Utils\JsonResponse::success(['redirect' => route($this->redirectRoute)], trans('admin.save')); 
    }

    public function showeditForm($id)
    { 
        return view('admin.'.$this->folder.'.edit', [
            'method'        => $this->method,
            'table'         => $this->model->getTable(),
            'data'          => $this->model->findOrFail($id), 
        ]);
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
        $input          = \Language::returnData($this->returnDataFields);
        if($this->validation($input) != true)
        {
            return trans('admin.req_fields');
        }
 
        return $input;
    }

    public function saveAccess(Request $request)
    {
        ProfileMenuGuard::truncate();
        if (!empty($request->access)) 
        {
            foreach ($request->access as $id_menu => $value) 
            {
                foreach ($value as $type => $val) 
                {
                    ProfileMenuGuard::insert([
                        'id_menu' => $id_menu,
                        'type'    => $type
                    ]);
                }
            }
        }
        return \App\Utils\JsonResponse::success(['message' => trans('admin.save')]); 
    }
}
