<?php

namespace App\Models\Constants;

use Illuminate\Database\Eloquent\Model;

class ConstantsValue extends Model
{
	public $timestamps = false;

	protected $table = 'constants_value';

	protected $fillable = [
        'lang',
        'id_const',
        'value'
    ];

	protected $casts = [
        'id_const' => 'integer',
        'value'    => 'content'
    ];

    public function constant()
    {
        return $this->hasOne('App\Models\Constants\Constants', 'id', 'id_const');
    }
}
