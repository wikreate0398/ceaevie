<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    public $timestamps = true;

    protected $table = 'transactions';

    protected $fillable = [
        'id_user',
        'id_sender',
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

    public function scopeForLast($query, $lastDays = false)
    {
        if (!empty($lastDays)) 
        {
            $data = \Carbon\Carbon::today()->subDays($lastDays);
            $query->where('created_at', '>=', $data);
        } 
    } 
}