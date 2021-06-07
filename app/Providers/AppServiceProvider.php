<?php

namespace App\Providers;

use App\Company;
use App\Http\Middleware\ForClient;
use App\Services\AuthUser;
use http\Env\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

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
            'Company',
            function ($app) {
                if ($companyId = \Session::has('companyId')) {

                    return \Auth::user()->companies()->where('id', $companyId)->first();
                }
                return null;
            }
        );

        app()->singleton(AuthUser::class, function (){
            return new AuthUser;
        });


        Livewire::addPersistentMiddleware([
            ForClient::class,
        ]);
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
