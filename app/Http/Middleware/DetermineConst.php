<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\Constants\ConstantsValue;

class DetermineConst
{
    public function __construct() {}

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
        $constants = ConstantsValue::where('lang', \App::getLocale())->with('constant')->get()->each(function ($item) {
            define($item->constant->name, $item->value);
        });

        return $next($request);
    }
}
