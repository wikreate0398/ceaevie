<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; 

class WithdrawStatus extends Model
{   
    public $timestamps = false;

    protected $table = 'withdraw_status';

    protected $fillable = [
        'define',
        'name_ru', 
        'name_en'
    ];
}
