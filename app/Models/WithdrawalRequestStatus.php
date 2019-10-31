<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; 

class WithdrawalRequestStatus extends Model
{   
    public $timestamps = false;

    protected $table = 'withdrawal_request_status';

    protected $fillable = [
        'define',
        'name_ru', 
        'name_en'
    ];
}
