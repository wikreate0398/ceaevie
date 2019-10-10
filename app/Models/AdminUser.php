<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AdminUser extends Authenticatable
{
    use Notifiable; 
    
	protected $guard = 'admin';

    protected $table = 'admin_users';

    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'email', 
        'password' 
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function admin_logs_report()
    {
        return $this->hasMany('App\Models\LogsReport', 'id_user', 'id')
                    ->where('guard', '1')
                    ->orderBy('id', 'desc');
    }


}
