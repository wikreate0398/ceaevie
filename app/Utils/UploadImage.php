<?php

namespace App\Utils;
use Illuminate\Http\Request;   
use Spatie\ImageOptimizer\OptimizerChainFactory;

class UploadImage  
{  
	function __construct() {} 

	public function upload($name, $path, $fileName = '')
	{
		if (\Request::hasFile($name) != false) 
        { 
        	$file = \Request::file($name);

            $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $path = public_path() . '/uploads/'.$path.'/';
        	$file->move($path, $fileName);   

        	$optimizerChain = OptimizerChainFactory::create();

			$optimizerChain->optimize($path.$fileName); 
        }
        
        return $fileName;
	}	 
}