<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
//    protected function redirectTo(Request $request): ?string
//    {
//        return $request->expectsJson() ? null : route('login');
//    }

    public function handle($request, Closure $next, ...$guards)
    {
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            }
        }

        return $next($request);
    }
}
