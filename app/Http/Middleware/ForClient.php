<?php

namespace App\Http\Middleware;

use App\Services\SelectedCompanyService;
use Closure;
use Auth;
use Illuminate\Support\Facades\App;

class ForClient
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 *
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (!Auth::check()) {
			return redirect()->route('login');
		}

		// ja ejam uz citiem routem izņemot client.companies.index - lai nebūtu redireckts loopā
		// un uz client.companies.show - kas, piešķir ID
		if (!in_array($request->route()->getName(), [
			'client.companies.index',
			'client.companies.show',
			'client.user.edit',
			'client.user.update',
		])) {
			// ja nav ID, tad redirekts uz client.companies.index
			if (!SelectedCompanyService::getCompanyId()) {

				return redirect(route('client.companies.index'))->withErrors([
					'name' => 'Select company to continue',
				]);
			}
		}

		return $next($request);
	}
}
