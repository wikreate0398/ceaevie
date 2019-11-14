<?php
 

Route::post('webhook/visa', 'Pay\PaymentWebhookController@visa');  
 
Route::any('visa-callback', 'Pay\PaymentController@visaCallback')->name('visa_callback'); 

Route::get('/', 'Pay\PaymentController@indicateСode')->middleware(['lang', 'web']); 
Route::group(['prefix' => '{lang}', 'namespace' => 'Pay', 'middleware' => ['lang', 'web']], function() { 
	Route::get('/', 'PaymentController@indicateСode')->name('show_payment_code');
	Route::post('set-code', 'PaymentController@setСode')->name('set_code');
	Route::get('make-payment/{code}', 'PaymentController@payment')->name('payment');
	Route::post('handle-payment', 'PaymentController@handlePayment')->name('make_payment');
	Route::get('visa-webpay/{orderRand}', 'PaymentController@visaWebPay')->name('visa_webpay'); 
}); 