<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; 

class IdentificationFiles extends Model
{   
    public $timestamps = false;

    protected $table = 'identification_files';

    protected $fillable = [
        'id_user',
        'file' 
    ];
}
