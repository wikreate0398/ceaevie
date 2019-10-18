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
		$token = $this->makeTokenRequest([
			'serviceId'   => $this->serviceId,
			'orderId'     => $this->orderId,
			'amount'      => $this->amount,
			'currency'    => $this->currency, 
			'description' => $this->description
		]);
		
		return $this->serviceHostname . 'webblock/?token=' . $token; 
	}

	private function makeTokenRequest($arrayRequest)
	{
		$body      = http_build_query($arrayRequest);
		$signature = $this->getSignature($body, $this->secretKey);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->serviceHostname . 'token');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $body);  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, ['signature:'.$signature]);

		$server_output = curl_exec ($ch);
		curl_close ($ch);

		$xml = simplexml_load_string($server_output); 
		if(@$xml->success != 'true' or !@$xml->token)
		{
			throw new \Exception("Ошибка оплаты, попробуйте позже"); 
		}

		return $xml->token;
	}

	private function getSignature($body)
	{
		$hash = hash_hmac('sha256', $body, $this->secretKey, false);
 		return base64_encode($hash);	
	}
}