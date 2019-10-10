<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\OrderingTrait;
use App\Models\Traits\PermisionTrait;

class PaymentType extends Model
{ 
	use OrderingTrait, PermisionTrait;

	public $timestamps = false;

	protected $table = 'payment_types';

	protected $fillable = [
        'name_ru', 
        'name_en', 
        'image'
    ]; 
}
