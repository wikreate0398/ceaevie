<?php 

namespace App\Utils\PaymentServices\Methods; 

class GooglePay extends PaymentMethod
{
	private $paymentData;
 
	public function __construct($paymentData, $invoiceId, $invoiceToken)
	{
		parent::__construct($invoiceToken);
		
		$this->paymentData  = $paymentData;
		$this->invoiceId    = $invoiceId; 
	}

	public function pay()
	{
		$paymentResource = $this->createPaymentResource(); 
		exit(print_arr($paymentResource));
		$this->paymentToolToken = $paymentResource['paymentToolToken'];
		$this->paymentSession   = $paymentResource['paymentSession']; 
		$this->createPayment();
	}

	public function createPaymentResource()
	{
		return $this->http->post('https://api.rbk.money/v2/processing/payment-resources', [
			'paymentTool' => [
				'paymentToolType'   => 'TokenizedCardData',
				'provider'          => 'GooglePay',
				'gatewayMerchantID' => 'rbkmoney-test',
				'paymentToken'      => [
					'cardInfo'           => $this->paymentData['info'],
					'paymentMethodToken' => 'PAYMENT_GATEWAY',
					'token'              => $this->paymentData['tokenizationData']['token']
				]
			],

			'clientInfo' => [
				'fingerprint' => random_str(10)
			]
		]);
	} 
}