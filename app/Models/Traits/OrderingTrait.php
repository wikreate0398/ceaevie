<?php 

namespace App\Models\Traits; 

trait OrderingTrait
{
	function scopeOrderByPageUp($query)
	{
		return $query->orderByRaw('page_up asc, id desc');
	}
}