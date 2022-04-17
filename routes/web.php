<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


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

/*
Route::get('login/facebook', [
		'as' => 'login.facebook',
		'uses' => 'Auth\AuthController@redirectToFacebookProvider',
	]
);
Route::get('login/facebook/callback', 'Auth\AuthController@handleFacebookProviderCallback');

Route::get('login/linkedin', [
		'as' => 'login.linkedin',
		'uses' => 'Auth\AuthController@redirectToLinkedinProvider',
	]
);
Route::get('login/linkedin/callback', 'Auth\AuthController@handleLinkedinProviderCallback');

Route::get('login/twitter', [
		'as' => 'login.twitter',
		'uses' => 'Auth\AuthController@redirectToTwitterProvider',
	]
);
Route::get('login/twitter/callback', 'Auth\AuthController@handleTwitterProviderCallback');

Route::get(	'login/google', [
		'as' => 'login.google',
		'uses' => 'Auth\AuthController@redirectToGoogleProvider',
	]
);
Route::get(	'login/google/callback', 'Auth\AuthController@handleGoogleProviderCallback');
*/

require(app_path() . '/../routes/Routes/clientRoutes.php');
require(app_path() . '/../routes/Routes/adminRoutes.php');
require(app_path() . '/../routes/Routes/apiRoutes.php');

Route::get('test', \App\Http\Livewire\TestRoot::class);

Route::get('/7', function () {
    $invoice = DB::table('invoices')->select(
        \DB::raw(
            'invoices.id, 
                invoices.date, 
                invoices.payment_date, 
                invoices.vat_number, 
                invoices.is_locked, 
                invoices.number, 
                invoices.amount_total, 
                invoices.details_self, 
                invoices.structuralunit_id, 
                currencies.name as currency_name, 
                invoices.currency_rate, 
                partners.name as partnername,
                partners.vat_number as partner_vat_number,

                structuralunits.title as structuralunitname, 
                invoice_types.title as invoicetypename'
        )
    )
        ->leftJoin('partners', 'invoices.partner_id', '=', 'partners.id')
        ->leftJoin('structuralunits', 'invoices.structuralunit_id', '=', 'structuralunits.id')
        ->leftJoin('invoice_types', 'invoices.invoicetype_id', '=', 'invoice_types.id')
        ->leftJoin('currencies', 'invoices.currency_id', '=', 'currencies.id')
        ->where('invoices.company_id', 15);

    $invoices = $invoice->get();

    dump($invoices);

    $invoiceLines = DB::table('invoice_lines')
        ->selectRaw('invoice_lines.*')
        ->leftJoin('invoices', 'invoice_lines.invoice_id', '=', 'invoices.id')
        ->where('company_id', '15')->get();

    dump($invoiceLines);

    foreach($invoices as &$invoice){

//        dd($invoice);


        $invoice->invoice_lines = $invoiceLines->where('invoice_id', $invoice->id)->all();
    }

    dump($invoices);

});
