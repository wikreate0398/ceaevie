<?php 

namespace App\Utils\Payments;

class PaymentService
{ 
	protected $testMode  = false;

	protected $mode = 'production';
	
	function __construct()
	{ 
		if (setting('payment_mode') == 'on') 
		{
			$this->testMode = true;
			$this->mode     = 'dev';
		}
	}
}