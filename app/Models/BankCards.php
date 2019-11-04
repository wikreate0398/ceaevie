<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankCards extends Model
{
    use SoftDeletes;

    public $timestamps = true;

    protected $table = 'bank_cards';

    protected $fillable = [
        'id_user',
        'type',
        'name',
        'number',
        'hide_number',
        'month',
        'year'
    ];  

    protected $casts = []; 

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'id_user');
    }  

    public function card_type()
    {
        return $this->hasOne('App\Models\CardTypes', 'type', 'type');
    }   
}
