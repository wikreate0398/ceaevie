<?php

namespace App\Http\Controllers\Profile;
 
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use App\Models\BackgroundColor; 
use App\Models\QrCode;

class WorkspaceController extends Controller
{ 
    public function index()
    { 
    	$backgrounds = BackgroundColor::orderByPageUp()->visible()->get();
        return view('profile.workspace', compact(['backgrounds'])); 
    }  

    public function addQrCode(Request $request)
    {
    	if (!$request->card_signature or !$request->institution_name or !$request->background) 
        {
            return \JsonResponse::error(['messages' => \Constant::get('REQ_FIELDS')]);
        }

        $code    = $this->generateCode();  
        $qrImage = $this->genereateQrCode($code);
          
        QrCode::create([
        	'id_user'          => \Auth::user()->id,
        	'card_signature'   => $request->card_signature,
        	'institution_name' => $request->institution_name,
        	'id_background'    => $request->background,
        	'code'             => $code,
        	'qr_code'          => $qrImage
        ]);

        return \JsonResponse::success(['messages' => \Constant::get('DATA_SAVED'), 'reload' => true]);
    }

    private function genereateQrCode($code)
    { 
        $qrImage = "qrcode_{$code}.png";
    	\QrCode::format('png')
               ->size(500)
               ->generate(\URL::to("open-payment/$code"), public_path("uploads/qr_codes/{$qrImage}"));

        return $qrImage;
    }

    public function generateCode()
    {
	    $code = rand(0,9) . rand(0,9) . rand(0,9) . '-' . rand(0,9);
	    if(QrCode::where('code', $code)->count())
	    {
	    	$this->generateCode();
	    }
	    return $code;
    }
}