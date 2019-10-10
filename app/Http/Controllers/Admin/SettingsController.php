<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Utils\UploadImage;
 
class SettingsController extends Controller
{
    private $method;

    private $folder = 'settings';

    private $uploadFolder = 'uploads';

    private $redirectRoute = 'admin_settings';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() 
    {
        $this->model  = new Settings;
        $this->method = config('admin.path') . '/settings';
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
            'method' => $this->method,
            'table'  => $this->model->getTable(),
            'folder' => $this->uploadFolder
        ]; 

        return view('admin.'.$this->folder.'.list', $data);
    } 

    public function save(Request $request)
    {
        $this->model->where('id', '>', '0')
                    ->where('let_alone', '0')
                    ->update(array('value' => ''));


        if (!empty($request->settings))
        {
            foreach ($request->settings as $key => $value)
            {
                $this->model->where('id', $key)->update(array('value' => $value));
            }
        }

        $files       = $this->model->where('type', 'image')->get();
        $uploadImage = new UploadImage;

        foreach ($files as $file)
        {
            if($request->hasFile($file->var))
            {
                if($image = $uploadImage->upload($file->var, $this->uploadFolder))
                {
                    $file->value = $image;
                    $file->save();
                }
            }
        }


        return \App\Utils\JsonResponse::success(['redirect' => route($this->redirectRoute)], trans('admin.save')); 
    } 
}
