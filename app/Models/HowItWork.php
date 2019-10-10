<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\OrderingTrait;
use App\Models\Traits\PermisionTrait;

class HowItWork extends Model
{
	use OrderingTrait, PermisionTrait;
	
	public $timestamps = false;

	protected $table = 'how_it_works';

	protected $fillable = [
        'name_ru', 
        'name_en',
        'description_ru', 
        'description_en', 
        'image'
    ]; 
}
