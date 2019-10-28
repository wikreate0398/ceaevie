<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WithdrawTips extends Model
{ 
    use SoftDeletes;

    public $timestamps = true;

    protected $table = 'withdraw_tips';

    protected $fillable = [
        'id_user',
        'id_card', 
        'rand', 
        'amount',  
        'status', 
        'id_transaction',
        'pan_ref_token',
        'open',
        'open_admin'
    ];  

    protected $casts = [ 
        'open'         => 'integer',
        'open_admin'   => 'integer', 
        'amount'       => 'float'
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'id_user')->withTrashed();
    } 

    public function card()
    {
        return $this->hasOne('App\Models\BankCards', 'id', 'id_card')->withTrashed();
    }  

    public function statusData()
    {
        return $this->hasOne('App\Models\WithdrawStatus', 'define', 'status');
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

        if (request()->client) {
            $query->where('id_user', request()->client);
        } 
    }
}
