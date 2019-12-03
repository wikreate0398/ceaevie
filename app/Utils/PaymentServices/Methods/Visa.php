<?php 

namespace App\Utils\PaymentServices\Methods; 
  
class Visa extends PaymentMethod
{ 
	private $cardCredentials = [];
	
	public function __construct($cardCredentials)
	{
		parent::__construct();
		$this->cardCredentials = $cardCredentials;
	}

	public function pay()
	{  
		$expiryDate = prepareExpiryDate($this->cardCredentials['expiry_date'], true);

		return $this->http->post('https://api.rbk.money/v2/processing/payment-resources', [  
			'paymentTool' => [
				'paymentToolType' => 'CardData',
				'cardNumber' => $this->cardCredentials['number'],
				'expDate'    => $expiryDate[0] . '/' . $expiryDate[1],
				'cardHolder' => $this->cardCredentials['name']
			],

			'clientInfo' => [
				'fingerprint' => uniqid()
			]
		]);
	} 
}