<?php

namespace easyCRM\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthRouteAccess
{
    public function handle($request, Closure $next)
    {
        $roles = $request->route()->getAction('roles', []);
        foreach ((array) $roles as $role) {
            if ($role == Auth::guard('web')->user()->profiles->name) {
                return $next($request);
            }
        }
        return redirect('/');
    }
}
