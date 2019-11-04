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
        $this->validate($name);

        $file     = \Request::file($name); 
        $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
        $path     = public_path() . '/uploads/'.$path.'/';
        $file->move($path, $fileName);   

        $optimizerChain = OptimizerChainFactory::create();

        $optimizerChain->optimize($path.$fileName); 
        
        return $fileName;
	}	

    private function validate($name)
    {    
        $validator = Validator::make(request()->all(), [
            "{$name}" => 'required|file|mimes:' . $this->ext . '|max:' . $this->size
        ]);

        if ($validator->fails()) 
        {  
            throw new \Exception('Убедитесь что ваш файл содержит формат ' . $this->ext . ' и его размер не превышает ' . ($this->size/1000) . 'Мб' ); 
        } 

    } 
}