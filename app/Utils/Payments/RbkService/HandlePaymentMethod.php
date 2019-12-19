<?php 

namespace App\Utils\Payments\RbkService; 

use App\Utils\Payments\RbkService\Methods\PaymentMethod; 

class HandlePaymentMethod
{	
	public function handle(PaymentMethod $paymentMethod)
	{
		return $paymentMethod->pay();
	}
}