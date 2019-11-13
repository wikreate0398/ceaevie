<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; 

class ProfileMenuGuard extends Model
{ 
	public $timestamps = false;

	protected $table = 'profile_menu_guard';

	protected $fillable = [
        'id_menu', 
        'type', 
    ]; 
}
