<?php

namespace App\Http\Controllers\Pay;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\NewTips;
use App\Utils\Payments\PaymentLogResponse; 
use App\Models\Tips; 

class PaymentWebhookController extends Controller
{
	public function visa(Request $request)
	{ 
		$this->log($request->all());

		$requestData = json_decode($request->all(), true);

		if ($requestData['Order_id']) 
		{
			$tip = Tips::where('rand', $requestData['Order_id'])->first();
			if ($tip) 
			{
				$tip->status = $requestData['Status'];

				if (!empty($requestData['NewAmount'])) 
				{
					$tip->amount = toFloat($requestData['NewAmount']); 
				} 

				if ($requestData['Status'] == 'CHARGED') 
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
			'log'          => $data
		]);
	}
}
