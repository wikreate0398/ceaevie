<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'lastname', 
        'payment_signature', 
        'phone', 
        'email', 
        'password', 
        'confirm', 
        'confirm_hash', 
        'active',  
        'image', 
        'user_agent', 
        'last_entry', 
        'fee', 
        'ballance', 
        'verification_status', 
        'rand', 
        'institution_name', 
        'type', 
        'work_type', 
        'self_percent'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
      'password', 'remember_token',
    ];

    protected $casts = [
        'confirm'    => 'integer',
        'active'     => 'integer',
        'fee'        => 'float',    
        'ballance'   => 'float',      
        'last_entry' => 'datetime',
        'self_percent' => 'float'
    ];
  
    public function scopeFilter($query)
    {
        if(request()->search)
        {
            $searchQuery = request()->search;
            $query->where('name', 'like', '%'.$searchQuery.'%')
                  ->orWhere('email', 'like', '%'.$searchQuery.'%')
                  ->orWhere('rand', 'like', '%'.$searchQuery.'%');
        } 

        if (request()->sort) 
        {
          $sort = request()->sort;
          if ($sort == 'no-active') 
          {
            $query->where('active', '!=', '1');
          } 
          elseif ($sort == 'active') 
          {
            $query->where('active', '1');
          }
          elseif ($sort == 'verification-pending') 
          {
            $query->where('verification_status', 'pending');
          }
          elseif ($sort == 'identified') 
          {
            $query->where('verification_status', 'confirm');
          }
          elseif ($sort == 'no-identified') 
          {
            $query->where('verification_status', 'not_passed');
          } 
          elseif ($sort == 'decline-identification') 
          {
            $query->where('verification_status', 'decline');
          }  
        }

        return $query;
    } 

    public function tips()
    {
        return $this->hasMany('App\Models\Tips', 'id_user', 'id');
    } 

    public function withdraw()
    {
      return $this->hasMany('App\Models\WithdrawTips', 'id_user', 'id')->where('moderation', 0);
    }  

    public function withdraw_requests()
    {
      return $this->hasMany('App\Models\WithdrawTips', 'id_user', 'id')->where('moderation', 1);
    }  

    public function cards()
    {
      return $this->hasMany('App\Models\BankCards', 'id_user', 'id');
    }  

    public function scopeWithTrashedCard($query)
    {
      return $query->with(['cards' => function($query){
        return $query->withTrashed();
      }]);
    }

    public function verificationStatusData()
    {
      return $this->hasOne('App\Models\IdentificationSatuses', 'define', 'verification_status');
    } 

    public function typeData()
    {
      return $this->hasOne('App\Models\UserType', 'type', 'type');
    }  

    public function identificationFiles()
    {
      return $this->hasMany('App\Models\IdentificationFiles', 'id_user', 'id')->orderBy('id', 'desc');
    }  

    public function scopeRegistered($query, $time = false)
    {
      if ($time == 'week') 
      { 
        $query->where('created_at', '>=', \Carbon\Carbon::today()->subDays(7));
      }
      elseif ($time == 'today') 
      {
        $query->where('created_at', '>=', \Carbon\Carbon::today());
      }

      return $query->where('confirm', '1');
    }

    public function locationUsers()
    {
      return $this->belongsToMany('App\Models\User', 'location_users', 'id_location', 'id');
    }
}
