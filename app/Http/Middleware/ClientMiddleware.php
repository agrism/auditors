<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ClientMiddleware
{
	public function handle($request, Closure $next): mixed
	{
		if (!Auth::check()) {
			return redirect()->route('v2.login');
		}

		return $next($request);
	}
}
