<?php 

namespace App\Utils\Payments\PaymentCenterService; 

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

	protected $errResponse;
	
	function __construct()
	{ 
		$this->logModel = new PaymentLogResponse;
		if (setting('test_payment_mode') == 'on') 
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

	protected function makeRequest($arrayRequest, $requestType)
	{  
		$body      = http_build_query($arrayRequest);
		$signature = $this->getSignature($body, $this->secretKey[$this->mode]);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->serviceHostname . $requestType);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $body);  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['signature:'.$signature]);

		$server_output = curl_exec($ch); 
		curl_close ($ch);  
 
		return simplexml_load_string($server_output); 
	}

	protected function getSignature($body)
	{
		$hash = hash_hmac('sha256', $body, $this->secretKey[$this->mode], false);
 		return base64_encode($hash);	
	}
}