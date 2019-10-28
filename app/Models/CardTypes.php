<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; 

class CardTypes extends Model
{   
	public $timestamps = false;

	protected $table = 'card_types';

	protected $fillable = [
        'type', 
        'image' 
    ]; 
}
