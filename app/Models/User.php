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
        'name', 'lastname', 'payment_signature', 'phone', 'email', 'password', 'confirm', 'confirm_hash', 'active',  'image', 'user_agent', 'last_entry', 'fee', 'ballance'
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
        'last_entry' => 'datetime'
    ];
  
    public function scopeFilter($query)
    {
        if(request()->q)
        {
            $searchQuery = request()->q;
            $query->where('name', 'like', '%'.$searchQuery.'%')
                  ->orWhere('email', 'like', '%'.$searchQuery.'%');
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
}
