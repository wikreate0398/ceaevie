<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    public $timestamps = true;

    protected $table = 'transactions';

    protected $fillable = [
        'id_user',
        'id_order',
        'id_withdraw', 
        'type',
        'price', 
        'ballance' 
    ]; 

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'id_user');
    } 
}