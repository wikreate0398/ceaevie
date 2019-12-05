<?php

namespace App\Http\Controllers\Pay;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\NewTips;
use App\Utils\Payments\VisaPayment;
use App\Utils\Ballance; 
use App\Models\PaymentLogResponse; 
use App\Models\Tips; 
use App\Models\WithdrawTips; 
use App\Utils\Payments\WithdrawalService; 

use App\Utils\PaymentServices\Http\Curl; 

class PaymentWebhook2Controller extends Controller
{
	protected $publicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAhzFOW9Nfs/DLQcT5NX9x
2yoCweh1nmveslBCVWEYkeBy3jX84YqosFSRgUhP55u0wwvG+G+xojw2fmuibuyV
+RIU+puHAADc9V1Bh/96gtJp9BLrqqND3+9Y5d+kVcZszqBK+Ku/QUs+Mr79a9HB
4xvaLPjzdGtcQ7t7B0D5lzKDJ1zTOcmOVPKlJ+dzhMxsgAj1j7iBlHDnQ+E41ODP
YotF3/QDGsYwG6YMz1N+ey9ngnwPZMx7EwFGDv50i2jrPF4AgPmMRDZ7edj/ixCA
z80N89ltVn5YRvBOj4/AcigY3nKV5D12XwMxqst6Pb6uHaIq0S9GGGlFipLVOHyw
iwIDAQAB';

	private $request; 

	public function __construct()
	{
		$this->http = new Curl();  
	}

	public function handle()
	{ 
		$content = $this->getBodyData();
		$this->request = json_decode($content, TRUE);  
		$this->log($this->request, @$this->request['event']); 

		try {
			$signatureFromHeader = $this->get_signature_from_header($_SERVER['HTTP_CONTENT_SIGNATURE']); 
		} catch (\Exception $e) {
			
		}

		$decodedSignature = $this->urlsafe_base64decode($signatureFromHeader); 
		    
		if(!$this->verify_signature($content, $decodedSignature, $this->publicKey)) {
		    http_response_code(400);
		    echo json_encode(['message' => 'Webhook notification signature mismatch']);
		    exit();
		}
 
		if (!empty($this->request['event'])) {
  
			switch ($this->request['event']) {
				case 'PaymentProcessed ':
					$this->paymentProcessed();
					break;

				case 'PaymentCaptured ':
					$this->paymentCaptured();
					break;

				case 'PaymentCancelled ':
					$this->paymentCancelled();
					break; 

				case 'PaymentFailed ':
					$this->paymentFailed();
					break;  
				
				default: 
					break;
			}
		}
	}

	private function paymentProcessed()
	{
		$tip         = Tips::with('location')->where('id_invoice', $this->request['invoice']['id'])->first(); 
		$tip->status = $this->request['payment']['status'];
		$tip->save();

		$this->capturePayment();
	}

	private function capturePayment()
	{
		$response = $this->http->post('https://api.rbk.money/v2/processing/invoices/'.$this->request['invoice']['id'].'/payments/'.$this->request['payment']['id'].'/capture', [
			'reason'   => 'Express capture payment',
			'amount'   => $this->request['invoice']['amount'],
			'currency' => 'RUB'
		]);

		\Log::channel('payment')->info('PaymentWebhook2Controller -> capturePayment()');
		\Log::channel('payment')->info($response);

		$tip         = Tips::with('location')->where('id_invoice', $this->request['invoice']['id'])->first(); 
		$tip->status = 'CHARGED';
		$tip->save(); 

		$this->chargedTip($tip);
	}	

	private function paymentCaptured()
	{
		 \Log::channel('payment')->info('PaymentWebhook2Controller -> paymentCaptured()');
		\Log::channel('payment')->info($this->request);
	}

	private function chargedTip($tip)
	{ 
		$amount = $tip->amount;
		if (!empty($tip->location)) 
		{
			if ($tip->location_work_type == 'percent') 
			{  
				// зачисляем деньги пользователю 
 				$this->enroll($tip->user, $tip->id, $amount);

	            // зачисляем деньги заведению  
	        	$this->enroll($tip->location, $tip->id, $tip->location_amount);
			}
			else
			{
				// зачисляем деньги заведению
	            $this->enroll($tip->location, $tip->id, $amount);         
			}
		}
		else
		{ 
	        $this->enroll($tip->user, $tip->id, $amount); 
		}
	}

	private function enroll($user, $tipId, $amount)
	{
		$userBallance = new Ballance;
		$userBallance->setUser($user)
                     ->setOrderId($tipId)
                     ->setPrice($amount)
                     ->replenish();

        $user->notify(new NewTips($amount));
	}

	private function paymentCancelled()
	{
		$tip         = Tips::with('location')->where('id_invoice', $this->request['invoice']['id'])->first(); 
		$tip->status = $this->request['payment']['status'];
		$tip->save();
	}

	private function paymentFailed()
	{
		$tip         = Tips::with('location')->where('id_invoice', $this->request['invoice']['id'])->first(); 
		$tip->status = $this->request['payment']['status'];
		$tip->save();
	}

	private function getBodyData()
	{
		return file_get_contents('php://input');
	}

	private function get_signature_from_header($contentSignature) {
        $signature = preg_replace("/alg=(\S+);\sdigest=/", '', $contentSignature);

        if (empty($signature)) {
            throw new Exception('Signature is missing');
        }

        return $signature;
	}

	private function urlsafe_base64decode($string) {
	    return base64_decode(strtr($string, '-_,', '+/='));
	}

	private function verify_signature($data, $signature, $publicKey) {
	    if (empty($data) || empty($signature) || empty($publicKey)) {
	        return FALSE;
	    }

	    $publicKeyId = openssl_get_publickey($publicKey);
	    if (empty($publicKeyId)) {
	        return FALSE;
	    }

	    $verify = openssl_verify($data, $signature, $publicKeyId, OPENSSL_ALGO_SHA256);

	    return ($verify == 1);
	}

	private function log($data, $action = null, $rand = null)
	{   
		\Log::channel('payment')->info([
			// 'order_rand'   => !empty($data->Order_Id) ? $data->Order_Id : '',
			// 'payment_mode' => !empty($data->IsTest) ? 'dev' : 'production',
			'flag'         => !empty($data['payment']['status']) ? $data['payment']['status'] : '', 
			'log'          => json_encode($data),
			'action'       => $action ?: '',
			'order_rand'   => $rand ?: ''
		]);
	}
}
