<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\App;
use App\Partner;
use Validator;

class PartnerController extends Controller
{


	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */


	public function index()
	{
		$partners = Partner::where('company_id', $this->companyId)->oldest(
			'name'
		)->get();

		return view('client.partners.index', compact('partners'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		return view('client.partners.create');
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

		$validator = Validator::make($data, Partner::createRules());

		if ($validator->fails()) {
			return redirect()->back()
				->withErrors($validator)
				->withInput();
		}


		$data['company_id'] = $this->companyId;
		// return $data;
		Partner::create($data);

		return redirect()->route('client.partners.index')->with('success', true)
			->with('form_message', _('Partner is created successfully'));

		return $this->index();
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
		//
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
		$partner = Partner::where('company_id', $this->companyId)->find($id);

		return view('client.partners.edit', compact('partner'));
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
		$data['company_id'] = $this->companyId;
		// return $data;
		$partner = Partner::where('company_id', $this->companyId)->find($id);

		$partner->update($data);

		return redirect()->route('client.partners.index')->with('success', true)
			->with('form_message', _('Partner is updated successfully'));
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
		return redirect()->route('client.partners.index')->with('warning', true)
			->with(
				'form_message',
				_('There is not possibility to delete Partner now')
			);
	}
}
