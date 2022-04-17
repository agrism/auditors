<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	protected $companyId;
	/**
	 * @var Company
	 */
	protected $company;

	public function __construct()
	{
		$this->company = app()->Company;

		$this->initCompany();

		if (!isset($this->company->id)) {
			return route('client.companies.index');
		}
	}

	private function initCompany()
	{

		$this->middleware(function ($request, $next) {

				if ($request->session()->has('companyId')) {
					$this->companyId = $request->session()->get('companyId');

					if (!$this->company = Company::where('id', $this->companyId)->first()) {
						$this->companyId = null;
					}
				}

				return $next($request);
			});
	}
}
