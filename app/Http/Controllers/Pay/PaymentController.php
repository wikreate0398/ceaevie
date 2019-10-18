<?php

namespace App\Http\Controllers\Pay;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Utils\Payments\VisaPayment;
use App\Models\QrCode;
use App\Models\PaymentType; 

class PaymentController extends Controller
{
	public function indicateСode()
	{ 
		return view('public.payment.indicate_сode');
	}

	public function setСode(Request $request)
	{   sleep(2);
		
		$code = $this->prepareCode($request->code); 

		$getQr = QrCode::where('code', $code)->first();

		if (!$getQr) 
		{
			return \JsonResponse::error(['messages' => 'Официант не найден']);
		}

		return \JsonResponse::success(['redirect' => route('payment', ['lang' => lang(), 'code' => $code])], false);
	}

	private function prepareCode($code)
	{	
		if (!strpos($code, '-') && strlen($code) >= 4) 
		{
			$code = substr($code, 0, 3) . '-' . substr($code, 3, 4);
		}
		return $code;
	}

	public function payment($lang, $code)
	{ 
		$data     = QrCode::where('code', $code)->with('user')->firstOrFail();
		$payments = PaymentType::orderByPageUp()->visible()->get();
		return view('public.payment.make_payment', compact(['data', 'payments']));
	}

	public function makePayment(Request $request)
    {
    	if (!$request->payment or !$request->price) 
    	{
    		return \JsonResponse::error(['messages' => 'Укажите сумму и метод оплаты']);
    	}

    	$payment = PaymentType::visible()->whereId($request->id)->first();

    	if ($request->payment == 1) {
    		$paymentClass = new VisaPayment;

    		try {
    			$token = $paymentClass->orderId(generate_id())
    		                      ->amount($request->price)
    		                      ->description('Чаевые официанту')
    		                      ->getToken();

    		    return \JsonResponse::success(['redirect' => $token]); 
    		} catch (\Exception $e) {
    			return \JsonResponse::error(['messages' => $e->getMessage()]);
    		}               
    	}
    }
}
