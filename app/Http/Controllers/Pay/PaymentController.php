<?php

namespace App\Http\Controllers\Pay;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\Utils\Ballance; 
use App\Utils\Payments\VisaPayment;
use App\Models\QrCode;
use App\Models\PaymentType; 
use App\Models\Tips; 
use App\Models\EnrollmentPercents;
use App\Models\TipPercents;

use App\Utils\PaymentServices\HandlePaymentMethod; 
use App\Utils\PaymentServices\Methods\Visa;
use App\Utils\PaymentServices\Methods\GooglePay; 
use App\Utils\PaymentServices\Components\Invoice;  

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
		
		$code = prepareCode($request->code); 

		$getQr = QrCode::where('code', $code)->first();

		if (!$getQr) 
		{
			return \JsonResponse::error(['messages' => 'Официант не найден']);
		}

		return \JsonResponse::success(['redirect' => route('payment', ['lang' => lang(), 'code' => $code])], false);
	} 

	public function payment($lang, $code)
	{ 
		$data     = QrCode::where('code', $code)->with('user')->firstOrFail();
		$payments = PaymentType::orderByPageUp()->visible()->get();
		return view('public.payment.make_payment', compact(['data', 'payments']));
	}

    public function payment2($lang, $code)
    {    
        $data     = QrCode::where('code', $code)->with('user')->firstOrFail();
        $payments = PaymentType::orderByPageUp()->visible()->get();
        return view('public.payment.make_payment2', compact(['data', 'payments']));
    } 

    public function generateInvoice(Request $request, Invoice $invoice)
    { 
        $qrCode = QrCode::where('code', $request->code)->with('user')->first();  
        $amount = toFloat($request->amount) . '00';  

        $invoiceData = $invoice->create([
            'amount'     => intval($amount),
            'currency'   => 'RUB',
            'product'    => 'Чаевые официанту ' . $qrCode->user->name, 
            'cart'       => [
                [
                    'product' => 'Чаевые официанту ' . $qrCode->user->name,
                    'quantity' => 1,
                    'price'    => intval($amount),
                    'taxMode'  => [
                        'type' => 'InvoiceLineTaxVAT',
                        'rate' => '0%'
                    ]
                ]
            ],
            'metadata' => [
                'order_id' => uniqid()
            ]
        ]);  

        return $invoiceData;
    }

	public function formPayment(Request $request)
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
            if ($order->id_payment == 1)
            {
                return \JsonResponse::success([
                    'redirect' => route('visa_webpay', ['lang' => lang(), 'orderRand' => $order->rand])
                ]);
            } 
            else
            {
                throw new \Exception("Данные метод оплаты не работает. Попробуйте оплатить с помощью VISA"); 
            } 
    	} catch (\Exception $e) {
    		\DB::rollback();
    		return \JsonResponse::error(['messages' => $e->getMessage()]);
    	}
    } 

    public function formPayment2(Request $request, HandlePaymentMethod $paymentMethod, Invoice $invoice)
    {  
        \DB::beginTransaction();
        try {
            $this->checkFormData2($request); 
        } catch (\Exception $e) {
            return \JsonResponse::error(['messages' => $e->getMessage()]);
        }

        try {
 
            $idOrder = $this->makeOrder($request);
            $order   = Tips::whereId($idOrder)->first();
             
            if ($order->id_payment == 1)
            { 
                $paymentData = $paymentMethod->handle(new Visa([
                    'paymentToolToken' => $request->paymentToolToken,
                    'paymentSession'   => $request->paymentSession, 
                    'invoiceId'        => $request->invoiceId,
                ]));  
            } 
            elseif ($order->id_payment == 2) 
            {
                $googlePayResponse = json_decode($request->google_pay, true);

                $getInvoice = $invoice->get($request->invoiceId);
                exit(print_arr($getInvoice)); 

                if (empty($googlePayResponse['paymentMethodData']['tokenizationData']['token'])) 
                {
                    throw new \Exception("Произошла ошибка. Попробуйте повторить попытку сново.");
                }

                $paymentData = $paymentMethod->handle(new GooglePay($googlePayResponse['paymentMethodData'], $request->invoiceId));     

                exit(print_arr($paymentData)); 
            }
            else
            {
                throw new \Exception("Данные метод оплаты не работает. Попробуйте оплатить с помощью VISA"); 
            } 

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollback();
            return \JsonResponse::error(['messages' => $e->getMessage()]);
        }
    }

    public function visaWebPay($lang, $orderRand)
    {
        $order        = Tips::where('rand', $orderRand)->firstOrFail(); 
        $paymentClass = new VisaPayment;  
        $paymentClass->setOrderId($order->rand)
                     ->setAmount(toFloat($order->total_amount))
                     ->setDescription('Чаевые официанту ' . $order->user->name);

        $paymentData = $paymentClass->webpay();  
        $link = $paymentClass->getServiceHostname() . 'webpay';
 
        return view('public.payment.visa_webpay', compact(['paymentData', 'link']));
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
                $message = @request()->session()->get('flash_message'); 
                break;
        } 
 
        $lang = lang();
        return view('public.payment.message', compact(['message', 'lang']));
    }
 
    private function makeOrder($request)
    {  
        $qrCode      = QrCode::where('code', $request->code)->with('user')->first();

        // процент приложния
        $percents    = EnrollmentPercents::select('percent', 'id')->get(); 
        $fee         = $qrCode->user->fee ?: $percents->sum('percent');

        $totalAmount = toFloat($request->price);
        $amount      = withdrawFee($totalAmount, $fee);

        $qrCode      = QrCode::where('code', $request->code)->with('location')->first();

        $location_fee = 0;
        $location_amount = 0;
        if (!empty($qrCode->location)) 
        {
            if ($qrCode->location->work_type == 'percent') 
            {
                $location_fee = $qrCode->location->self_percent; 
                $location_amount = withdrawFee($amount, $location_fee);
            } 
        }

    	$tipId = Tips::create([
    		'id_user'             => $qrCode->id_user,
            'id_location'         => $qrCode->id_location,
    		'id_payment'          => $request->payment,
            'id_qrcode'           => $qrCode->id,
    		'rand'                => generate_id(7),
    		'total_amount'        => $totalAmount,
            'amount'              => $location_amount ? $amount - $location_amount : $amount,
            'location_fee'        => $location_fee,
            'location_amount'     => $location_amount,
            'location_work_type'  => @$qrCode->location->work_type ?: '',
            'fee'                 => $fee, 
            'rating'              => $request->rating ?: '',
            'review'              => $request->review ?: '',
    	])->id; 

        $percents->each(function($percent) use($tipId){
            TipPercents::insert([
                'id_tip'     => $tipId,
                'id_percent' => $percent->id,
                'percent'    => $percent->percent
            ]);
        });

        return $tipId;
    }

    private function checkFormData2($request)
    { 
        if (!$request->payment or !toFloat($request->price) or !$request->code or 
            $request->payment == 1 && (!$request->paymentToolToken or !$request->paymentSession)
            or $request->payment == 2 && !$request->google_pay) 
        { 
            throw new \Exception("Укажите все обязательные поля"); 
        } 
 
        if (!QrCode::where('code', $request->code)->count() or !PaymentType::visible()->whereId($request->payment)->count()) 
        {
            throw new \Exception("Во время обработки данных возникла ошибка");   
        } 
    } 

    private function checkFormData($request)
    { 
    	if (!$request->payment or !toFloat($request->price) or !$request->code) 
    	{ 
    		throw new \Exception("Укажите все обязательные поля"); 
    	} 
 
		if (!QrCode::where('code', $request->code)->count() or !PaymentType::visible()->whereId($request->payment)->count()) 
    	{
    		throw new \Exception("Во время обработки данных возникла ошибка");   
    	} 
    } 
}
