<?php 

namespace App\Utils\PaymentServices\Components; 

use App\Utils\PaymentServices\Http\Curl;

abstract class Component
{  
	const SHOP_ID = '9e7497e4-fb8b-4c42-a4a6-ffb76600cff8';

	protected $http;
	
	public function __construct()
	{
		$this->http = new Curl; 
	} 

	abstract public function create($params = []);
}