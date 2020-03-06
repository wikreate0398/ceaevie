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
        'id_location',
        'id_payment',
        'payment_service',
        'id_qrcode',
        'rand',
        'total_amount',
        'amount',
        'fee', 
        'location_fee',
        'location_amount',
        'location_work_type',
        'status',
        'id_transaction',
        'rrn',
        'open',
        'open_admin',
        'rating',
        'review'
    ];  

    protected $casts = [
        'withdraw'     => 'integer',
        'open'         => 'integer',
        'open_admin'   => 'integer',
        'total_amount' => 'float',
        'amount'       => 'float',
        'fee'          => 'float',
        'location_fee' => 'float',
        'location_amount' => 'float',
        'rating'       => 'integer'
    ];
 

    public function scopeConfirmed($query, $lastDays = false)
    {
        if (!empty($lastDays)) 
        {
            $week = \Carbon\Carbon::today()->subDays(7);
            $query->where('created_at', '>=', $week);
        }
        return $query->where('status', 'CHARGED');
    }  

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'id_user')->withTrashed();
    } 

    public function location()
    {
        return $this->hasOne('App\Models\User', 'id', 'id_location');
    } 

    public function qr_code()
    {
        return $this->hasOne('App\Models\QrCode', 'id', 'id_qrcode')->withTrashed();
    } 

    public function payment()
    {
        return $this->hasOne('App\Models\PaymentType', 'id', 'id_payment');
    }

    public function payment_service_data()
    {
        return $this->hasOne('App\Models\PaymentService', 'define', 'payment_service');
    }

    public function percents()
    {
        return $this->hasMany('App\Models\TipPercents', 'id_tip', 'id')->where('percent', '>', '0');
    }

    public function scopeWithPartnerPercent($query)
    {
        return $query->with(['percents' => function($query){
            return $query->whereHas('enrollment_percent', function($query){
                return $query->where('type', 'agent');
            });
        }])->whereHas('percents', function ($query){
            return $query->whereHas('enrollment_percent', function($query){
                return $query->where('type', 'agent');
            });
        });
    }
 
    public function scopeFilter($query)
    { 
        if (request()->from) { 
            $query->where('tips.created_at', '>=', \Carbon\Carbon::parse(new \DateTime(request()->from . ' 00:00:00'))->format('Y-m-d H:i:s'));
        }

        if (request()->to) {
            $query->where('tips.created_at', '<=', \Carbon\Carbon::parse(request()->to . ' 23:59:59')->format('Y-m-d H:i:s'));
        }

        switch (request()->period) {
            case 'week': 
                $query->where('tips.created_at', '>=', \Carbon\Carbon::today()->subDays(7));
                break;
 
            case 'month': 
                $query->where('tips.created_at', '>=', \Carbon\Carbon::now()->subDays(30)->toDateTimeString());
                break;
            
            default: 
                break;
        }

        if (request()->rand) {
            $query->where('tips.rand', request()->rand);
        } 

        if (request()->client) {
            $query->where('tips.id_user', request()->client);
        } 
    }
}
