<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use App\Partner;
use App\Role;
use Illuminate\Support\Facades\Input;

class UserController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
//        dd(\Auth::user()->isAdmin());
		return view('admin.users.index')->with('users', User::all());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show($id)
	{
		return 'UserController@show()';
		// return User::with('companies')->get()->;
		// $user = User::with('companies')->findOrFail($id);
		// // $partners = Partner::all()->pluck('name', 'id');

		// return view('admin.users.show', compact('user'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit(Request $request, $id)
	{
		$user = User::find($id);

		// return $user;
		return view('admin.users.edit', compact('user'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int                       $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$user = User::find($id);
		$user->update($request->all());
		$users = User::all();

		return view('admin.users.index', compact('users'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		//
	}

	public function assignToPartner(Request $request)
	{
		$user = User::findOrFail($request->user_id);

		try {
			$user->partners()->attach($request->partner_id);
			$response['response'] = 'success';
			$response['user'] = $user->partners;
		} catch (\Exception $e) {
			$response['response'] = 'error';
			$response['message'] = 'User is allready assigned to this partner!';
		}

		return $response;

	}

	public function assignRoleToUser()
	{

		$partnerId = Input::get('partner_id');

		$user = User::with(
			[
				'partners' => function ($q) use ($partnerId) {
					$q->find($partnerId)->first();
				},
			]
		)->find(Input::get('user_id'));

		$roles = $user->roles->filter(
			function ($role) use ($partnerId) {
				return $role->pivot->partner_id == $partnerId;
			}
		);

		$allRoles = Role::all();

		//return $allRoles;


		return view('admin.users.roles.index')
			->with('roles', $roles)
			->with('allRoles', $allRoles)
			->with('user', $user)
			->with('partnerId', $partnerId);

	}

	public function assignRoleToUserSave(Request $request)
	{
		//return $request->all();
		$partnerId = $request->partner_id;
		$userId = $request->user_id;

		// $user = User::with(['partners'=>function($q) use( $partnerId ){
		//     $q->find($partnerId)->first();
		// }])->find($userId)->first();

		$user = User::with(
			[
				'partners' => function ($q) use ($partnerId) {
					$q->find($partnerId)->first();
				},
			]
		);


		return $user;
	}


}
