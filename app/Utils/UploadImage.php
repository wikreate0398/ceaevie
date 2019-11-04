<?php

namespace App\Utils;
use Illuminate\Http\Request;   
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Illuminate\Support\Facades\Validator;

class UploadImage  
{  

    private $ext = 'jpeg,jpg,png,svg,gif';

    private $size = 2000;

	function __construct() {} 

    public function setExtensions($ext)
    {
        $this->ext = $ext;
        return $this;
    }

    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

	public function upload($name, $path, $fileName = '')
	{    
        if ($this->validate($name) == false) 
        {
            throw new \Exception('Убедитесь что ваш файл содержит формат ' . $this->ext . ' и его размер не превышает ' . ($this->size/1000) . 'Мб' );
        }

        $file     = \Request::file($name); 
        $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
        $path     = public_path() . '/uploads/'.$path.'/';
        $file->move($path, $fileName);   

        $optimizerChain = OptimizerChainFactory::create(); 
        $optimizerChain->optimize($path.$fileName); 
        
        return $fileName;
	}	

    public function multipleUpload($name, $path)
    {
        if ($this->validate($name .'.*') == false) 
        {
            throw new \Exception('Убедитесь что ваш файл содержит формат ' . $this->ext . ' и его размер не превышает ' . ($this->size/1000) . 'Мб' );
        }

        $fileNames = [];
        foreach (request()->all()[$name] as $key => $file) 
        { 
            $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension(); 
            $file->move(public_path() . '/uploads/'.$path.'/', $fileName);  
            $fileNames[] = $fileName; 
        }

        return $fileNames;
    }

    private function validate($name)
    {    
        $validator = Validator::make(request()->all(), [
            "{$name}" => 'required|file|mimes:' . $this->ext . '|max:' . $this->size
        ]);

        if ($validator->fails()) 
        {  
            return false; 
        } 

        return true;
    } 
}