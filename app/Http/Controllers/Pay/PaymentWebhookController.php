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
use App\Utils\Payments\PaymentCenterService\WithdrawalService; 

class PaymentWebhookController extends Controller
{
	public function testCharged($idTip)
	{
		$tip = Tips::with('location')->whereId($idTip)->first();
		$this->chargedTip($tip); 

		$tip->status = 'CHARGED';  
		$tip->save();
	}

	public function visa(Request $request)
	{ 
		$this->log($request->all(), @$request->Event, @$request->Order_Id); 
  
		if ($request->Order_Id) 
		{
			if ($request->Event == 'Payment') 
			{ 
				$tip = Tips::with('location')->where('rand', $request->Order_Id)->first();
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
						$this->chargedTip($tip); 
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
			elseif ($request->Event == 'Fail') 
			{
				$withdraw = WithdrawTips::where('rand', $request->Order_Id)->first();
				if ($withdraw) 
				{ 
					$withdraw->status         = 'FAIL'; 
					$withdraw->request_status = 'rejected'; 
					$withdraw->id_transaction = $request->Transaction_Id;
       				$withdraw->save();

       				(new \App\Http\Controllers\Admin\WithdrawalWithdrawalRequestsController)->rejectedRequest($withdraw);
				}
			}
		} 
	}

	private function chargedTip($tip)
	{ 
		$amount = $tip->amount;
		if (!empty($tip->location)) 
		{
			if ($tip->location_work_type == 'percent') 
			{  
				// зачисляем деньги пользователю 
 				$this->enroll($tip->user, $tip->id, $amount);

	            // зачисляем деньги заведению  
	        	$this->enroll($tip->location, $tip->id, $tip->location_amount);
			}
			else
			{
				// зачисляем деньги заведению
	            $this->enroll($tip->location, $tip->id, $amount);         
			}
		}
		else
		{ 
	        $this->enroll($tip->user, $tip->id, $amount); 
		}
	}

	private function enroll($user, $tipId, $amount)
	{
		$userBallance = new Ballance;
		$userBallance->setUser($user)
                     ->setOrderId($tipId)
                     ->setPrice($amount)
                     ->replenish();

        $user->notify(new NewTips($amount));
	}

	private function log($data, $action = null, $rand = null)
	{   
		\Log::channel('payment')->info([
			'order_rand'   => !empty($data->Order_Id) ? $data->Order_Id : '',
			'payment_mode' => !empty($data->IsTest) ? 'dev' : 'production',
			'flag'         => !empty($data->Status) ? $data->Status : '', 
			'log'          => json_encode($data),
			'action'       => $action ?: '',
			'order_rand'   => $rand ?: ''
		]);
	}
}
