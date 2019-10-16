<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BackgroundColor; 

class BackgroundsController extends Controller
{
    private $method = 'oficiant-profile/backgrounds';

    private $folder = 'backgrounds';

    private $redirectRoute = 'admin_backgrounds'; 
    
    private $input;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() 
    {
        $this->model  = new BackgroundColor;
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

    public function create(Request $request)
    {  
        $this->model->create([ 
            'color' => $request->color
        ]);

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
        $this->model->whereId($id)->update([ 
            'color' => $request->color
        ]);
        return \App\Utils\JsonResponse::success(['redirect' => route($this->redirectRoute)], trans('admin.save')); 
    }
}
