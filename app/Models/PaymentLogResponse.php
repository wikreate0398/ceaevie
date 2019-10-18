<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentLogResponse extends Model
{
	public $timestamps = true;

	protected $table = 'payment_log_response';

	protected $fillable = [
        'payment_type', 
        'type',
        'log'
    ];

	public function background()
    {
        return $this->hasOne('App\Models\BackgroundColor', 'id', 'id_background');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'id_user');
    } 
}
