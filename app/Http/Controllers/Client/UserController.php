<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function edit()
	{
		$user = Auth::user();
		return view('client.user.edit', compact('user'));
	}

	public function update(Request $request)
	{
		$data = $request->all();

		$errors = [];

		if(!$data['name']){
			$errors[] = 'name is required!';
		}

		if(!$data['email']){
			$errors[] = 'e-mail is required!';
		}

		if ($data['password'] !== $data['password_repeat']) {
			$errors[] = 'Password field data must be equal';
		}

		if(count($errors)){
			return redirect()->route('client.user.edit')->withErrors($errors);
		}

		$user = Auth::user();
		$user->email = $request->get('email');
		$user->name = $request->get('name');

		if ($pass = $data['password']) {
			$user->password = Hash::make($pass);
		}

		$user->save();

		return redirect()->route('client.user.edit')->with('success', true)->with('form_message', _('Data updated'));
	}
}
