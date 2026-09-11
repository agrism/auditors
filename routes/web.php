<?php

use App\Http\Middleware\ForClient;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('client.index');
    }

    return redirect()->route('login');
})->middleware('auth');


Route::get('test', function (InvoiceService $invoiceService) {
    $invoiceService->fillInvoiceData();
});


Route::get('login', ['as' => 'login', 'uses' => 'App\\Http\\Controllers\\HomeController@login']);
Route::post('sign-in', ['as' => 'sign-in', 'uses' => 'App\\Http\\Controllers\\HomeController@signIn']);
Route::get('logout', ['as' => 'logout', 'uses' => 'App\\Http\\Controllers\\HomeController@logout']);
Route::post('bug-reports', [\App\Http\Controllers\BugReportController::class, 'store'])->name('bug-reports.store')->middleware('auth');
Route::post('bug-reports/{id}/reply', [\App\Http\Controllers\BugReportController::class, 'reply'])->name('bug-reports.reply')->middleware('auth');




require(app_path() . '/../routes/Routes/clientRoutes.php');
require(app_path() . '/../routes/Routes/adminRoutes.php');
require(app_path() . '/../routes/Routes/apiRoutes.php');

Route::group(
    [
        'middleware' => [ForClient::class],
        'prefix' => 'private',
        'as' => 'private.',
    ], function () {
        Route::get('/', function(){
           echo 'v2 coming soon!';
        })->name('index');
    }
);
