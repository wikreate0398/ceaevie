<?php

namespace App\Utils;
use Illuminate\Http\Request;
use \App\Models\Menu;

/**
 *
 */
class Pages
{
    private static $_page_data;

    function __construct() {}

    public static function top()
    {
        return Menu::where('view_top', '1')->orderByPageUp()->get();
    }

    public static function bottom()
    {
        return Menu::where('view_bottom', '1')->orderByPageUp()->get();
    }

    public function active($url)
    {
        (request()->segment(1) == $url) ? true : false;
    }

    public static function pageData($page_type = false)
    {
        $query = new Menu;
        if(!empty($page_type))
        {
            $query = $query->where('page_type', $page_type);
        }
        else
        { 
            $query = $query->where('url', self::uriName());
        }
        return $query->first();
    }

    public static function uriName()
    {
        return \Request::segment(2) ? \Request::segment(2) : '/';
    }

    public function getUriByType($type)
    {
        return @Menu::where('page_type', $type)->first()->url;
    }
}