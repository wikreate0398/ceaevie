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

    public function scopeFilter($query)
    { 
        if (request()->from) { 
            $query->where('created_at', '>=', \Carbon\Carbon::parse(new \DateTime(request()->from . ' 00:00:00'))->format('Y-m-d H:i:s'));
        }

        if (request()->to) {
            $query->where('created_at', '<=', \Carbon\Carbon::parse(request()->to . ' 23:59:59')->format('Y-m-d H:i:s'));
        }

        switch (request()->period) {
            case 'week': 
                $query->where('created_at', '>=', \Carbon\Carbon::today()->subDays(7));
                break;
 
            case 'month': 
                $query->where('created_at', '>=', \Carbon\Carbon::now()->subDays(30)->toDateTimeString());
                break;
            
            default: 
                break;
        }

        if (request()->rand) {
            $query->where('rand', request()->rand);
        }

    }
}
