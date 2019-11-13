<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; 

class UserType extends Model
{   
	public $timestamps = false;

	protected $table = 'user_types';

	protected $fillable = [
        'type', 
        'name_ru',
        'name_en'
    ]; 
}
