<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

class HomeController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('home');
	}

	public function login()
	{
		return view('loginForm');
	}

	public function logout()
	{
		Auth::logout();
		\Session::forget('companyId');

		return redirect()->route('client.partners.index');

	}
}
