<?php 

namespace App\Utils\PaymentServices\Methods; 
 
use App\Utils\PaymentServices\Http\Curl; 

abstract class PaymentMethod
{  
	protected $http;

	protected $customer;

	protected $invoiceId;

	protected $paymentToolToken;

	protected $paymentSession;

	public function __construct() 
	{ 
		$this->http = new Curl;  
	} 

	public function createPayment()
	{   
		return $this->http->post('https://api.rbk.money/v2/processing/invoices/'.$this->invoiceId.'/payments', [
			'flow' => [
				'type'             => 'PaymentFlowInstant', 
				'onHoldExpiration' => 'cancel'
			],

			'payer' => [
				'payerType'        => 'PaymentResourcePayer',
				'paymentToolToken' => $this->paymentToolToken,
				'paymentSession'   => $this->paymentSession,
				'contactInfo'      => [
					'email'       => 'user@mail.ru',
					'phoneNumber' => ''
				]	 
			]  
		]);
	} 

	abstract public function pay();
}