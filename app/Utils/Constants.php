<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 22.02.2019
 * Time: 11:52
 */

namespace App\Utils;
use App\Models\Constants\Constants as ConstantsModel;

class Constants
{
    private static $_const;

    public static function get($key)
    {
        if(self::$_const[$key]){
            return self::$_const[$key];
        }

        $constants = ConstantsModel::with(['constants_value' => function($query){
            return $query->where('lang', \App::getLocale());
        }])->get()->keyBy('name');

        foreach ($constants as $constant)
        {
            if($constant->constants_value[0])
            {
                foreach($constant->constants_value as $value)
                {
                    self::$_const[$constant['name']] = $value['value'];
                }
            }
        }

        if(self::$_const[$key]){
            return self::$_const[$key];
        }
    }
}