<?php

Route::get('page-404', function(){ 
	return response()->view('errors.404', [], 404);
})->name('404'); 

Route::get('/', 'Pay\PaymentController@indicateСode')->middleware(['lang', 'web']); 
Route::group(['prefix' => '{lang}', 'namespace' => 'Pay', 'middleware' => ['lang', 'web']], function() {
	Route::get('/', 'PaymentController@indicateСode');
	Route::post('set-code', 'PaymentController@setСode')->name('set_code');
	Route::get('make-payment/{code}', 'PaymentController@payment')->name('payment');
	Route::post('make-payment', 'PaymentController@makePayment')->name('make_payment');
});

 