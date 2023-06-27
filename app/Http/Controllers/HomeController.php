<?php

namespace App\Http\Controllers;

use App\User;
use App\Services\AuthUser;
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

		if(!$user = User::where('email', $email)->first()){
			return redirect()->to('login')->withErrors(['message' => 'User not found']);
		}

		if (!Hash::check($password, $user->password)) {
			return redirect()->to('login');
		}

		Auth::loginUsingId($user->id);

		// if($usrName = AuthUser::instance()->userName() ?? null){
        //     $chatApiToken = env('WHATSAPP_TOKEN'); // Get it from https://www.phphive.info/255/get-whatsapp-password/
        //     $number = env('WHATSAPP_PHONE'); // Number
        //
        //     $usrEmail = AuthUser::instance()->userEmail();
        //
        //     $message = "logged in: $usrName, $usrEmail"; // Message
        //
        //     $curl = curl_init();
        //     curl_setopt_array($curl, array(
        //         CURLOPT_URL => 'http://chat-api.phphive.info/message/send/text',
        //         CURLOPT_RETURNTRANSFER => true,
        //         CURLOPT_ENCODING => '',
        //         CURLOPT_MAXREDIRS => 10,
        //         CURLOPT_TIMEOUT => 0,
        //         CURLOPT_FOLLOWLOCATION => true,
        //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //         CURLOPT_CUSTOMREQUEST => 'POST',
        //         CURLOPT_POSTFIELDS =>json_encode(array("jid"=> $number."@s.whatsapp.net", "message" => $message)),
        //         CURLOPT_HTTPHEADER => array(
        //             'Authorization: Bearer '.$chatApiToken,
        //             'Content-Type: application/json'
        //         ),
        //     ));
        //     $response = curl_exec($curl);
        //     curl_close($curl);
        // }

		return redirect()->route('client.new');
	}

	public function logout()
	{
		Auth::logout();
		\Session::forget('companyId');

		return redirect()->route('client.partners.index');

	}
}