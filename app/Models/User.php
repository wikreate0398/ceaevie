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
        'name', 'lastname', 'payment_signature', 'phone', 'email', 'password', 'confirm', 'confirm_hash', 'active',  'image', 'lang'
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
        'confirm' => 'integer',
        'active'  => 'integer',
        'ballance' => 'float'
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
}
