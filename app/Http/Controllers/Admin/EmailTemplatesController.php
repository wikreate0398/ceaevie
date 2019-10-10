<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmailTemplates;

class EmailTemplatesController extends Controller
{

    private $method = 'email-templates';

    private $folder = 'email_templates';

    private $redirectRoute = 'admin_email_templates';

    private $returnDataFields = ['theme', 'message'];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() 
    {
        $this->model = new EmailTemplates;
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
            'data'   => $this->model->orderByRaw('page_up asc, id desc')->get(),
            'table'  => $this->model->getTable(),
            'method' => $this->method
        ]; 

        return view('admin.'.$this->folder.'.list', $data);
    }  

    public function showeditForm($id)
    { 
        return view('admin.'.$this->folder.'.edit', ['method' => $this->method, 'table' => $this->model->getTable(), 'data' => $this->model->findOrFail($id)]);
    }

    public function update($id, Request $request)
    { 
        $data  = $this->model->findOrFail($id); 
        $input = \Language::returnData($this->returnDataFields);      
        $data->fill($input)->save(); 
        return \App\Utils\JsonResponse::success(['redirect' => route($this->redirectRoute)], trans('admin.save')); 
    } 
}
