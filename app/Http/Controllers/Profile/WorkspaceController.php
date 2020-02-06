<?php

namespace App\Http\Controllers\Profile;
 
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use App\Utils\QrCodeGenerator;
use App\Models\BackgroundColor; 
use App\Models\QrCode;
use App\Models\PaymentType;
use App\Models\ProfileMenu;

class WorkspaceController extends Controller
{ 
    public function index()
    {   
        $payments    = PaymentType::orderByPageUp()->visible()->get();
    	$backgrounds = BackgroundColor::orderByPageUp()->visible()->get();
        $qr          = QrCode::where('id_user', \Auth::user()->id)->with('background')->get();
        $menu        = ProfileMenu::where('route', 'workspace')->first();
        return view('profile.workspace', compact(['backgrounds', 'qr', 'payments', 'menu']));
    }  

    public function addQrCode(Request $request, QrCodeGenerator $qrGenerator)
    {
    	if (!$request->card_signature or !$request->institution_name or !$request->background) 
        {
            return \JsonResponse::error(['messages' => \Constant::get('REQ_FIELDS')]);
        }

        if (QrCode::where('id_user', \Auth::user()->id)->count() == 3) 
        {
            return \JsonResponse::error(['messages' => 'Ошибка']);
        }

        $qrImage = $qrGenerator->generateCode()
                               ->genereateImage();

        QrCode::create([
        	'id_user'          => \Auth::user()->id,
        	'card_signature'   => $request->card_signature,
        	'institution_name' => $request->institution_name,
        	'id_background'    => $request->background,
        	'code'             => $qrGenerator->getCode(),
        	'qr_code'          => $qrImage
        ]);

        return \JsonResponse::success(['messages' => 'Qr Код успешно сгенерирован', 'reload' => true]);
    }

    public function deleteQrCode($lang, $id)
    { 
        $qrCode = QrCode::where('id_user', \Auth::user()->id)->whereId($id)->firstOrFail();
        $qrCode->delete();
        return redirect()->back()->with('lk_success', 'Запись успешно удалена');
    }

    public function qrCodeToPdf($lang, $id)
    { 
        //return redirect()->back();
        $qrCode = QrCode::where('id_user', \Auth::user()->id)->whereId($id)->firstOrFail();
        $file   = public_path('uploads/qr_codes/' . $qrCode->qr_code);

        exit($file );
        $image  = new \Imagick($file); 
        $image->setImageFormat('pdf');
 
        $filename = $this->fileNameToPdf($qrCode->qr_code);
        $filePath = public_path('uploads/qr_codes/pdf/'); 
        $image->writeImage($filePath . $filename); 

       $this->downloadPdf($filePath, $filename); 

        // return \PDF::loadFile(public_path('uploads/qr_codes/' . $qrCode->qr_code))
        //           ->save($filePath . $filename)
        //           ->stream($filename);

    }

    private function downloadPdf($filePath, $filename)
    {
        $content = file_get_contents($filePath . $filename);
        header('Content-Type: application/pdf');
        header('Content-Length: '.strlen( $content ));
        header('Content-disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: public, must-revalidate, max-age=0');
        header('Pragma: public');
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
        echo $content; 
    }

    private function fileNameToPdf($filename)
    {
        $explodeFileName = explode('.', $filename);
        return $explodeFileName[0] . '.pdf';
    }

    private function genereateQrCode($code)
    { 
        $qrImage = "qrcode_{$code}.svg";

        $url = getAppUrl('pay') . '/ru/make-payment/' . $code;
    	\QrCode::format('svg')
               ->size(500)  
               ->margin(0)  
               ->generate($url, public_path("uploads/qr_codes/{$qrImage}"));

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