<?php

use App\Http\Middleware\ClientMiddleware;
use App\Http\Middleware\ForClient;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get(
    '/', [
        'middleware' => 'auth',
        function () {
            if (Auth::check()) {
                return redirect()->route('client.index');
            }

            return redirect()->route('login');
        },
    ]
);


Route::get('test', function (InvoiceService $invoiceService) {
    $invoiceService->fillInvoiceData();
});


Route::get('login', ['as' => 'login', 'uses' => 'App\\Http\\Controllers\\HomeController@login']);
Route::post('sign-in', ['as' => 'sign-in', 'uses' => 'App\\Http\\Controllers\\HomeController@signIn']);
Route::get('logout', ['as' => 'logout', 'uses' => 'App\\Http\\Controllers\\HomeController@logout']);


require(app_path() . '/../routes/Routes/clientRoutes.php');
require(app_path() . '/../routes/Routes/adminRoutes.php');
require(app_path() . '/../routes/Routes/apiRoutes.php');

Route::group(['prefix' => 'v2', 'as' => 'v2.',], function () {
    Route::get('login', \App\Http\Controllers\V2\Auth\LoginController::class)->name('login');
    Route::post('auth', \App\Http\Controllers\V2\Auth\AuthController::class)->name('auth');
    Route::post('logout', \App\Http\Controllers\V2\Auth\LogoutController::class)->name('logout');

    Route::group(['middleware' => ClientMiddleware::class], function(){
        Route::get('/', \App\Http\Controllers\V2\IndexController::class)->name('index');
        Route::get('/invoices', \App\Http\Controllers\V2\Invoices\IndexController::class)->name('invoices.index');
        Route::get('/partners', \App\Http\Controllers\V2\Partners\IndexController::class)->name('partners.index');
        Route::get('/companies', \App\Http\Controllers\V2\Companies\IndexController::class)->name('companies.index');
        Route::get('/companies/{id}/activate', \App\Http\Controllers\V2\Companies\ActivateController::class)->name('companies.activate');
    });
});
