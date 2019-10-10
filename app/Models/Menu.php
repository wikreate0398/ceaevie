<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\OrderingTrait;
use App\Models\Traits\PermisionTrait;

class Menu extends Model
{
    use OrderingTrait, PermisionTrait;
    
	public $timestamps = false;

	protected $table = 'menu';

	protected $fillable = [
        'name_ru', 
        'name_en',
        'text_ru', 
        'text_en',
        'url',
        'seo_title_ru', 
        'seo_title_en',
        'seo_description_ru', 
        'seo_description_en',
        'seo_keywords_ru', 
        'seo_keywords_en'
    ]; 
}
