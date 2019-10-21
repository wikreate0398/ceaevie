<?php 

namespace App\Utils\Payments; 

use App\Models\PaymentLogResponse; 

class PaymentService
{ 
	protected $testMode  = false;

	protected $mode = 'production';

	protected $amount;

	protected $orderId;

	protected $tranId;

	protected $description;

	protected $cardCredentials;

	protected $logModel;
	
	function __construct()
	{ 
		$this->logModel = new PaymentLogResponse;
		if (setting('payment_mode') == 'on') 
		{
			$this->testMode = true;
			$this->mode     = 'dev';
		}
	}

	public function setAmount($amount)
	{
		$this->amount = $amount;
		return $this;
	}

	public function setOrderId($orderId)
	{
		$this->orderId = $orderId;
		return $this;
	}
  
	public function setDescription($description)
	{
		$this->description = $description;
		return $this;
	}

	public function setCardCredentials($cardCredentials)
	{
		$this->cardCredentials = $cardCredentials;
		return $this;
	}
}