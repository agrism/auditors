<?php

namespace App\Http\Middleware;



use Auth;
use Closure;
use Debugbar;

class BeforeMiddleware
{

    public function handle($request, Closure $next)
    {
        if(env('APP_DEBUG') == 'true'){
            if(!Auth::check() || (Auth::check() && Auth::user()->is_developer != 1)){
                Debugbar::disable();
            }
        }

        return $next($request);
    }
}