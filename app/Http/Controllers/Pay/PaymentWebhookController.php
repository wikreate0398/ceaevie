<?php

namespace App\Http\Controllers\Pay;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\NewTips;
use App\Utils\Payments\VisaPayment;
use App\Utils\Ballance; 
use App\Models\PaymentLogResponse; 
use App\Models\Tips; 
use App\Models\WithdrawTips; 
use App\Utils\Payments\WithdrawalService; 

class PaymentWebhookController extends Controller
{
	public function visa(Request $request, Ballance $userBallance)
	{ 
		$this->log($request->all(), @$request->Event, @$request->Order_Id); 
  
		if ($request->Order_Id) 
		{
			if ($request->Event == 'Payment') 
			{ 
				$tip = Tips::where('rand', $request->Order_Id)->first();
				if ($tip) 
				{

					if ($request->Event == 'Payment' && $request->Status == 'BLOCKED') 
					{ 
						$paymentClass = new VisaPayment; 
			    		$data = $paymentClass->setTranId($request->Transaction_Id)
			    		                     ->setAmount(toFloat($tip->amount))
			    		                     ->makeCharge();

			    		$this->log($data); 
					}

					$tip->status         = $request->Status;
					$tip->rrn            = @$request->RRN;
					$tip->id_transaction = $request->Transaction_Id;

					if (!empty($request->NewAmount)) 
					{
						$tip->amount = toFloat($request->NewAmount); 
					} 
	 
					$tip->save();

					if ($request->Status == 'CHARGED') 
					{
						$userBallance->setUser($tip->user)
				                     ->setOrderId($tip->id)
				                     ->setPrice($tip->amount)
				                     ->replenish();

						$tip->user->notify(new NewTips($tip->amount));
					}
				} 
			}
			elseif ($request->Event == 'Payout') 
			{
				$withdraw = WithdrawTips::where('rand', $request->Order_Id)->first();
				if ($withdraw) 
				{ 
					$withdraw->status         = ($request->Status == 'CHARGED') ? 'SUCCESS' : ''; 
					$withdraw->id_transaction = $request->Transaction_Id;
       				$withdraw->pan_ref_token  = $request->panRefToken ?: ''; 
       				$withdraw->save();
				}
			}
		} 
	}

	private function log($data, $action = null, $rand = null)
	{
		PaymentLogResponse::create([
			'order_rand'   => !empty($data->Order_Id) ? $data->Order_Id : '',
			'payment_mode' => !empty($data->IsTest) ? 'dev' : 'production',
			'flag'         => !empty($data->Status) ? $data->Status : '', 
			'log'          => json_encode($data),
			'action'       => $action ?: '',
			'order_rand'   => $rand ?: ''
		]);
	}
}
