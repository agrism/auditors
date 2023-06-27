<?php

use App\Services\InvoiceService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get(
    '/', [
        'middleware' => 'auth', function () {

            if (Auth::check()) {
                return redirect()->route('client.index');
            }

            return redirect()->route('login');

        },
    ]
);


Route::get('test', function(\App\Services\InvoiceService $invoiceService){
    $invoiceService->fillInvoiceData();
});


Route::get('login', ['as' => 'login', 'uses' => 'App\\Http\\Controllers\\HomeController@login']);
Route::post('sign-in', ['as' => 'sign-in', 'uses' => 'App\\Http\\Controllers\\HomeController@signIn']);
Route::get('logout', ['as' => 'logout', 'uses' => 'App\\Http\\Controllers\\HomeController@logout']);


require(app_path() . '/../routes/Routes/clientRoutes.php');
require(app_path() . '/../routes/Routes/adminRoutes.php');
require(app_path() . '/../routes/Routes/apiRoutes.php');
