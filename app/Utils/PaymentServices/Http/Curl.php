<?php 

namespace App\Utils\PaymentServices\Http; 

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class Curl
{ 
	protected $client;

	private $apiKey = 'eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICIzaG14MU95bjJWc2psTEZRNTNLYk5Nek12RGZUcjdzcGxYelJDeDRERVR3In0.eyJqdGkiOiIwNTc1MmMwZi02MjYxLTQwNzktODIzNi02YzQ0ZmQ1ZTFjNjMiLCJleHAiOjE2MDY4MTEwMTMsIm5iZiI6MCwiaWF0IjoxNTc1Mjc1MDE0LCJpc3MiOiJodHRwczovL2F1dGgucmJrLm1vbmV5L2F1dGgvcmVhbG1zL2V4dGVybmFsIiwiYXVkIjoia29mZmluZyIsInN1YiI6IjI5OTc3MGViLWFjZDEtNDg4Yi04NzJiLWU4MWY1NzlkNzA0ZiIsInR5cCI6IkJlYXJlciIsImF6cCI6ImtvZmZpbmciLCJub25jZSI6IjNhNzUwNDYyLWQwM2ItNGQ0OC1hYzEyLTI1Y2IyZGNjZTIxMyIsImF1dGhfdGltZSI6MTU3NTI3NTAxMywic2Vzc2lvbl9zdGF0ZSI6ImM1NTNkZjczLTU1NTAtNDg4NC1hYzkwLWFkY2ZiNjI4Y2QxZSIsImFjciI6IjEiLCJhbGxvd2VkLW9yaWdpbnMiOlsiaHR0cDovL2xvY2FsaG9zdDo4MDAwIiwiaHR0cHM6Ly9kYXNoYm9hcmQucmJrLm1vbmV5Il0sInJlc291cmNlX2FjY2VzcyI6eyJjb21tb24tYXBpIjp7InJvbGVzIjpbImludm9pY2VzLioucGF5bWVudHM6d3JpdGUiLCJjdXN0b21lcnMuKi5iaW5kaW5nczp3cml0ZSIsInBhcnR5OnJlYWQiLCJpbnZvaWNlcy4qLnBheW1lbnRzOnJlYWQiLCJjdXN0b21lcnM6d3JpdGUiLCJwYXJ0eTp3cml0ZSIsImN1c3RvbWVycy4qLmJpbmRpbmdzOnJlYWQiLCJjdXN0b21lcnM6cmVhZCIsImludm9pY2VzOndyaXRlIiwiaW52b2ljZXM6cmVhZCJdfSwidXJsLXNob3J0ZW5lciI6eyJyb2xlcyI6WyJzaG9ydGVuZWQtdXJsczp3cml0ZSIsInNob3J0ZW5lZC11cmxzOnJlYWQiXX0sImFjY291bnQiOnsicm9sZXMiOlsibWFuYWdlLWFjY291bnQiLCJtYW5hZ2UtYWNjb3VudC1saW5rcyIsInZpZXctcHJvZmlsZSJdfX0sInNjb3BlIjoib3BlbmlkIiwiZW1haWxfdmVyaWZpZWQiOnRydWUsIm5hbWUiOiLQmtCw0YDQsNC_0LXRgiDQn9Cw0YjQsNGP0L0iLCJwcmVmZXJyZWRfdXNlcm5hbWUiOiJjaGFldmllb25saW5lQGdtYWlsLmNvbSIsImdpdmVuX25hbWUiOiLQmtCw0YDQsNC_0LXRgiIsImZhbWlseV9uYW1lIjoi0J_QsNGI0LDRj9C9IiwiZW1haWwiOiJjaGFldmllb25saW5lQGdtYWlsLmNvbSJ9.l-wqicF7Z_dofJbY7J55tz14chnJ9o_iyy7yIQY64P-EZOE1K41i-nPAS1WyNzwDAhkKH8uap4fLUsQK0wKeVhLsDgsFtfysheyq6_AWxRuPuJVy_NY3wo5hkMhWxzd19AHmUICDxxR8ahxNBTOuETHrW6N5gFChqmRf081HINx1GuLPDtBO4Ho-xeUVlsR--y1Ygu5H7KWmiNLeiFxlioUL7ana7tVymevl9g1BoU02e9BwOZbZ6H4XAGsxdG2fPBVSQF6vMs-5epqONRw9DNm_6VPNCY6J8o1hZNV6fApsZwrzWq9nrFOO3QcR1bkyUi4C3tbi_Fu4Vi3EotPoDP_ODdAFyOOOcou3pnc2urTVgfCriZWnDh9bqIB7opohF7K113UAonngDDGD2PX4DG2M3Sf-kqE6HYDkFUUpRW2o4_BFGMxOAfVm4yV4qUHFbuXyLMWbFQlD_I1BZn3o5SNeNQtDmzauCUVld1eOW2aJOGlypsSoA1WQW5Lk0brJDHNxemK0E2xpeuoXP_LAIBANXoekf0nSrr3LzMRub91TcKwL29oZsM8clPS5blbDm6VsAx3hWoKx42gMBc2_fkmmc12g-sxnNOGiQQ-9s8eFdTUtrO-O5xs9GRtQMqZWNIJcuRvRsGs312H9zG1UKBVjsOHv_xTG_w-ETt3jnqo';

	public function __construct() 
	{
		$this->client = new Client();
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
		  	CURLOPT_HTTPHEADER => $this->prepare_headers(),
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