<?php

namespace App\Http\Middleware;

use App\Log;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BeforeMiddleware
{
	public array $restrictedIp = [
		'45.155.205.211', '128.14.134.134', '162.142.125.53', '74.120.14.55', '167.248.133.39',
		'185.142.239.16', '128.14.133.58', '180.149.125.165', '80.82.77.192', '170.130.187.38', '174.138.13.87',
		'165.232.166.65', '147.139.171.114', '68.183.179.58', '192.35.168.112', '111.7.96.174', '54.160.195.147',
		'170.130.187.10', '45.79.218.30', '139.162.116.133', '138.246.253.24', '45.155.205.27', '45.146.164.110',
		'91.92.47.207', '87.192.253.110'
	];

	public function handle(Request $request, Closure $next)
	{
		$ip = $request->ip();
		if (in_array($ip, $this->restrictedIp)) {
			return response()->json(['message' => 'You are not allowed to access this site.'], 403);
		}

		$fullUrl = $request->fullUrl();

		// Immediately reject common malicious scanning / exploit patterns
		if ($this->isExploitAttempt($fullUrl, $request->all())) {
			return response()->json(['message' => 'Forbidden.'], 403);
		}

		$user = Auth::user();
		if ($user && $user->email === '7924@inbox.lv') {
			return $next($request);
		}

		$debugIps = config('app.debug-ips', []);
		if (empty($debugIps) || !in_array($ip, $debugIps)) {
			// Do not log direct IP scanner hits if Host header is an IP address
			$host = $request->getHost();
			if (!filter_var($host, FILTER_VALIDATE_IP)) {
				try {
					$payload = $request->except(['password', 'password_confirmation', '_token']);

					$log = new Log;
					$log->user_id = $user ? $user->id : null;
					$log->ip = $ip;
					$log->method = $request->method();
					$log->url = substr($fullUrl, 0, 255);
					$log->data = !empty($payload) ? json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : null;
					$log->save();
				} catch (\Throwable $e) {
					\Illuminate\Support\Facades\Log::warning('User activity logging failed: ' . $e->getMessage());
				}
			}
		}

		return $next($request);
	}

	/**
	 * Detect common automated exploit patterns
	 */
	protected function isExploitAttempt(string $url, array $params): bool
	{
		$pattern = '/(\.\.\/|\%2e\%2e|pearcmd|invokefunction|thinkphp|call_user_func|\bselect\b.*\bfrom\b|\bunion\b.*\bselect\b|<\s*script|base64_decode|eval\(|\.env|\.git|wp-config|phpmyadmin|actuator)/i';

		if (preg_match($pattern, $url) || preg_match($pattern, rawurldecode($url))) {
			return true;
		}

		foreach ($params as $key => $val) {
			if (preg_match($pattern, (string)$key) || preg_match($pattern, rawurldecode((string)$key))) {
				return true;
			}
			if (is_string($val) && (preg_match($pattern, $val) || preg_match($pattern, rawurldecode($val)))) {
				return true;
			}
		}

		return false;
	}
}