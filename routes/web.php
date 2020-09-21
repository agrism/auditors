<?php

use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return view('welcome');
});



Route::get('queue', function(){
	$user = \App\User::find(14);
//	return $user;
	$user->name = 'Talis3';
	$user->save();
});


Route::get('/', [ 'middleware' => 'auth', function () {

	if(Auth::check()) {
		return redirect()->route('client.index');
	}
	return redirect()->route('login');

}]);



Route::get('test/{id}','Client\InvoiceController@calculateTotalInvoiceAmount');


Route::get('login', ['as' => 'login', 'uses' => 'HomeController@login']);
Route::get('logout', ['as' => 'logout', 'uses' => 'HomeController@logout']);


Route::get('login/facebook', ['as'=>'login.facebook','uses'=>'Auth\AuthController@redirectToFacebookProvider' ]);
Route::get('login/facebook/callback', 'Auth\AuthController@handleFacebookProviderCallback');

Route::get('login/linkedin', ['as'=>'login.linkedin','uses'=>'Auth\AuthController@redirectToLinkedinProvider']);
Route::get('login/linkedin/callback', 'Auth\AuthController@handleLinkedinProviderCallback');

Route::get('login/twitter', ['as'=>'login.twitter','uses'=>'Auth\AuthController@redirectToTwitterProvider']);
Route::get('login/twitter/callback', 'Auth\AuthController@handleTwitterProviderCallback');

Route::get('login/google', ['as'=>'login.google','uses'=>'Auth\AuthController@redirectToGoogleProvider']);
Route::get('login/google/callback', 'Auth\AuthController@handleGoogleProviderCallback');


require(app_path() . '/../routes/Routes/clientRoutes.php');
require(app_path() . '/../routes/Routes/adminRoutes.php');
require(app_path() . '/../routes/Routes/apiRoutes.php');


Route::get('ttt', function(){
	echo  'ttt';
});
