<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    { 
        $allowedOrigins = [config('app.base_domain'), 'pay.' . config('app.base_domain')];
        $origin         = $request->header('host'); 
        if (in_array($origin, $allowedOrigins)) 
        {     
            return $next($request)
                ->header('Access-Control-Allow-Origin', ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://') . config('app.base_domain'))
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE', 'OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type');
        } 
    }
}
