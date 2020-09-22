<?php

namespace App\Http\Controllers\Client;

use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\CompanyBank;


class CompanyBankController extends Controller
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function index()
	{

		$banks = CompanyBank::where('company_id', $this->company['id'])->get();
		$company = $this->company;

		return view(
			'client.companies.banks.index', compact('banks', 'company')
		);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('client.companies.banks.create');
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
		$data = $request->all();
		$data['company_id'] = $this->company['id'];
		CompanyBank::create($data);

		return redirect()->route('client.companies.bank.index');
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
		if ($request->get('method') == 'delete') {
			$this->destroy($id);

			return redirect()->route('client.companies.bank.index');
		} else {
			'nothing to delete!';
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
		$bank = CompanyBank::where('company_id', $this->company['id'])->find(
			$id
		);

		return view('client.companies.banks.edit', compact('bank'));
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
		$data = $request->all();
		$data['company_id'] = $this->company['id'];

		// $bank = CompanyBank::where('company_id', $this->company['id'])->where('id', $id)->first();
		$bank = CompanyBank::where('company_id', $this->company->id)->find($id);

		$bank->update($data);

		return redirect()->route('client.companies.bank.index');

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
		$bank = CompanyBank::where('company_id', $this->company['id'])->find(
			$id
		);
		if ($bank) {
			$bank->delete();
		}

		return redirect()->route('client.companies.bank.index');
	}
}
