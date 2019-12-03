<?php 

namespace App\Utils\PaymentServices; 

use App\Utils\PaymentServices\Methods\PaymentMethod; 

class HandlePaymentMethod
{	
	public function handle(PaymentMethod $paymentMethod)
	{
		return $paymentMethod->pay();
	}
}