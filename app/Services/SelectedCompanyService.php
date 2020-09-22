<?php

namespace App\Services;

use App\Company;

class SelectedCompanyService
{
	private static $selectedCompany;
	private static $sessionId;

	public static function getCompany()
	{
		if ($id = session()->get('companyId')) {
			if ($id !== self::$sessionId) {
				self::$sessionId = $id;
				if (!self::$selectedCompany = Company::where('id', $id)->first(
				)
				) {
					self::$sessionId = null;
				}
			}
		}

		return self::$selectedCompany;
	}

	public static function getCompanyId()
	{
		if ($company = self::getCompany()) {
			return $company->id ?? null;
		}

		return null;
	}
}