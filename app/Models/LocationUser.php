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
        'status',
        'card_signature'
    ]; 

    public function user()
    {
      return $this->hasOne('App\Models\User', 'id', 'id_user');
    }  

    public function location()
    {
      return $this->hasOne('App\Models\User', 'id', 'id_location');
    }  
}
