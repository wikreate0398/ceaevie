<?php
 
namespace App\Utils; 

use App\Models\QrCode;

class QrCodeGenerator
{
	private $code;
  
    public function generateCode()
    {
    	$code = rand(0,9) . rand(0,9) . rand(0,9) . '-' . rand(0,9);
	    if(QrCode::where('code', $code)->count())
	    {
	    	$this->generateCode();
	    }
	    $this->code = $code;
	    return $this;
    }

    public function genereateImage()
    {
    	$qrImage = "qrcode_{$this->code}.svg"; 
        $url     = route('payment', ['lang' => 'ru', 'code' => $this->code]);
    	\QrCode::format('svg')
               ->size(500)  
               ->margin(0)  
               ->generate($url, public_path("uploads/qr_codes/{$qrImage}"));

        return $qrImage;
    }

    public function getCode()
    {
    	return $this->code;
    }
}