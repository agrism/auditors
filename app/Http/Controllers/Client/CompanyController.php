<?php

namespace App\Http\Controllers\Client;

use App\Partner;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Company;
use App\User;
use App\CompanyVatNumber;
use Illuminate\Support\Facades\Session;


class CompanyController extends Controller
{

//    public function __construct(){
//        $this->company  = app()->Company;
//        if( !isset($this->company->id) ){
//            return route('client.companies.index');
//        }
//        $this->companyId  = $this->company['id'];
//    }


	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		// return 'Client\ClientController@index';
		// if(! \App::make('Company')){
		//     return redirect()->route('client.partners.index');
		// }

		// return \App::make('Company');
		// if(\Session::has('companyId')){
		//     return'true';
		// }
		// return 'false';
		// return \Session::get('companyId');

		$user = User::with(
			[
				'companies' => function ($q) {
					$q->orderBy('title', 'asc');
				},
			]
		)->find(Auth::user()->id);

		return view('client.companies.index', compact('user'));

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
		session()->forget('companyId');
		session()->put('companyId', $id);
		session()->save();

		return redirect(route('client.partners.index'));
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
		$companyId = $this->companyId;
		if ($companyId != $id) {
			return 'you do not have rights to access this!';
		}

		$user = User::with(
			[
				'companies' => function ($q) use ($id) {
					$q->with(['vatNumbers'])->find($id);
				},
			]
		)->find(Auth::user()->id);

		$company = $user['companies']->first();

		// return $company ;
		return view('client.companies.edit', compact('company'));
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
		// return $data;
		$companyId = $this->companyId;
		if ($companyId != $id) {
			return 'you do not have rights to access this!';
		}

		Company::find($id)->update($data);

		$updatedVatKeys = [];
		$updatedVatIds = [];

		//  update existing vat numbers
		if (isset($data['vat_id'])) {
			foreach ($data['vat_id'] as $key => $vatId) {
				$array['vat_number'] = $data['vat_number'][$key];
				$array['company_id'] = $companyId;


				$updatedVatIds[] = $vatId;
				$updatedVatKeys[] = $key;
				CompanyVatNumber::find($vatId)->update($array);
			}
		}

		// insert new Vat numbers
		foreach ($data['vat_number'] as $key => $val) {
			if (!in_array($key, $updatedVatKeys) && $data['vat_number'][$key]) {
				$array['vat_number'] = $data['vat_number'][$key];
				$array['company_id'] = $companyId;


				$updatedVatKeys[] = $key;
				$companyVatNumber = new CompanyVatNumber();
				$companyVatNumber->create($array);
				$updatedVatIds[] = $companyVatNumber->id;
			}
		}

		// destroy a deleted vat numbers
		$vatNUmbersToDelete = CompanyVatNumber::whereNotIn('id', $updatedVatIds)
			->where('company_id', $companyId);
		$vatNUmbersToDelete->delete();


		$user = User::with('companies')->find(Auth::user()->id);

		return view('client.companies.index', compact('user'));
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
}
