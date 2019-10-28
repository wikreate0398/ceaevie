<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentLogResponse extends Model
{
	public $timestamps = true;

	protected $table = 'payment_log_response';

	protected $fillable = [
        'action',
        'order_rand',
        'flag',
        'err_code',
        'err_message', 
        'log',
        'payment_mode'
    ]; 
}
