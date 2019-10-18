<?php 

namespace App\Utils\Payments;

class VisaPayment implements PaymentInterface
{

	private $serviceHostname = 'https://secured.payment.center/v2/';

	private $serviceId = '796';

	private $secretKey = 'ZsuvzNBPrEzzNaxr';
	
	private $currency = 'RUB';

	private $amount;

	private $orderId;

	private $description;

	function __construct() {}

	public function amount($amount)
	{
		$this->amount = $amount;
		return $this;
	}

	public function orderId($orderId)
	{
		$this->orderId = $orderId;
		return $this;
	}

	public function description($description)
	{
		$this->description = $description;
		return $this;
	}

	public function getToken()
	{   
		$xmlData = $this->makeRequest([
			'serviceId'   => $this->serviceId,
			'orderId'     => $this->orderId,
			'amount'      => $this->amount,
			'currency'    => $this->currency, 
			'description' => $this->description,
			'extra'       => json_encode([
				'success_url' => route('visa_callback', ['type' => 'success']),
				'decline_url' =>  route('visa_callback', ['type' => 'decline']),
				'cancel_url'  =>  route('visa_callback', ['type' => 'cancel']),
				'account_id'  =>  route('visa_callback', ['type' => 'account_id'])
			])
		], 'token');

		if(@$xmlData->success != 'true' or !@$xmlData->token)
		{
			throw new \Exception("Ошибка оплаты, попробуйте позже"); 
		} 

		return $this->serviceHostname . 'webblock/?token=' . $xmlData->token; 
	}

	private function makeRequest($arrayRequest, $requestType)
	{
		$body      = http_build_query($arrayRequest);
		$signature = $this->getSignature($body, $this->secretKey);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->serviceHostname . $requestType);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $body);  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['signature:'.$signature]);

		$server_output = curl_exec ($ch);
		curl_close ($ch);
 
		return simplexml_load_string($server_output); 
	}

	private function getSignature($body)
	{
		$hash = hash_hmac('sha256', $body, $this->secretKey, false);
 		return base64_encode($hash);	
	}

	// public function checkStatus()
	// {
	// 	$xmlData = $this->makeRequest([
	// 		'serviceId' => $this->serviceId,
	// 		'orderId'   => $this->orderId,
	// 		// 'amount'    => $this->amount,
	// 		// 'currency'  => $this->currency, 
	// 	], 'status');
	// 	return $xmlData;
	// 	if (@$xmlData->tranStatus) 
	// 	{
	// 		return $xmlData->tranStatus;
	// 	} 
	// }
}