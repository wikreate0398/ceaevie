<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\OrderingTrait;
use App\Models\Traits\PermisionTrait;

class BackgroundColor extends Model
{
	use OrderingTrait, PermisionTrait;
	
	public $timestamps = false;

	protected $table = 'background_color';

	protected $fillable = [ 
        'color', 
        'logo',
        'font_color',
        'code_color'
    ]; 
}
