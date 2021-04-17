<?php

namespace App\Http\Controllers\Client;

use App\CompanySetting;
use App\Exports\InvoiceExport;
use App\InvoiceType;
use App\Structuralunit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use App\Invoice;
use App\InvoiceLine;
use App\Partner;
use App\Currency;
use App\Vat;
use App\CompanyBank;
use App\Unit;
use App\CompanyVatNumber;
use Auth;
use Carbon;

use App\Repositories\Invoice\InvoiceRepository;

class InvoiceController extends Controller
{


	private $invoices;

	public function __construct(InvoiceRepository $invoices)
	{

		$this->invoices = $invoices;

		parent::__construct();
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		$partners = $this->invoices->getPartners();

		$structuralunits = $this->invoices->getStructuralunits();

		$invoicetypes = $this->invoices->getInvoicetypes();

		$params = $request->all();


		if (isset($params['filter'])) {
			$filter = $params['filter'];
			foreach ($filter as $key => $value) {
				setcookie(
					'cookies['.$this->companyId.'][filter]['.$key.']',
					($value ?? null), env('COOKIE_EXPIRE_TIME')
				);
				$_COOKIE['cookies'][$this->companyId]['filter'][$key] = ($value ?? null);

			}
		}

		if (isset($params['sort'])) {

			$sort = $params['sort'];
			foreach ($sort as $key => $value) {
				if (isset($sort[$key])) {
					foreach ($sort[$key] as $k => $v) {
						setcookie('cookies['.$this->companyId.'][sort]['.$key.']['.$k.']',
							$v,
							env('COOKIE_EXPIRE_TIME')
						);
						$_COOKIE['cookies'][$this->companyId]['sort'][$key][$k]	= $v;
					}
				}
			}
		}

		$params = isset($_COOKIE['cookies'][$this->companyId])
			? $_COOKIE['cookies'][$this->companyId] : [];

		$invoices = $this->invoices->getInvoices($params);

		if($request->export === 'xls'){
			$invoices->load(['company', 'partner', 'invoiceLines', 'currency', 'invoiceType']);
			return \Maatwebsite\Excel\Facades\Excel::download(new InvoiceExport($invoices), 'invoices.xlsx');
		}

		if (!$invoices) {
			return $this->index(new Request);
		}

		$bank = CompanyBank::where('company_id', $this->companyId)->get()
			->pluck('payment_receiver', 'id');

//        return $invoices;
		return view(
			'client.invoices.index', compact(
				'invoices', 'bank', 'partners', 'params', 'structuralunits',
				'invoicetypes'
			)
		);
	}

	public function getLastFiveInvoices()
	{
		$invoices = Invoice::with('partner')->where(
			'company_id', $this->companyId
		)->where('is_locked', 1)->orderBy('updated_at', 'desc')->limit(5)->get();

		return $invoices;
	}

	public function getCurrentInvoice($id)
	{
		$invoice = Invoice::with('partner')->where(
			'company_id', $this->companyId
		)->find($id);

		return $invoice;
	}

