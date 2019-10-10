<?php 

namespace App\Models\Traits; 

trait PermisionTrait
{
	function scopeVisible($query)
	{
		return $query->where('view', '1');
	}
}