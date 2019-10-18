<?php

namespace App\Http\Controllers\Pay;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\NewTips;
use App\Utils\Payments\VisaPayment;
use App\Models\PaymentLogResponse; 
use App\Models\Tips; 

class PaymentWebhookController extends Controller
{
	public function visa(Request $request)
	{ 
		$this->log($request->all()); 
  
		if ($request->Order_Id) 
		{
			$tip = Tips::where('rand', $request->Order_Id)->first();
			if ($tip) 
			{

				if ($request->Event == 'Payment' && $request->Status == 'BLOCKED') 
				{
					$paymentClass = new VisaPayment; 
		    		$paymentClass->orderId($tip->rand)
		    		             ->amount(toFloat($tip->amount))
		    		             ->makeCharge();
				}

				$tip->status         = $request->Status;
				$tip->id_transaction = $request->Transaction_Id;

				if (!empty($request->NewAmount)) 
				{
					$tip->amount = toFloat($request->NewAmount); 
				} 

				if ($request->Status == 'CHARGED') 
				{
					$tip->user->notify(new NewTips($tip->amount));
				}

				$tip->save();
			} 
		} 
	}

	private function log($data)
	{
		PaymentLogResponse::create([
			'payment_type' => 'visa',
			'log'          => json_encode($data)
		]);
	}
}
