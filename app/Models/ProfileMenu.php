<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\OrderingTrait;
use App\Models\Traits\PermisionTrait;

class ProfileMenu extends Model
{
	use OrderingTrait, PermisionTrait;
	
	public $timestamps = false;

	protected $table = 'profile_menu';

	protected $fillable = [
        'name_ru', 
        'name_en', 
        'route',
        'description'
    ]; 

    public function access()
    {
    	return $this->hasMany('App\Models\ProfileMenuGuard', 'id_menu', 'id');
    }

    public function scopeAccessType($query, $type)
    {
    	return $query->whereHas('access', function($query) use($type){
    		return $query->where('type', $type);
    	});
    }
}
