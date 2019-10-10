<?php

namespace App\Http\Middleware;

use Closure;

class Lang
{
    private $currentLang = 'en';

    private $languages_array;

    public function __construct()
    {
        $this->languages_array = $this->acceptableLanguages();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $this->determine();
        \View::share( 'lang', $this->currentLang);
        \View::share( 'page_data', \Pages::pageData());

        if(\Request::segment(1) && !in_array(\Request::segment(1), $this->languages_array))
        {
            abort('404');
        }

        return $next($request);
    }

    /**
     * Get array of existing languages
     *
     * @return array
     */
    public function acceptableLanguages()
    {
        return \Language::get()->pluck('short')->toArray();
    }

    /**
     * Determine site language
     *
     * @return void
     */
    public function determine()
    {
        if(in_array(\Request::segment(1), $this->languages_array))
        {
            $this->currentLang = request()->segment(1);
        }
        elseif (\Session::has('lang'))
        {
            $this->currentLang = \Session::get('lang');
        }
        elseif (in_array(self::getHttpAcceptLang(), $this->languages_array))
        {
            $this->currentLang = self::getHttpAcceptLang();
        }

        $this->putAndSave();
    }

    /**
     * Save language in session
     */
    private function putAndSave(){
        \App::setLocale($this->currentLang);
        \Session::put('lang', $this->currentLang);
        \Session::save();
    }

    private static function getHttpAcceptLang()
    {
        return self::getDefaultLanguage();
    }

    private static function getDefaultLanguage() {
        if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]))
        {
            return self::parseDefaultLanguage($_SERVER["HTTP_ACCEPT_LANGUAGE"]);
        }
        else
        {
            return self::parseDefaultLanguage(NULL);
        }
    }

    private static function parseDefaultLanguage($http_accept, $deflang = "ro") {
        if(isset($http_accept) && strlen($http_accept) > 1)
        {
            # Split possible languages into array
            $x = explode(",",$http_accept);
            foreach ($x as $val)
            {
                #check for q-value and create associative array. No q-value means 1 by rule
                if(preg_match("/(.*);q=([0-1]{0,1}.\d{0,4})/i",$val,$matches))
                {
                    $lang[$matches[1]] = (float)$matches[2];
                }
                else
                {
                    $lang[$val] = 1.0;
                }
            }

            #return default language (highest q-value)
            $qval = 0.0;
            foreach ($lang as $key => $value)
            {
                if ($value > $qval)
                {
                    $qval = (float)$value;
                    $deflang = $key;
                }
            }
            return substr($deflang, 0, 2);
        }
        return strtolower($deflang);
    }
}
