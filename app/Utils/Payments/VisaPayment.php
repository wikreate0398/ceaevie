<?php 

namespace App\Utils\Payments;

class VisaPayment extends PaymentService implements PaymentInterface
{
	private $serviceHostname = 'https://secured.payment.center/v2/';

	private $serviceId = [
		'dev'        => 796,
		'production' => 805
	];

	private $secretKey = [
		'dev'        => 'ZsuvzNBPrEzzNaxr',
		'production' => 'Ef9tqLRSZ3PsNAsb'
	]; 
	
	private $currency = 'RUB'; 
  
	function __construct() 
	{
		parent::__construct();
	}
 
	public function setTranId($tranId)
	{
		$this->tranId = $tranId;
		return $this;
	}  

	public function getServiceHostname()
	{
		return $this->serviceHostname;
	}

	public function getToken()
	{    
		$xmlData = $this->makeRequest([
			'serviceId'   => $this->serviceId[$this->mode],
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

	public function pay()
	{   
		$exp     = explode('/', prepareExpiryDate($this->cardCredentials['expiry_date'])); 
		$xmlData = $this->makeRequest([
			'serviceId'   => $this->serviceId[$this->mode],
			'cardNumber'  => $this->cardCredentials['number'],
			'expMonth'    => @$exp[0],
			'expYear'     => @$exp[1],
			'cardHolder'  => $this->cardCredentials['name'],
			'cvc'         => $this->cardCredentials['cvc'],
			'orderId'     => $this->orderId,
			'amount'      => $this->amount,
			'currency'    => $this->currency, 
			'description' => $this->description,
			'customFields' => 'IP='. base64_encode(\Request::getClientIp(true)) .';'
		], 'pay');   
 
		$this->log($xmlData);

		if (in_array($xmlData->success, ['false', 'fail'])) 
	 	{
	 		throw new \Exception("Ошибка оплаты, попробуйте позже"); 
	 	}

	 	return $xmlData;
	}

	public function webPay()
	{     
	 	return [
			'serviceId'   => $this->serviceId[$this->mode], 
			'orderId'     => $this->orderId,
			'amount'      => $this->amount,
			'currency'    => $this->currency, 
			'description' => $this->description,
			'customFields' => 'IP='. base64_encode(\Request::getClientIp(true)) .';',
			'extra'       => json_encode([
				'success_url' => route('visa_callback', ['type' => 'success']),
				'decline_url' =>  route('visa_callback', ['type' => 'decline']),
				'cancel_url'  =>  route('visa_callback', ['type' => 'cancel']),
				'account_id'  =>  route('visa_callback', ['type' => 'account_id'])
			])
		];
	}

	private function log($xmlData)
	{
		$this->logModel->create([
			'order_rand'   => $this->orderId,
			'payment_mode' => $this->mode,
			'flag'         => @$xmlData->success,
			'err_code'     => @$xmlData->errCode,
			'err_message'  => @$xmlData->errMessage,
			'log'          => json_encode($xmlData)
		]);
	}

	private function makeRequest($arrayRequest, $requestType)
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

	private function getSignature($body)
	{
		$hash = hash_hmac('sha256', $body, $this->secretKey[$this->mode], false);
 		return base64_encode($hash);	
	}

	public function makeCharge()
	{   
		$xmlData = $this->makeRequest([
			'serviceId' => $this->serviceId[$this->mode],
			'tranId'    => $this->tranId,
			'amount'    => $this->amount,
			'currency'  => $this->currency,   
		], 'charge');

		return $xmlData; 
	}
}