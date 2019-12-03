<?php 

namespace App\Utils\PaymentServices\Components; 

use App\Utils\PaymentServices\Http\Curl;

abstract class Component
{  
	const SHOP_ID = '42a87c14-eeee-41e8-ac15-751ece64e417';

	protected $http;
	
	public function __construct()
	{
		$this->http = new Curl; 
	} 

	abstract public function create($params = []);
}