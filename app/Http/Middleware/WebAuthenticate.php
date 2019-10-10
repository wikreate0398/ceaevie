<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class WebAuthenticate
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
        if ((Auth::check() == true && in_array(0, [Auth::user()->active, Auth::user()->confirm])) or empty(Auth::user()->id))
        {
            if ($request->ajax())
            {
                header("HTTP/1.1 401 Unauthorized");
                die();
            }
            else
            {
                Auth::guard('web')->logout();
                return  redirect('/');
            }
        }

        if(Auth::user()->lang != lang())
        {
            \App\Models\User::whereId(Auth::user()->id)->update(['lang' => lang()]);
        }
         
        return $next($request);
    }
}
