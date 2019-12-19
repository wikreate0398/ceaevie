<?php 

namespace App\Utils\Payments\RbkService\Components; 

use App\Utils\Payments\RbkService\Http\Curl;

abstract class Component
{  
	protected $shopId; // production

	protected $http;
	
	public function __construct()
	{ 
		$this->shopId = (setting('test_payment_mode') == 'on') ? '42a87c14-eeee-41e8-ac15-751ece64e417' : '9e7497e4-fb8b-4c42-a4a6-ffb76600cff8' ;
		$this->http   = new Curl; 
	} 

	abstract public function create($params = []);
}