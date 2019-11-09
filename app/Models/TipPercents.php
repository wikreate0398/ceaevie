<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; 

class TipPercents extends Model
{   
	public $timestamps = false;

	protected $table = 'tip_percents';

	protected $fillable = [
        'id_tip', 
        'id_percent',
        'percent'
    ]; 
}