<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tips extends Model
{ 
    use SoftDeletes;

    public $timestamps = true;

    protected $table = 'tips';

    protected $fillable = [
        'id_user',
        'id_payment',
        'rand',
        'amount',
        'confirm'
    ]; 
 
    protected $casts = [
        'confirm' => 'integer'  
    ]; 
}
