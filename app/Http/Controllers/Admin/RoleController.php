<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Role;

class RoleController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		return view('admin.roles.index')->with('roles', Role::all());
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('admin.roles.create');
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
		Role::create($request->all());

		return redirect()->route('admin.roles.index');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, $id)
	{
		if ($request->has('method') && $request->get('method') == 'delete') {
			return $this->destroy($id);
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		return view('admin.roles.edit')->with('role', Role::findOrFail($id));
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
		Role::findOrFail($id)->update($request->all());

		return redirect()->route('admin.roles.index');
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

		$role = Role::find($id);
		if ($role->permissions->count() > 0) {
			return 'Role is assigned to '.$role->permissions->count()
				." permission(s), you can't delete this role!";
		}

		if ($role) {
			$role->delete();
		} else {
			return 'Role does not exist!';
		}
		$roles = Role::get();

		return view('admin.roles.index', compact('roles'));
	}
}
