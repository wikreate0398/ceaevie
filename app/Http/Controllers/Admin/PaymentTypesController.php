<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PaymentType;
use App\Utils\UploadImage;

class PaymentTypesController extends Controller
{
    private $method = 'payment-types';

    private $folder = 'payment_types';

    private $redirectRoute = 'admin_payment_types';

    private $returnDataFields = ['name'];

    private $uploadFolder = 'payment_types';
 
    private $requiredFields = ['name_ru'];
  
    private $input;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() 
    {
        $this->model  = new PaymentType;
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
            'method' => $this->method,
            'folder'  => $this->uploadFolder
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
            'folder'        => $this->uploadFolder
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

        $uploadImage       = new UploadImage;
        $image             = $uploadImage->upload('image', $this->uploadFolder); 
        $image_black_white = $uploadImage->upload('image_black_white', $this->uploadFolder);

        if (!empty($image)) {
            $input['image'] = $image;
        } 

        if (!empty($image_black_white)) {
            $input['image_black_white'] = $image_black_white;
        }  

        return $input;
    } 
}
