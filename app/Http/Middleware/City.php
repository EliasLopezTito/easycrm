<?php

namespace NavegapComprame\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class City
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
        if(!Session::has('city') && (!Auth::guard('client')->check() || Auth::guard('client')->user()->city_id == null)){
           return redirect(route('cities'));
        }

        return $next($request);
    }
}
