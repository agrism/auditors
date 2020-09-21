<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class ForAdmin
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
        if(!Auth::check()){
            // return 'not signed in';
            return redirect()->route('login');

        }

        if(!Auth::user()->isAdmin()){
            return redirect()->route('client.index');
        }
        return $next($request);
    }
}