	public function updateInvoiceNumber($id, Request $request)
	{
		$invoice = Invoice::where('company_id', $this->companyId)->find($id);
		$invoice->number = $request->number;
		$invoice->save();

		$responce = ['number' => $invoice->number];

		return $responce;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$partners = Partner::where('company_id', $this->companyId)->orderBy('name', 'asc')->get()
			->pluck('name', 'id');
		$currencies = Currency::get()->pluck('name', 'id');
		$bank = CompanyBank::where('company_id', $this->companyId)->get()
			->map(function($record){
				$record->payment_receiver = $record->payment_receiver . ' | '. $record->bank .' | '.$record->account_number;
				return $record;
			})
			->pluck('payment_receiver', 'id');
		$vats = Vat::get();
		$units = Unit::get();

		$companyVatNumbers = CompanyVatNumber::where(
			'company_id', $this->companyId
		)->get();

		$structuralunits = Structuralunit::where('company_id', $this->companyId)
			->get();
		$invoicetypes = InvoiceType::get();

		return view(
			'client.invoices.create', compact(
				'partners', 'currencies', 'vats', 'bank', 'units',
				'companyVatNumbers', 'structuralunits', 'invoicetypes'
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
		$data = $request->all();
//        return $data;
		$data['company_id'] = $this->companyId;
		if (isset($data['bank_id']) && $data['bank_id']) {
			$selectedOptionalBank = CompanyBank::where(
				'company_id', $this->companyId
			)->find($data['bank_id']);
			$data['payment_receiver']
				= $selectedOptionalBank['payment_receiver'];
			$data['bank'] = $selectedOptionalBank['bank'];
			$data['swift'] = $selectedOptionalBank['swift'];
			$data['account_number'] = $selectedOptionalBank['account_number'];
		} else {
			$data['payment_receiver'] = $this->company->title;
			$data['bank'] = $this->company->bank;
			$data['swift'] = $this->company->swift;
			$data['account_number'] = $this->company->account_number;
		}
		// return $data;
		$invoice = new Invoice();
		$invoice = $invoice->create($data);
		$invoiceId = $invoice->id;
		$invoiceCurrencyId = $invoice->currency_id;

		foreach ($data['title'] as $key => $val) {
			if ($val) {
				$invoiceLine = new InvoiceLine();
				$array = [];
				$array['code'] = $data['code'][$key];
				$array['title'] = $data['title'][$key];
				$array['unit_id'] = $data['unit_id'][$key];
				$array['price'] = $data['price'][$key];
				$array['quantity'] = $data['quantity'][$key];
				$array['vat_id'] = $data['vat_id'][$key];
				$array['currency_id'] = $invoiceCurrencyId;

				$array['invoice_id'] = $invoiceId;

				$invoiceLine->create($array);
			}
		}

		$this->calculateTotalInvoiceAmount($invoiceId);

		if(strtolower($request->get('submit-name')) === 'create'){
			return redirect()->route('client.invoices.edit', $invoiceId)->with('success', true)
				->with('form_message', _('Invoice is updated successfully'));
		}

		return redirect()->route('client.invoices.index')->with('success', true)
			->with('form_message', _('Invoice is created successfully'));

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  Request  $request
	 * @param  int  $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, $id)
	{

		if($request->get('locale') == 'en'){
			app()->setLocale('en');
		} else {
			app()->setLocale('lv');
		}

		$settings = $this->company->settings->pluck('content', 'variable');
		$settingsTopMargin = $settings[CompanySetting::INVOICE_PRINT_TOP_MARGIN] ?? null;
		$settingsBottomMargin = $settings[CompanySetting::INVOICE_PRINT_BOTTOM_MARGIN] ?? null;
		$settingLeftMargin = $settings[CompanySetting::INVOICE_PRINT_LEFT_MARGIN] ?? null;
		$settingRightMargin = $settings[CompanySetting::INVOICE_PRINT_RIGHT_MARGIN] ?? null;

//		dump($settingsTopMargin);
//		dump($settingsBottomMargin);
//		dump($settingLeftMargin);
//		dump($settingRightMargin);
//		dd(1);

		if ($request->has('method') && $request->input('method') == 'delete') {
			return $this->destroy($id);
		}
		libxml_use_internal_errors(true);
		$invoice = Invoice::with(
			[
				'company', 'partner', 'currency', 'invoicelines',
				'invoicelines.unit', 'invoicelines.vat', 'invoicelines.currency',
			]
		)->where('company_id', $this->companyId)->find($id);

		$vats = vat::get();

		// $pdf = \Pdf::loadview('client.invoices.show', compact('invoice', 'vats'));
		// return $pdf->download('invoice.pdf');


		// $pdf = app::make('dompdf.wrapper');
		// $html = '<table border="1" width="600px"><tr><td>1</td></tr></table>';
		// $pdf->loadhtml($html);
		// return $pdf->stream();


// 1 variants strādā

		if ($request->has('type') && $request['type'] == 'html') {
			return view(
				'client.invoices.show',
				compact('invoice', 'vats',
					'settingsTopMargin', 'settingLeftMargin', 'settingsBottomMargin', 'settingRightMargin'
				)
			);
		}
		$pdf = app::make('dompdf.wrapper');

		$pdf->loadview(
			'client.invoices.show',
			compact('invoice', 'vats', 'settingsTopMargin', 'settingLeftMargin',
				'settingsBottomMargin', 'settingRightMargin')
		)
//			->setPaper([0,0,297, 210]);
//			->setPaper([0, 0, 595.28, 841.89]);
			->setPaper('a4');

//			->setPaper([0, 0, 841.89, 1190.55]);
//			->setPaper('a3');

		// return $pdf->stream();

		$invoiceNumber = $invoice->number;
		$date = Carbon\Carbon::createfromformat('d.m.Y', $invoice->date)
			->format('Y-m-d');

		$details = $invoice->details_self;
		$partner = $invoice->partner()->first()->name ?? null;

		$key = strpos($partner, ',');

		$partner = substr($partner, 0, $key);

		app()->setLocale('en');

		return $pdf->download(
			'invoice_'.$invoiceNumber.'_'.$date.'_'.$details.'_'.$partner.'.pdf'
		);

// 2.variants strādā
		// $pdf = app::make('dompdf.wrapper');
		// $html = view('client.invoices.show', compact('invoice', 'vats'));
		// return $html;


		$pdf->loadhtml($html);

		return $pdf->stream();
// 


		// dd($pdf);
		// return $pdf->download('invoice.pdf');
		// return $pdf->stream();


		return view('client.invoices.show', compact('invoice', 'vats', 'settingLeftMargin', 'settingTopMargin'));

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

		$invoice = Invoice::with('invoiceLines')->where(
			'company_id', $this->companyId
		)->find($id);

		$partners = Partner::where('company_id', $this->companyId)->orderBy('name', 'asc')->get()
			->pluck('name', 'id');
		$currencies = Currency::get()->pluck('name', 'id');
		$bank = CompanyBank::where('company_id', $this->companyId)->get()
			->map(function($record){
				$record->payment_receiver = $record->payment_receiver . ' | '. $record->bank .' | '.$record->account_number;
				return $record;
			})
			->pluck('payment_receiver', 'id');
		$units = Unit::orderBy('default', 'desc')->orderBy('name', 'asc')->get();

		$structuralunits = Structuralunit::where('company_id', $this->companyId)
			->get();
		$invoicetypes = InvoiceType::get();

		$selectedBank = CompanyBank::where(
			'payment_receiver', $invoice['payment_receiver']
		)
			->where('bank', $invoice['bank'])
			->where('swift', $invoice['swift'])
			->where('account_number', $invoice['account_number'])->first();

		$vats = Vat::orderBy('default', 'desc')->orderBy('name', 'asc')->get();;

		$companyVatNumbers = CompanyVatNumber::where(
			'company_id', $this->companyId
		)->get();

		// return $invoice;

		return view(
			'client.invoices.edit', compact(
				'invoice', 'partners', 'currencies', 'units', 'vats', 'bank',
				'selectedBank', 'companyVatNumbers', 'structuralunits',
				'invoicetypes'
			)
		);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$data = $request->all();
		// return $this->company;

		$data['company_id'] = $this->companyId;
		// return $data;
		if (isset($data['bank_id']) && $data['bank_id']) {

			$selectedOptionalBank = CompanyBank::where(
				'company_id', $this->companyId
			)->find($data['bank_id']);
			$data['payment_receiver']
				= $selectedOptionalBank['payment_receiver'];
			$data['bank'] = $selectedOptionalBank['bank'];
			$data['swift'] = $selectedOptionalBank['swift'];
			$data['account_number'] = $selectedOptionalBank['account_number'];

		} else {
			// default data!
			$data['payment_receiver'] = $this->company->title;
			$data['bank'] = $this->company->bank;
			$data['swift'] = $this->company->swift;
			$data['account_number'] = $this->company->account_number;
		}

		// return $data;
		$invoice = Invoice::where('company_id', $data['company_id'])->find($id);
		$invoice->update($data);
		$invocieId = $invoice->id;
		$invoiceCurrencyId = $invoice->currency_id;


		foreach ($data['title'] as $key => $val) {
			if ($val) {

				$array = [];
				$array['code'] = substr(($data['code'][$key] ?? ''), 0, 15);
				$array['title'] = $data['title'][$key] ?? null;
				$array['unit_id'] = $data['unit_id'][$key] ?? null;
				$array['price'] = $data['price'][$key] ?? null;
				$array['quantity'] = $data['quantity'][$key] ?? null;
				$array['vat_id'] = $data['vat_id'][$key] ?? null;

				$array['invoice_id'] = $invocieId;
				$array['currency_id'] = $invoiceCurrencyId;

				if ($data['line_id'][$key]) {
					$lineId = $data['line_id'][$key];
					$invoiceLinesIdsExist[]
						= $lineId;// none existing lines will be deleted after update
					$invoiceLine = InvoiceLine::find($lineId);
					$invoiceLine->update($array);
				} else {
					$invoiceLine = new InvoiceLine();
					$invoiceLine->create($array);
					$invoiceLinesIdsExist[] = $invoiceLine->id;
				}
			}
		}

		$deletedInvoiceLines = InvoiceLine::where('invoice_id', $invocieId)
			->whereNotIn(
				'id', isset($invoiceLinesIdsExist) && $invoiceLinesIdsExist
				? $invoiceLinesIdsExist : []
			);
		$deletedInvoiceLines->delete();

		$this->calculateTotalInvoiceAmount($invocieId);

		if(strtolower($request->get('submit-name')) === 'save'){
			return redirect()->route('client.invoices.edit', $id)->with('success', true)
				->with('form_message', _('Invoice is updated successfully'));
		}

		return redirect()->route('client.invoices.index')->with('success', true)
			->with('form_message', _('Invoice is updated successfully'));
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

		$invoice = Invoice::with('invoiceLines')->where(
			'company_id', $this->companyId
		)->find($id);

		$invoice->invoiceLines()->delete();
		$invoice->delete();

		return redirect()->route('client.invoices.index')->with('success', true)
			->with('form_message', _('Invoice is deleted successfully'));

	}

	public function lockInvoice($id)
	{
		$invoice = Invoice::find($id);
		$invoice->locker_user_id = Auth::user()->id;
		$invoice->is_locked = 1;
		$invoice->save();

		return redirect()->back();
	}

	public function unlockInvoice($id)
	{
		$invoice = Invoice::find($id);
		$invoice->is_locked = 0;
		$invoice->save();

		return redirect()->back();
	}

	public function copyInvoice($id)
	{
		$invoice = Invoice::find($id);

		$newInvoice = new Invoice();
		$data = $invoice->toArray();

		$data['date'] = \Carbon\Carbon::now()->format('d.m.Y');
		$data['payment_date'] = \Carbon\Carbon::now()->addDays(10)->format(
			'd.m.Y'
		);
		$data['number'] = 'copy of '.$invoice->number;
		$newInvoice = $newInvoice->create($data);

		$invoiceLines = InvoiceLine::where('invoice_id', $invoice->id)->get();
		foreach ($invoiceLines as $key => $line) {
			$data = $line->toArray();
			$data['invoice_id'] = $newInvoice->id;
			InvoiceLine::create($data);
		}


		return redirect()->back()->with('success', true)->with(
			'form_message', _('Invoice is copied successfully')
		);

	}

	public function calculateTotalInvoiceAmount($id)
	{
		$vats = Vat::get();
		foreach ($vats as $key => $vat) {
			$total[$vat->id] = 0;
			$total['rate'][$vat->id] = $vat->rate;
		}

		$invoiceLines = InvoiceLine::with('vat')->where('invoice_id', $id)->get();
		// return $invoiceLines;

		foreach ($invoiceLines as $key => $line) {
			$total[$line->vat_id] += round($line->quantity * $line->price, 2);
		}

		$vats = Vat::get();
		$invoiceTotal = 0;
		foreach ($vats as $key => $vat) {
			$invoiceTotal += $total[$vat->id] + ROUND(
					$total[$vat->id] * $total['rate'][$vat->id], 2
				);
		}

		$invoice = Invoice::find($id);
		$invoice->amount_total = $invoiceTotal;
		$invoice->save();

		return;
	}
}
