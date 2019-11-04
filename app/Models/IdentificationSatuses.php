<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; 

class IdentificationSatuses extends Model
{   
    public $timestamps = false;

    protected $table = 'identification_statuses';

    protected $fillable = [
        'define',
        'name_ru', 
        'name_en'
    ];
}
