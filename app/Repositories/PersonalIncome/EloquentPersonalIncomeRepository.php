<?php namespace App\Repositories\PersonalIncome;

use App\Partner;


use App;
use App\PersonalIncome;
use App\PersonalIncomeType;

class EloquentPersonalIncomeRepository implements PersonalIncomeRepository
{

	public $companyId;
	public $company;

	public function __construct()
	{
		$this->company = App\Services\SelectedCompanyService::getCompany();
		if (!isset($this->company->id)) {
			return route('client.companies.index');
		}
		$this->companyId = $this->company['id'];
	}

	public function getPersonalIncomes(array $params)
	{

		/***
		 * $params = [
		 *    'sort'=>[
		 *        [
		 *            'orderBy'=>'name',
		 *            'direction'=>'asc'
		 *        ],
		 *        [
		 *            'orderBy'=>'number',
		 *            'direction'=>'asc'
		 *        ],
		 * ],
		 *    'filter'=>['partner_id'=>1, number=>'123']
		 * ];
		 */
		// $invoice =  Invoice::where('company_id', $this->companyId);

		$invoice = PersonalIncome::select(
			\DB::raw(
				'personal_incomes.*, partners.name as partnername,
        personal_income_types as personal_income_type'
			)
		)
			->leftJoin(
				'partners', 'personal_incomes.partner_id', '=', 'partners.id'
			)
			->leftJoin(
				'personal_income_types',
				'personal_incomes.personal_income_type_id', '=',
				'personal_income_type.id'
			)
			->where('personal_incomes.company_id', $this->companyId);

//		dd($params);

		if (isset($params['sort'])) {

			foreach ($params['sort'] as $key => $value) {

				if (isset($value['orderBy'])) {

					if (!isset($value['direction'])) {
						$value['direction'] = 'asc';
					}

					$invoice = $invoice->orderBy(
						$value['orderBy'], $value['direction']
					);
				}
			}
		} else {
			$invoice = $invoice->orderBy('date', 'desc')->orderBy(
				'number', 'desc'
			);
		}

//		dd($params);
		if (isset($params['filter'])) {

			$filter = $params['filter'];

			if (isset($filter['partner_id']) && $filter['partner_id'] != '') {
				$invoice = $invoice->where('partner_id', $filter['partner_id']);
			}

			if (isset($filter['personal_income_type_id'])
				&& $filter['personal_income_type_id'] != ''
			) {
				$invoice = $invoice->where(
					'personal_income_type_id',
					$filter['personal_income_type_id']
				);
			}
		}
//		dd($filter);

		try {
			$invoice = $invoice->get();
		} catch (\Illuminate\Database\QueryException $e) {
			// dd('try to sort none existing column!');
			return false;
		}

		return $invoice;

	}

	public function getPartners()
	{
		return Partner::where('company_id', $this->companyId)->orderBy(
			'name', 'asc'
		)->get();
	}

	public function getPersonalIncomeTypes()
	{
		return PersonalIncomeType::orderBy('name', 'asc')->get();
	}

	public function create()
	{

	}


}
