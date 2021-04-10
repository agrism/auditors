<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Hash;

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

	public function signIn(Request $request)
	{
		$email = trim($request->get('email'));
		$password = trim($request->get('password'));


		if (!$email || !$password) {
			return redirect()->to('login');
		}

		$user = User::where('email', $email)->first();

		if (!Hash::check($password, $user->password)) {
			return redirect()->to('login');
		}

		Auth::loginUsingId($user->id);

		return redirect()->route('client.companies.index');
	}

	public function logout()
	{
		Auth::logout();
		\Session::forget('companyId');

		return redirect()->route('client.partners.index');

	}
}
