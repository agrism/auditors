<?php

namespace App\Http\Middleware;


use App\Log;
use Auth;
use Closure;
use Debugbar;

class BeforeMiddleware
{

	public function handle($request, Closure $next)
	{
		if(!in_array($request->ip(), config('app.debug-ips', []))){
			$log = new Log;
			$log->user_id = \Illuminate\Support\Facades\Auth::id();
			$log->ip = $request->ip();
			$log->method = $request->method();
			$log->url = substr($request->url(), 0, 255);
			$log->data = json_encode($request->all(), JSON_PRETTY_PRINT);

			$log->save();
		}


//		if (env('APP_DEBUG') == 'true') {
//			if (!Auth::check() || (Auth::check() && Auth::user()->is_developer != 1)) {
//				dump(1);
//				Debugbar::disable();
//			} else {
//				dump(2);
//				config('app.debug', true);
//				Debugbar::enable();
//			}
//		}

		if(in_array($_SERVER['REMOTE_ADDR'],explode(',', env('APP_DEBUG_IPS')))){
			Debugbar::enable();
		}

		return $next($request);
	}
}