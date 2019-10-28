<?php

namespace App\Utils;
use Illuminate\Http\Request; 
use App\Models\Languages;

class Language  
{

    public function __construct() {}

    /*
     * Select all languages
     */
	public static function get()
	{
		return Languages::orderByRaw('page_up asc, id desc')->where('view', '1')->get();
	}

    /*
     * Select all languages
     *
     * @param $fields
     * @param $post
     *
     * @return array
     */
	public static function returnData($fields, $post = false)
	{
		if (empty($post)) 
		{
			$post = \Request::all();
		}
 
		foreach ($fields as $keyField => $fieldName) 
		{
			foreach ($post[$fieldName] as $keyLang => $value) {
				$post[$fieldName . '_' . $keyLang] = !empty($value) ? $value : '';
			}
			unset($post[$fieldName]);
		}

		return $post;
	}
}