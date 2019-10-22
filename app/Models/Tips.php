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
        'withdraw',
        'status',
        'id_transaction',
        'rrn',
        'open'
    ];  

    protected $casts = [
        'withdraw' => 'integer',
        'open'     => 'integer'
    ];

    public function scopeConfirmed($query, $lasDays = false)
    {
        if (!empty($lasDays)) 
        {
            $week = \Carbon\Carbon::today()->subDays(7);
            $query->where('created_at', '>=', $week);
        }
        return $query->where('status', 'CHARGED');
    }

    public function scopeForWithdraw($query)
    {
        return $query->where('id_user', \Auth::user()->id)
                     ->where('withdraw', 0)
                     ->confirmed();
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'id_user');
    } 
}
