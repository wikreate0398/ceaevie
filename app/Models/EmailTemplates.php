<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplates extends Model
{
	public $timestamps = false;

	protected $table = 'email_templates';

	protected $fillable = [
        'name',
        'var',
        'const',
        'theme_ru', 
        'theme_en',
        'message_ru', 
        'message_en'
    ];
}
