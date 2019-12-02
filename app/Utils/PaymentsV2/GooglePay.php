<?php 

 
class GooglePay 
{
	
	function __construct()
	{
	}

	public function createPaymentResource()
	{
		$data = [
			'paymentTool' => [
				'paymentToolType' => 'TokenizedCardData',
				'provider'        => 'GooglePay',
				'gatewayMerchantID' => 'rbkmoney-test',
				'paymentToken' => [
					'cardInfo' => [
						'cardNetwork'     => '',
						'cardDetails'     => '',
						'cardImageUri'    => '',
						'cardDescription' => '',
						'cardClass'       => ''
					],
					'paymentMethodToken' => [
						'tokenizationType' => 'PAYMENT_GATEWAY',
						'token' => []
					]
				]
			],

			'clientInfo' => [
				'fingerprint' => random_str(10)
			]
		];
	}
}