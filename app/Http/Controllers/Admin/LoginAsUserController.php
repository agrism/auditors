<?php

namespace App\Http\Controllers\Admin;

use App\LoginAsUser;
use App\User;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;

class LoginAsUserController extends Controller
{
	public function prepareLogin($id)
	{
		if(!$user = User::whereId($id)->first()){
			return redirect()->back()->withErrors(['message' => 'user not found']);
		}

		$loginAsUser = new LoginAsUser;
		$loginAsUser->secret = uniqid();
		$loginAsUser->user_id = $id;
		$loginAsUser->save();

		return redirect()->route('admin.users.index')->with('success', true)
			->with('form_message', _('To login open in incognito mode: <a style="background-color:yellow;" href="'.route('admin.loginAsClient', $loginAsUser->secret).'">'.$user->name.'</a>'));;
	}

	public function login(string $secret){

		if(!$loginAsUser = LoginAsUser::whereSecret($secret)->where('created_at', '>', Carbon::now()->subMinutes(1))->first()){
			return redirect()->route('login')->withErrors('Secret is outdated!');
		}

		if(!$user = User::whereId($loginAsUser->user_id)->first()){
			return redirect()->route('login')->withErrors('User not found!');
		}

		Auth::loginUsingId($loginAsUser->user_id);

		$loginAsUser->delete();
		return redirect()->route('client.companies.index')->with('success', true)->with('form_message', 'Logged in as user: '.$user->name);
	}
}
