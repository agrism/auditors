<?php

namespace App\Providers;

use App\Company;
use http\Env\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		App::bind(
			'App\\Repositories\\Invoice\\InvoiceRepository',
			'App\\Repositories\\Invoice\\EloquentInvoiceRepository'
		);
		App::bind(
			'App\\Repositories\\PersonalIncome\\PersonalIncomeRepository',
			'App\\Repositories\\PersonalIncome\\EloquentPersonalIncomeRepository'
		);


		app()->singleton(
			'Company', function ($app) {
			if (\Session::has('companyId')) {
				return Company::orderBy('title', 'asc')->find(
					Session::get('companyId')
				);
			} else {
				return redirect(route('login'));
			}
		}
		);
	}

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		Schema::defaultStringLength(191);

	}
}
