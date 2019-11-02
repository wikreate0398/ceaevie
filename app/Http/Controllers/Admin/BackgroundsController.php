<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BackgroundColor; 
use App\Utils\UploadImage;

class BackgroundsController extends Controller
{
    private $method = 'oficiant-profile/backgrounds';

    private $folder = 'backgrounds';

    private $uploadFolder = 'backgrounds';

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
        $postData = $request->all();   
        $logo     = $this->saveLogo();

        if (!empty($logo)) 
        {
            $postData['logo'] = $logo;
        }

        $this->model->create($postData);

        return \App\Utils\JsonResponse::success(['redirect' => route($this->redirectRoute)], trans('admin.save')); 
    }

    public function showeditForm($id)
    { 
        return view('admin.'.$this->folder.'.edit', [
            'method'        => $this->method,
            'table'         => $this->model->getTable(),
            'data'          => $this->model->findOrFail($id), 
            'folder'        => $this->uploadFolder
        ]);
    }

    public function update($id, Request $request)
    {   
        $postData = $request->all(); 
        $logo     = $this->saveLogo();

        if (!empty($logo)) 
        {
            $postData['logo'] = $logo;
        }
 
        $this->model->whereId($id)->first()->fill($postData)->save();

        return \App\Utils\JsonResponse::success(['redirect' => route($this->redirectRoute)], trans('admin.save')); 
    }

    private function saveLogo()
    {
        $uploadImage = new UploadImage;
        $image       = $uploadImage->upload('logo', $this->uploadFolder); 
        return $image ?: false;  
    }
}
