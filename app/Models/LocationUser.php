<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; 

class LocationUser extends Model
{   
	public $timestamps = true;

	protected $table = 'location_users';

	protected $fillable = [
        'id_location', 
        'id_user',
        'hash',
        'status'
    ]; 

    public function user()
    {
      return $this->hasOne('App\Models\User', 'id', 'id_user');
    }  
}
