<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Company;

class CompanyController extends Controller
{


	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */


	public function index()
	{
		$companies = Company::orderBy('title', 'asc')->get();

		return view('admin.companies.index')->with('companies', $companies);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('admin.companies.create');
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
		Company::create($request->all());
		$companies = Company::orderBy('title', 'asc')->get();

		return view('admin.companies.index', compact('companies'));
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

		$company = Company::whereId($id)->first();

		return view('admin.companies.show')->with('company', $company);
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
		$company = Company::find($id);

		return view('admin.companies.edit', compact('company'));
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
		// return $request->all();
		Company::find($id)->update($request->all());

//        $companies = Company::get();
		return redirect()->route('admin.companies.index')->with('success', true)
			->with('form_message', _('Company has updated successfully'));

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
		$company = Company::find($id);
		if ($company) {
			$company->delete();
		} else {
			return 'company do not exists!';
		}
		$companies = Company::get();

		return view('admin.companies.index', compact('companies'));
	}
}
