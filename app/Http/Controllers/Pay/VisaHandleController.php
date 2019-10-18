<?php

namespace App\Http\Controllers\Pay;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Utils\Payments\PaymentLogResponse; 

class VisaHandleController extends Controller
{
	public function success(Request $request)
	{
		PaymentLogResponse::create([
			'payment_type' => 'visa',
			'type'         => 'success',
			'log'          => json_encode($request->all())
		]);
	}

	public function fail(Request $request)
	{
		PaymentLogResponse::create([
			'payment_type' => 'visa',
			'type'         => 'fail',
			'log'          => json_encode($request->all())
		]);
	}

	public function notify(Request $request)
	{
		PaymentLogResponse::create([
			'payment_type' => 'visa',
			'type'         => 'notify',
			'log'          => json_encode($request->all())
		]);
	}

	public function back(Request $request)
	{
		PaymentLogResponse::create([
			'payment_type' => 'visa',
			'type'         => 'back',
			'log'          => json_encode($request->all())
		]);
	}
}
