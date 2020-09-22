<?php

namespace App\Http\Controllers\Auth;

use App\Repositories\Auth\FacebookAuthRepository;
use App\User;
use Laravel\Socialite\Facades\Socialite;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


use Illuminate\Foundation\Auth\ThrottlesLogins;

//use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

use App\AuthenticateUser;
use Illuminate\Http\Request;

class AuthController extends Controller
{
	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/
//    use AuthenticatesAndRegistersUsers, ThrottlesLogins;
	/**
	 * Create a new authentication controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest', ['except' => 'getLogout']);

	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 *
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data)
	{
		return Validator::make(
			$data, [
			'name' => 'required|max:255',
			'email' => 'required|email|max:255|unique:users',
			'password' => 'required|confirmed|min:6',
		]
		);
	}
	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 *
	 * @return User
	 */
	// protected function create(array $data)
	// {
	//     return User::create([
	//         'name' => $data['name'],
	//         'email' => $data['email'],
	//         'password' => bcrypt($data['password']),
	//     ]);
	// }

	// protected function create(array $data)
	// {
	//     return User::create([
	//         'name' => $data['name'],
	//         'email' => $data['email'],
	//         'password' => bcrypt($data['password']),
	//         'provider' => 'facebook',
	//         'provider_id' => $data['id']
	//     ]);
	// }

	// public function login(AuthenticateUser $authenticateUser, Request $request){
	//     return $authenticateUser->execute( $request->has('code'),  $this );
	// }

	// public function userHasLoggedIn($user){
	//     return redirect('/');
	// }
	public function redirectToFacebookProvider()
	{
		return $this->redirectToProvider('facebook');
	}

	public function redirectToLinkedinProvider()
	{
		return $this->redirectToProvider('linkedin');
	}

	public function redirectToTwitterProvider()
	{
		return $this->redirectToProvider('twitter');
	}

	public function redirectToGoogleProvider()
	{
		return $this->redirectToProvider('google');
	}

	public function redirectToProvider($provider)
	{
		if (!Auth::check()) {
			if (!\request()->has('code')) {
				return Socialite::driver($provider)->redirect();

			}
			echo 'code from facebook is gotten';
		}

		return "user is logged in!";
	}

	public function handleFacebookProviderCallback()
	{
		return $this->handleProviderCallback('facebook');
	}

	public function handleLinkedinProviderCallback()
	{
		return $this->handleProviderCallback('linkedin');
	}

	public function handleTwitterProviderCallback()
	{
		return $this->handleProviderCallback('twitter');
	}

	public function handleGoogleProviderCallback()
	{
		return $this->handleProviderCallback('google');
	}

	public function handleProviderCallback($provider)
	{

		$user = Socialite::driver($provider)->stateless()->user();

		$model = User::where('provider_id', $user->id)->where(
			'provider', $provider
		)->first();

		if (!is_null($model)) {
			// make auth
			Auth::loginUsingId($model->id);

			return redirect()->route('client.companies.index');
		} else {
			//create new user
			$newUser = new User();
			$newUser->name = $user->name;
			$newUser->email = $user->email;
			$newUser->avatar = $user->avatar;
			$newUser->provider = $provider;
			$newUser->provider_id = $user->id;
			$newUser->save();

			Auth::loginUsingId($newUser->id);

			return redirect()->route('client.companies.index');

		}

	}

}