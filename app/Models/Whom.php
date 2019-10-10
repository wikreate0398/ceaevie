<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\OrderingTrait;
use App\Models\Traits\PermisionTrait;

class Whom extends Model
{
	use OrderingTrait, PermisionTrait;

	public $timestamps = false;

	protected $table = 'whom';

	protected $fillable = [
        'name_ru', 
        'name_en', 
        'image'
    ]; 
}
