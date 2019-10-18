<?php

namespace App\Http\Controllers\Pay;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Utils\Payments\VisaPayment;
use App\Models\QrCode;
use App\Models\PaymentType; 
use App\Models\Tips; 

class PaymentController extends Controller
{
	private $paymentServices = [
		'1' => VisaPayment::class
	];

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

	public function handlePayment(Request $request)
    {
    	\DB::beginTransaction();
    	try {
    		$this->checkFormData($request); 
    	} catch (\Exception $e) {
    		return \JsonResponse::error(['messages' => $e->getMessage()]);
    	}
 
    	$idOrder = $this->makeOrder($request);
    	$order   = Tips::whereId($idOrder)->first();

    	\DB::commit();
    	try {
    		$tokenUrl = $this->makePayment($request->payment, $order);
    		return \JsonResponse::success(['redirect' => $tokenUrl]);  
    	} catch (\Exception $e) {
    		\DB::rollback();
    		return \JsonResponse::error(['messages' => $e->getMessage()]);
    	}
    }

    public function visaCallback(Request $request)
    { 
        switch ($request->type) {
            case 'success':
                $message = 'Оплата пошла успешна'; 
                break;

            case 'decline':
                $message = 'Оплата отклонена'; 
                break;

            case 'cancel':
                $message = 'Оплата отменена';  
                break;

            case 'account_id': 
                $message = 'Оплата пошла успешна'; 
                break;
            
            default:
                $message = 'Оплата пошла успешна'; 
                break;
        }
 
        $lang = lang();
        return view('public.payment.message', compact(['message', 'lang']));
    }
 
    private function makeOrder($request)
    {  
    	return Tips::create([
    		'id_user'    => QrCode::where('code', $request->code)->first()->id_user,
    		'id_payment' => $request->payment,
    		'rand'       => generate_id(7),
    		'amount'     => toFloat($request->price)
    	])->id;
    }

    private function checkFormData($request)
    { 
    	if (!$request->payment or !$request->price or !$request->code) 
    	{ 
    		throw new \Exception("Укажите сумму и метод оплаты"); 
    	}
 
		if (!QrCode::where('code', $request->code)->count() or !PaymentType::visible()->whereId($request->payment)->count()) 
    	{
    		throw new \Exception("Во время обработки данных возникла ошибка");   
    	} 
    } 

    private function makePayment($idPayment, $order)
    {
    	if (!empty($this->paymentServices[$idPayment])) {
    		$paymentClass = new $this->paymentServices[$idPayment]; 

    		$paymentClass->orderId($order->rand)
    		             ->amount(toFloat($order->amount))
    		             ->description('Чаевые официанту');

  			return $paymentClass->getToken();           
    	}
    	else
    	{
    		throw new \Exception("Данные метод оплаты не работает. Попробуйте оплатить с помощью VISA"); 
    	}
    } 
}
