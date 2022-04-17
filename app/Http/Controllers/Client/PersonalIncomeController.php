<?php

namespace App\Http\Controllers\Client;


use App\PersonalIncomeCostRate;
use App\PersonalIncomeTaxRate;
use App\PersonalIncomeType;
use App\Repositories\PersonalIncome\PersonalIncomeRepository;
use App\Services\SelectedCompanyService;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\App;
use App\Partner;
use Validator;

class PersonalIncomeController extends Controller
{

	public $personalIncomes;

	/**
	 * Display a listing of the resource.
	 *
	 * @param  Request  $request
	 * @param  PersonalIncomeRepository  $personalIncomes
	 *
	 * @return \Illuminate\Http\Response
	 */


	public function index(
		Request $request, PersonalIncomeRepository $personalIncomes
	) {
		$this->personalIncomes = $personalIncomes;

		$partners = $this->personalIncomes->getPartners();

		$personalIncomeTypes = $this->personalIncomes->getPersonalIncomeTypes();

		$params = $request->all();

		if (isset($params['filter'])) {
			$filter = $params['filter'];
			foreach ($filter as $key => $value) {
				if (isset($filter[$key])) {
					setcookie(
						'cookies['.SelectedCompanyService::getCompanyId()
						.'][filter]['.$key.']', $filter[$key],
						env('COOKIE_EXPIRE_TIME')
					);
					$_COOKIE['cookies'][SelectedCompanyService::getCompanyId(
					)]['filter'][$key]
						= $filter[$key];
				}
			}
		}


		if (isset($params['sort'])) {

			$sort = $params['sort'];
			foreach ($sort as $key => $value) {
				if (isset($sort[$key])) {
					foreach ($sort[$key] as $k => $v) {
						setcookie(
							'cookies['.SelectedCompanyService::getCompanyId()
							.'][sort]['.$key.']['.$k.']', $v,
							env('COOKIE_EXPIRE_TIME')
						);
						$_COOKIE['cookies'][SelectedCompanyService::getCompanyId(
						)]['sort'][$key][$k]
							= $v;
					}
				}
			}
		}


		$params
			= isset($_COOKIE['cookies'][SelectedCompanyService::getCompanyId()])
			? $_COOKIE['cookies'][SelectedCompanyService::getCompanyId()] : [];


		$personalIncomes = $this->personalIncomes->getPersonalIncomes($params);

		if (!$personalIncomes) {
//            return $this->index(new Request);
		}

		return view(
			'client.personal-incomes.index', compact(
			'personalIncomes', 'partners', 'params', 'personalIncomeTypes'
		)
		);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$partners = Partner::where(
			'company_id', SelectedCompanyService::getCompanyId()
		)->get()->pluck('name', 'id');
		$personalIncomeTypes = PersonalIncomeType::get()->pluck('name', 'id');
		$personalIncomeTaxRates = PersonalIncomeTaxRate::get();
		$personalIncomeCostRates = PersonalIncomeCostRate::get();

		return view(
			'client.personal-incomes.create', compact(
			'partners', 'personalIncomeTypes', 'personalIncomeTaxRates',
			'personalIncomeCostRates'
		)
		);
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

	}
}
