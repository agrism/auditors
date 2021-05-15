<?php

namespace App\Http\Controllers\Client;

use App\CompanySetting;
use App\Exports\InvoiceExport;
use App\InvoiceType;
use App\Services\InvoiceService;
use App\StructuralunitUser;
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
	 * @param  Request  $request
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Symfony\Component\HttpFoundation\BinaryFileResponse
	 */
	public function index(Request $request)
	{
        return view('client.invoices.list');

            $partners = $this->company->partners;

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
						setcookie(
							'cookies['.$this->companyId.'][sort]['.$key.']['.$k.']',
							$v,
							env('COOKIE_EXPIRE_TIME')
						);
						$_COOKIE['cookies'][$this->companyId]['sort'][$key][$k] = $v;
					}
				}
			}
		}

		$params = isset($_COOKIE['cookies'][$this->companyId]) ? $_COOKIE['cookies'][$this->companyId] : [];

		$invoices = $this->invoices->getInvoices($params);

		if ($request->export === 'xls') {
			$invoices->load(['company', 'partner', 'invoiceLines', 'currency', 'invoiceType', 'structuralunit']);
			return \Maatwebsite\Excel\Facades\Excel::download(new InvoiceExport($invoices), 'invoices.xlsx');
		}

		if (!$invoices) {
			return $this->index(new Request);
		}

		return view('client.invoices.index', [
			'invoices' => $invoices,
			'partners' => $partners,
			'params' => $params,
			'structuralunits' => $structuralunits,
			'invoicetypes' => $invoicetypes,
		]);
	}

	public function getLastFiveInvoices()
	{
		return Invoice::with('partner')->where(
			'company_id', $this->companyId
		)->where('is_locked', 1)->orderBy('updated_at', 'desc')->limit(5)->get();
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
	 * @param  InvoiceService  $invoiceService
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function create(InvoiceService $invoiceService)
	{
		return view('client.invoices.create', $invoiceService->getInvoiceFormData($this->company));
	}

	/**
	 * @param  Request  $request
	 * @param  InvoiceService  $invoiceService
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store(Request $request, InvoiceService $invoiceService)
	{
		$invoice = $invoiceService->saveInvoice($request, $this->company);

		if (strtolower($request->get('submit-name')) === 'create') {
			return redirect()->route('client.invoices.edit', $invoice->id)->with('success', true)
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
	public function show(Request $request, InvoiceService $invoiceService, $id)
	{

		if ($request->get('locale') == 'en') {
			app()->setLocale('en');
		} else {
			app()->setLocale('lv');
		}

		$settings = $this->company->settings->pluck('content', 'variable');
		$settingsTopMargin = $settings[CompanySetting::INVOICE_PRINT_TOP_MARGIN] ?? null;
		$settingsBottomMargin = $settings[CompanySetting::INVOICE_PRINT_BOTTOM_MARGIN] ?? null;
		$settingLeftMargin = $settings[CompanySetting::INVOICE_PRINT_LEFT_MARGIN] ?? null;
		$settingRightMargin = $settings[CompanySetting::INVOICE_PRINT_RIGHT_MARGIN] ?? null;

		if ($request->has('method') && $request->input('method') == 'delete') {
			return $this->destroy($id, $invoiceService);
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
		$partner = $invoice->partner->name ?? null;

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
	 * @param $id
	 * @param  InvoiceService  $invoiceService
	 * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
	 */
	public function edit($id, InvoiceService $invoiceService)
	{
//	    dd($invoiceService->getInvoiceFormData($this->company, $id));
		return view('client.invoices.edit', $invoiceService->getInvoiceFormData($this->company, $id));
	}

	/**
	 * @param  Request  $request
	 * @param  InvoiceService  $invoiceService
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update(Request $request, InvoiceService $invoiceService, int $id)
	{
		$invoiceService->saveInvoice($request, $this->company, $id);

		if (strtolower($request->get('submit-name')) === 'save') {
			return redirect()->route('client.invoices.edit', $id)->with('success', true)
				->with('form_message', _('Invoice is updated successfully'));
		}

		return redirect()->route('client.invoices.index')->with('success', true)
			->with('form_message', _('Invoice is updated successfully'));
	}

	/**
	 * @param $id
	 * @param  InvoiceService  $invoiceService
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function destroy($id, InvoiceService $invoiceService)
	{
		$invoiceService->deleteInvoice($this->company, $id);

		return redirect()->route('client.invoices.index')->with('success', true)
			->with('form_message', _('Invoice is deleted successfully'));

	}

	public function lockInvoice($id, InvoiceService $invoiceService)
	{
		$invoiceService->lockInvoice($id);

		return redirect()->back();
	}

	public function unlockInvoice($id)
	{
		$invoice = Invoice::find($id);
		$invoice->is_locked = 0;
		$invoice->save();

		return redirect()->back();
	}

	public function copyInvoice($id, InvoiceService $invoiceService)
	{
		$invoiceService->copy($id);

		return redirect()->back()->with('success', true)->with(
			'form_message', _('Invoice is copied successfully')
		);

	}
}
