<?php

namespace App\Models\Constants;

use Illuminate\Database\Eloquent\Model;

class ConstantsCategory extends Model
{
	public $timestamps = false;

	protected $table = 'constants_category';

	protected $fillable = [
        'name'
    ];
}
