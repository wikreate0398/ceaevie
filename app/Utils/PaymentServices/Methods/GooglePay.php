<?php 

namespace App\Utils\PaymentServices\Methods; 

class GooglePay extends PaymentMethod
{
	private $paymentToken;

	public function __construct($paymentToken, $invoiceId)
	{
		$this->paymentToken = $paymentToken;
		$this->invoiceId    = $invoiceId;
	}

	public function pay()
	{
		$paymentResource = $this->createPaymentResource(); 
		$this->paymentToolToken = $paymentResource['paymentToolToken'];
		$this->paymentSession   = $paymentResource['paymentSession']; 
		$this->createPayment();
	}

	public function createPaymentResource()
	{
		$data = [
			'paymentTool' => [
				'paymentToolType'   => 'TokenizedCardData',
				'provider'          => 'GooglePay',
				'gatewayMerchantID' => 'rbkmoney-test',
				'paymentToken'      => $this->paymentToken
			],

			'clientInfo' => [
				'fingerprint' => random_str(10)
			]
		];
	} 
}