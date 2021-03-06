<?php 

namespace App\Utils\Payments\PaymentCenterService;

class VisaPayment extends PaymentService implements PaymentInterface
{
	protected $serviceHostname = 'https://secured.payment.center/v2/';

	protected $payoutBranch = 'common';

	protected $serviceId = [
		'dev'        => 796,
		'production' => 805,
		'payout'     => 859
	];

	protected $secretKey = [
		'dev'        => 'ZsuvzNBPrEzzNaxr',
		'production' => 'Ef9tqLRSZ3PsNAsb',
		'payout'     => '4Dnh2qvq8QVGxfj8'
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

	public function setPayoutBranch($branch = 'common')
    {
    	$this->payoutBranch = $branch;
    	return $this;
    }

	public function getToken()
	{      
		$xmlData = $this->makeRequest([
			'serviceId'   => ($this->payoutBranch == 'special') ? $this->serviceId['payout'] : $this->serviceId[$this->mode],
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

		return $this->serviceHostname . 'webpay/?token=' . $xmlData->token;
	}

	public function pay()
	{    
		$xmlData = $this->makeRequest([
			'serviceId'   => $this->serviceId[$this->mode],
			'cardNumber'  => $this->cardCredentials['number'],
			'expMonth'    => $this->cardCredentials['month'],
			'expYear'     => $this->cardCredentials['year'],
			'cardHolder'  => $this->cardCredentials['name'], 
			'orderId'     => $this->orderId,
			'amount'      => $this->amount,
			'currency'    => $this->currency, 
			'description' => $this->description,
			'customFields' => 'IP='. \Request::getClientIp(true) .';'
		], 'pay');   
 
		$this->log('pay', $xmlData);

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
			'customFields' => 'IP='. \Request::getClientIp(true) .';',
			'extra'       => json_encode([
				'success_url' => route('visa_callback', ['type' => 'success']),
				'decline_url' =>  route('visa_callback', ['type' => 'decline']),
				'cancel_url'  =>  route('visa_callback', ['type' => 'cancel']),
				'account_id'  =>  route('visa_callback', ['type' => 'account_id'])
			])
		];
	}

	public function payout()
	{
		$xmlData = $this->makeRequest([
			'serviceId'   => ($this->payoutBranch == 'special') ? $this->serviceId['payout'] : $this->serviceId[$this->mode],
			'cardNumber'  => $this->cardCredentials['number'],
			'expMonth'    => $this->cardCredentials['month'],
			'expYear'     => $this->cardCredentials['year'],
			'cardHolder'  => $this->cardCredentials['name'], 
			'orderId'     => $this->orderId,
			'amount'      => $this->amount,
			'currency'    => $this->currency, 
			'description' => $this->description,
			'customFields' => 'IP='. \Request::getClientIp(true) .';'
		], 'payout');   
 
		$this->log('payout', $xmlData);   
	 	return $xmlData;
	}

	private function log($action, $xmlData)
	{  
		\Log::channel('payment')->info([
			'action'       => $action,
			'order_rand'   => $this->orderId,
			'payment_mode' => $this->mode,
			'flag'         => @$xmlData->success,
			'err_code'     => @$xmlData->errCode,
			'err_message'  => @$xmlData->errMessage,
			'log'          => json_encode($xmlData)
		]);
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