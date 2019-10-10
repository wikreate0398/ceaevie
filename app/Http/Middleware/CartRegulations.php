<?php

namespace App\Http\Middleware;

use App\Models\Order;
use Closure;

class CartRegulations
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
        if(!\Auth::check() or !Order::where('id_user', \Auth::user()->id)->where('in_cart', '1')->count())
        { 
            return redirect()->route('empty_cart', ['lang' => lang()]);
        }

        return $next($request);
    }
}
