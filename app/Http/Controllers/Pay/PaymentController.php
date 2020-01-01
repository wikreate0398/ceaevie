<?php

namespace App\Http\Controllers\Pay;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\Utils\Ballance;  
use App\Models\QrCode;
use App\Models\PaymentType; 
use App\Models\Tips; 
use App\Models\EnrollmentPercents;
use App\Models\TipPercents;

use App\Utils\Payments\RbkService\Components\Invoice;  
use App\Utils\Payments\PaymentCenterService\VisaPayment;

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

    public function formPayment(Request $request)
    {  
        \DB::beginTransaction();
        try {
            $this->checkFormData($request); 
        } catch (\Exception $e) {
            return \JsonResponse::error(['messages' => $e->getMessage()]);
        }

        try {
            
            $idOrder = $this->makeOrder($request);
            $order   = Tips::whereId($idOrder)->first();

            if ($request->payment == 'payment_center') 
            {
                \DB::commit();
                return \JsonResponse::success([
                    'redirect' => route('visa_webpay', ['lang' => lang(), 'orderRand' => $order->rand])
                ]);
            }
            else
            {
                $invoice = new Invoice;
                $invoiceData = $invoice->create([
                    'amount'     => intval($order->total_amount . '00'),
                    'currency'   => 'RUB',
                    'product'    => 'Чаевые официанту ' . $order->user->name, 
                    'cart'       => [
                        [
                            'product' => 'Чаевые официанту ' . $order->user->name,
                            'quantity' => 1,
                            'price'    => intval($order->total_amount . '00'),
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

                $order->id_invoice = $invoiceData['invoice']['id'];
                $order->save();

                \DB::commit(); 
                return \JsonResponse::success([
                    'redirect' => 'https://checkout.rbk.money/v1/checkout.html?invoiceID='.$order->id_invoice.'&invoiceAccessToken='.$invoiceData['invoiceAccessToken']['payload'].'&name=Chaevie%20Online&description=Чаевые официанту '.$order->user->name.'&applePay=true&googlePay=true&samsungPay=false&bankCard=true&popupMode=true&locale=auto'
                ]);
            }
            
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

        $location_fee    = 0;
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
    		'id_payment'          => ($request->payment == 'payment_center') ? 1 : '',
            'payment_service'     => $request->payment ?: '',
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

    private function checkFormData($request)
    { 
        $qrCode = QrCode::where('code', $request->code)->with('user')->first();

        if (empty($request->payment) or !in_array($request->payment, ['payment_center', 'rbk'])) 
        {
            throw new \Exception("Укажите тип оплаты"); 
        } 
        
        if (!toFloat($request->price) or !$request->code) 
        { 
            throw new \Exception("Укажите все обязательные поля"); 
        }  

        if (empty($qrCode) or @$qrCode->user->{$request->payment} == false) 
        {
            throw new \Exception("Во время обработки данных возникла ошибка");   
        } 
    }  
}
