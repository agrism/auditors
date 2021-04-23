<?php

namespace App\Http\Middleware;


use App\Log;
use Auth;
use Closure;
use Debugbar;

class BeforeMiddleware
{
	public $restrictedIp = ['45.155.205.211', '128.14.134.134', '162.142.125.53', '74.120.14.55','167.248.133.39',
		'185.142.239.16','128.14.133.58', '180.149.125.165', '80.82.77.192', '170.130.187.38','174.138.13.87',
		'165.232.166.65', '147.139.171.114', '68.183.179.58', '192.35.168.112', '111.7.96.174', '54.160.195.147',
		'170.130.187.10', '45.79.218.30', '139.162.116.133', '138.246.253.24'];


	public function handle($request, Closure $next)
	{

		if (in_array($request->ip(), $this->restrictedIp)) {
			return response()->json(['message' => "You are not allowed to access this site."]);
		}

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
//			Debugbar::enable();
		}

		return $next($request);
	}
}