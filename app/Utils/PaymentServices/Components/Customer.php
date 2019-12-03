<?php 

namespace App\Utils\PaymentServices\Components;  

class Customer extends Component
{ 
	const CREATE_URI = 'https://api.rbk.money/v2/processing/customers';   

	public function __construct()
	{
		parent::__construct();
	}

	public function create($params = [])
	{ 
		$requestData = array_replace_recursive([
			'shopID'      => self::SHOP_ID,
			'contactInfo' => [
				'email' => '',
				'phoneNumber' => ''
			],
			'metadata'    => []
		], $params); 

		return $this->http->post(self::CREATE_URI, $requestData);
	} 
}