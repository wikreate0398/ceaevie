<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\SoftDeletes;

class EnrollmentPercents extends Model
{   
	use SoftDeletes;

	public $timestamps = false;

	protected $table = 'enrollment_percents';

	protected $fillable = [
        'name', 
        'percent' 
    ]; 
}
