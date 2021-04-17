<?php namespace App\Repositories\Invoice;

use App\Invoice;
use App\InvoiceType;
use App\Partner;


use App;
use App\Structuralunit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EloquentInvoiceRepository implements InvoiceRepository
{

	public $companyId;
	public $company;

	private function init()
	{
		$this->company = App\Services\SelectedCompanyService::getCompany();
		if (!isset($this->company->id)) {
			return route('client.companies.index');
		}
		$this->companyId = $this->company->id ?? null;
	}

	public function getInvoices(array $params)
	{
		$this->init();
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

		$invoice = Invoice::select(
			\DB::raw(
				'invoices.*, currencies.name as currency_name, partners.name as partnername,
        structuralunits.title as structuralunitname, invoice_types.title as invoicetypename'
			)
		)
			->leftJoin('partners', 'invoices.partner_id', '=', 'partners.id')
			->leftJoin('structuralunits', 'invoices.structuralunit_id', '=', 'structuralunits.id')
			->leftJoin('invoice_types', 'invoices.invoicetype_id', '=', 'invoice_types.id')
			->leftJoin('currencies', 'invoices.currency_id', '=', 'currencies.id')
			->where('invoices.company_id', $this->companyId);

//		if (!Auth::user()->isAdmin()) {


//		$invoice = $invoice->where(function ($q) {
//			return $q->rightJoin('structuralunits_users', function ($join) {
//				$join->on('structuralunits.id', '=', 'structuralunits_users.structuralunit_id')
//					->where('structuralunits_users.user_id', Auth::user()->id);
//			});
//		});


//		$invoice = $invoice->rightJoin('structuralunits_users', function ($join) {
//			$join->on('invoices.structuralunit_id', '=', 'structuralunits_users.structuralunit_id')
//				->whereIn('structuralunits_users.user_id', Auth::user()->id)
//			;
//		});
//		}

		if (isset($params['sort'])) {

			ksort($params['sort']);

			foreach ($params['sort'] as $key => $value) {

				if (isset($value['orderBy'])) {

					if (!isset($value['direction'])) {
						$value['direction'] = 'asc';
					}

					if ($value['orderBy'] == 'number') {
						$invoice = $invoice->orderByRaw(
							'cast(number as unsigned) '.$value['direction']
						);
					}

					$invoice = $invoice->orderBy(
						$value['orderBy'], $value['direction']
					);

				}
			}
		} else {
			$invoice = $invoice->orderBy('date', 'desc')->orderBy('number', 'desc');
		}

//		dd($params);
		if (isset($params['filter'])) {

			$filter = $params['filter'];

			if (isset($filter['date_from']) && $filter['date_from'] != '') {
				try {
					$dateFrom = Carbon::createFromFormat('d.m.Y', $filter['date_from'])
						->format('Y-m-d');
					$invoice = $invoice->where('date', '>=', $dateFrom);
				} catch (\Exception $e) {
					dd($e->getMessage());
				}
			}

			if (isset($filter['date_to']) && $filter['date_to'] != '') {
				try {
					$dateTo = Carbon::createFromFormat('d.m.Y', $filter['date_to'])
						->format('Y-m-d');
					$invoice = $invoice->where('date', '<=', $dateTo);
				} catch (\Exception $e) {
					dd($e->getMessage());
				}
			}

			if (isset($filter['partner_id']) && $filter['partner_id'] != '') {
				$invoice = $invoice->where('partner_id', $filter['partner_id']);
			}
			if (isset($filter['details_self'])
				&& $filter['details_self'] != ''
			) {
				$invoice = $invoice->where(
					"details_self", "like", "%".$filter['details_self']."%"
				);
			}
			if (isset($filter['structuralunit_id'])
				&& $filter['structuralunit_id'] != ''
			) {
				$invoice = $invoice->where(
					'structuralunit_id', $filter['structuralunit_id']
				);
			}
			if (isset($filter['invoicetype_id'])
				&& $filter['invoicetype_id'] != ''
			) {
				$invoice = $invoice->where(
					'invoicetype_id', $filter['invoicetype_id']
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

		if(!Auth::user()->isAdmin() && $this->company->structuralunits->count() > 0){
			$availableUnitsForUser = Auth::user()->structuralunits->where('company_id', $this->companyId)->pluck('id')->all();

			$invoice = $invoice->filter(function($inv) use($availableUnitsForUser){
				if(!$inv->structuralunit_id){
					return true;
				}

				return in_array($inv->structuralunit_id, $availableUnitsForUser);
			});
		}

		return $invoice;

	}

	public function getPartners()
	{
		$this->init();

		return Partner::where('company_id', $this->companyId)->orderBy(
			'name', 'asc'
		)->get();
	}

	public function getStructuralunits()
	{
		$this->init();

		$units = Structuralunit::where('company_id', $this->companyId)->orderBy('title', 'asc');

		if (!Auth::user()->isAdmin()) {
			$units = $units->whereHas('users', function ($q) {
				$q->where('users.id', Auth::user()->id);
			});
		}

		return $units->get();
	}

	public function getInvoicetypes()
	{
		return InvoiceType::orderBy('title', 'asc')->get();
	}

	public function create()
	{

	}
}
