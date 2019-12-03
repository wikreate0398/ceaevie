<?php 

namespace App\Utils\PaymentServices\Methods; 
  
class Visa extends PaymentMethod
{    
	public function __construct($paymentData)
	{
		parent::__construct();

		$this->invoiceId        = $paymentData['invoiceId'];
		$this->paymentToolToken = $paymentData['paymentToolToken'];
		$this->paymentSession   = $paymentData['paymentSession'];
	}

	public function pay()
	{   
		return $this->createPayment();
	} 
}