<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EnrollmentPercents; 

class EnrollmentPercentsController extends Controller
{
    private $method = 'enrollment-percents';

    private $folder = 'enrollment_percents';

    private $redirectRoute = 'admin_enrollment_percents';

    private $returnDataFields = ['name']; 

    private $input;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() 
    {
        $this->model  = new EnrollmentPercents;
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
            'data'   => $this->model->orderByRaw('percent asc, id desc')->get(),
            'table'  => $this->model->getTable(),
            'method' => $this->method
        ];  

        return view('admin.'.$this->folder.'.list', $data);
    }  

    public function create(Request $request)
    { 
        $this->model->create($request->all());
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
        $data = $this->model->findOrFail($id); 
        $data->fill($request->all())->save();
        return \App\Utils\JsonResponse::success(['redirect' => route($this->redirectRoute)], trans('admin.save')); 
    }  
}
