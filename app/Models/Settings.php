<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
	public $timestamps = false;

	protected $table = 'settings';

	protected $fillable = [
        'type',
        'var',
        'name',
        'value'
    ];
}
