<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; 

class PaymentService extends Model
{   
	public $timestamps = false;

	protected $table = 'payment_services';

	protected $fillable = [
        'define', 
        'name',  
    ]; 
}
