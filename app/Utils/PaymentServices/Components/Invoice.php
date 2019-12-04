<?php 

namespace App\Utils\PaymentServices\Components;  

class Invoice extends Component
{ 
	const CREATE_URI = 'https://api.rbk.money/v2/processing/invoices';  

	public function __construct()
	{
		parent::__construct();
	}

	public function create($params = [])
	{
		$dueTime = \Carbon\Carbon::now()->addMinutes(40); 
		$requestData = array_replace_recursive([
			'shopID'   => self::SHOP_ID,
			'dueDate'  => $dueTime->format('Y-m-d').'T'.$dueTime->format('H:i:s').'Z',
			'currency' => 'RUB' 
		], $params); 
		
		return $this->http->post(self::CREATE_URI, $requestData);
	} 

	public function get($id)
	{ 
		return $this->http->get('https://api.rbk.money/v2/processing/invoices/' . $id);
	} 
}