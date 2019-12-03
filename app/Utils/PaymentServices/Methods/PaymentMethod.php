<?php 

namespace App\Utils\PaymentServices\Methods; 

use App\Utils\PaymentServices\Components\Customer;
use App\Utils\PaymentServices\Http\Curl; 

abstract class PaymentMethod
{
	protected $invoice;

	protected $http;

	protected $customer;

	public function __construct($invoice = false) 
	{
		$this->invoice  = $invoice ? $invoice['invoice'] : [];
		$this->http     = new Curl; 
		$this->customer = new customer;
	}

	public function createCustomer()
	{
		return $this->customer->create();
	}

	abstract public function pay();
}