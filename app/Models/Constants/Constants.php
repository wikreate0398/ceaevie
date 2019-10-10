<?php

namespace App\Models\Constants;

use Illuminate\Database\Eloquent\Model;

class Constants extends Model
{
	public $timestamps = false;

	protected $table = 'constants';

	protected $fillable = [
        'name',
        'id_category',
        'description',
        'editor'
    ];

	protected $casts = [
	    'editor' => 'boolean'
    ];

    public function category()
    {
        return $this->hasOne('App\Models\Constants\ConstantsCategory', 'id', 'id_category');
    }

    public function constants_value()
    {
        return $this->hasMany('App\Models\Constants\ConstantsValue', 'id_const', 'id');
    }

    public function scopeFilter($query)
    {
        $searchQuery = request()->q;
        return $query->whereHas('constants_value', function($query) use($searchQuery){
            return $query->where('value', 'like', '%'.$searchQuery.'%');
        });
    }
}
