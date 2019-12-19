<?php 

namespace App\Utils\Payments\RbkService\Http; 

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class Curl
{ 
	protected $client;

	private $apiKey = 'eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICIzaG14MU95bjJWc2psTEZRNTNLYk5Nek12RGZUcjdzcGxYelJDeDRERVR3In0.eyJqdGkiOiJmMmQ5MTM1Zi02Mzk5LTQzNGYtYmRjMy04NjU2ODhmYzNlMTciLCJleHAiOjE2MDgwMjkyNzYsIm5iZiI6MCwiaWF0IjoxNTc2NDkzMjc3LCJpc3MiOiJodHRwczovL2F1dGgucmJrLm1vbmV5L2F1dGgvcmVhbG1zL2V4dGVybmFsIiwiYXVkIjoia29mZmluZyIsInN1YiI6IjI5OTc3MGViLWFjZDEtNDg4Yi04NzJiLWU4MWY1NzlkNzA0ZiIsInR5cCI6IkJlYXJlciIsImF6cCI6ImtvZmZpbmciLCJub25jZSI6IjgyYmRmM2EyLTEwNWEtNDkzYi1iYzVkLTNkNzNkNWVlMzMyYyIsImF1dGhfdGltZSI6MTU3NjQ5MzI3Niwic2Vzc2lvbl9zdGF0ZSI6ImIyMTEzZjY5LWJkNjMtNGJhOC04MzY4LWMzMTRiNTJmYTFkMSIsImFjciI6IjEiLCJhbGxvd2VkLW9yaWdpbnMiOlsiaHR0cDovL2xvY2FsaG9zdDo4MDAwIiwiaHR0cHM6Ly9kYXNoYm9hcmQucmJrLm1vbmV5Il0sInJlc291cmNlX2FjY2VzcyI6eyJjb21tb24tYXBpIjp7InJvbGVzIjpbImludm9pY2VzLioucGF5bWVudHM6d3JpdGUiLCJjdXN0b21lcnMuKi5iaW5kaW5nczp3cml0ZSIsInBhcnR5OnJlYWQiLCJpbnZvaWNlcy4qLnBheW1lbnRzOnJlYWQiLCJjdXN0b21lcnM6d3JpdGUiLCJwYXJ0eTp3cml0ZSIsImN1c3RvbWVycy4qLmJpbmRpbmdzOnJlYWQiLCJjdXN0b21lcnM6cmVhZCIsImludm9pY2VzOndyaXRlIiwiaW52b2ljZXM6cmVhZCJdfSwidXJsLXNob3J0ZW5lciI6eyJyb2xlcyI6WyJzaG9ydGVuZWQtdXJsczp3cml0ZSIsInNob3J0ZW5lZC11cmxzOnJlYWQiXX0sImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoib3BlbmlkIiwiZW1haWxfdmVyaWZpZWQiOnRydWUsIm5hbWUiOiLQmtCw0YDQsNC_0LXRgiDQn9Cw0YjQsNGP0L0iLCJwcmVmZXJyZWRfdXNlcm5hbWUiOiJjaGFldmllb25saW5lQGdtYWlsLmNvbSIsImdpdmVuX25hbWUiOiLQmtCw0YDQsNC_0LXRgiIsImZhbWlseV9uYW1lIjoi0J_QsNGI0LDRj9C9IiwiZW1haWwiOiJjaGFldmllb25saW5lQGdtYWlsLmNvbSJ9.BFqALfyKQOYpCq3r5u1tUJCU_b_1EnkLkA1-Qeq-9x6UdiXSA7_rfK6PwU8lyOcdYWgsFkRU0FaCfMl7jwTqqdunCUN4xIFEp3CJCEfUMdSSs-LGdAz2AN47S1m9NZjiOCZ0SDoKxPAnfwaeSGDJxqiQcv5ez9H8ZlE45hHe1IcaHuRutoqSwM452IxxZxhsOOs7tZocnZIWGFrcXqmAysw9ajBdDkakw-7HbWR06S4kK_Raah49nfEHR3MR4CxiLDoH9V6bTEfS_GagBfWrhVIl0yUQ4E05lTkI5AZs539nEDqRdBRhG9SeMI3rnDwygdaJZ3qpPFCsAGsd2FoxnvfxDPidft7PzctoOkz9mB0cwqvCibfqkGOPWeTaHqEO8dWEhsjmQ3zwqfwOuh575vPGHw9HqKDCkmtGBydC8R6fO3jvKnqd7wi-w9xfqv8tdFJNfMVOZafv0zwWHT_N4h2y3jVRKPenZcZUGqg-ti_tEEmxiAOb2rma7SrcX_ZWT1pmWhIUQ7jmOQURoZyybwAnI3jME_XdhAhvoco6t_3GGLElu2vhZSHbV1d00jE6CD-QZte6S7_Q7hSuXG2cKwS5UUXdySTyuW91J8OGWDHOB5Ehz3bg8cdeGbmP2QnKiTAWLX58cq2hJNSsDsPeQ5muHtx3j8sDagLF1tfG9Yg';

	public function __construct($apiKey = false) 
	{
		$this->client = new Client();
		$this->apiKey = $apiKey ?: $this->apiKey;
	}

	public function post($endpoint, $params)
	{      
		$curl = curl_init(); 
		curl_setopt_array($curl, array(
			CURLOPT_URL => $endpoint,
			CURLOPT_RETURNTRANSFER => true,
		  	CURLOPT_ENCODING => "",
		  	CURLOPT_MAXREDIRS => 10,
		  	CURLOPT_TIMEOUT => 30,
		  	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  	CURLOPT_CUSTOMREQUEST => "POST",
		  	CURLOPT_POSTFIELDS => json_encode($params),
		  	CURLOPT_HTTPHEADER => $this->prepare_headers() 
		));
 
		$response = curl_exec($curl);    
		$err = curl_error($curl);  
		curl_close($curl);  
 
		return json_decode($response, true);
	}  

	public function get($endpoint)
	{      
		$curl = curl_init(); 
		curl_setopt_array($curl, array(
			CURLOPT_URL => $endpoint,
			CURLOPT_RETURNTRANSFER => true,
		  	CURLOPT_ENCODING => "",
		  	CURLOPT_MAXREDIRS => 10,
		  	CURLOPT_TIMEOUT => 30,
		  	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  	CURLOPT_CUSTOMREQUEST => "GET", 
		  	CURLOPT_HTTPHEADER => $this->prepare_headers() 
		));
 
		$response = curl_exec($curl);    
		$err = curl_error($curl);   
		curl_close($curl);  
 
		return json_decode($response, true);
	}  

	private function prepare_headers()
	{
	    $headers = [];
	    $headers[] = 'X-Request-ID: ' . uniqid();
	    $headers[] = 'Authorization: Bearer ' . $this->apiKey;
	    $headers[] = 'Content-type: application/json; charset=utf-8';
	    $headers[] = 'Accept: application/json';
	    return $headers;
	}
}