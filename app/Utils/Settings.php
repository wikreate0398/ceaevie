<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 21.02.2019
 * Time: 19:57
 */

namespace App\Utils;
use App\Models\Settings as SettingsModel;

class Settings
{
    private static $_data;

    public static function get($key)
    {
        if(isset(self::$_data[$key])){
            return self::$_data[$key]['value'];
        }
        self::$_data = SettingsModel::all()->keyBy('var');
        return self::$_data[$key]['value'];
    }
}