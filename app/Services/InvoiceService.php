<?php


namespace App\Services;


use App\Company;
use App\CompanyBank;
use App\CompanyVatNumber;
use App\Currency;
use App\Invoice;
use App\InvoiceAdvancePayment;
use App\InvoiceLine;
use App\InvoiceType;
use App\Partner;
use App\Repositories\Invoice\InvoiceRepository;
use App\Unit;
use App\Vat;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class InvoiceService
{

	private $invoices;

	public function __construct(InvoiceRepository $invoices)
	{
		$this->invoices = $invoices;
	}

	/**
	 * @param  Company  $company
	 * @param $invoiceId
	 * @return array
	 */
	public function getInvoiceFormData(Company $company, $invoiceId = null): array
	{
		$invoice = Invoice::with(['invoiceLines', 'invoiceAdvancePayments'])->where(
			'company_id', $company->id
		)->find($invoiceId);

		$partners = $company->partners->pluck('name', 'id');

		$currencies = Currency::get()->pluck('name', 'id');
		$bank = CompanyBank::where('company_id', $company->id)->get()
			->map(function ($record) {
				$record->payment_receiver = $record->payment_receiver.'  ['.$record->comment. '] | '.$record->bank.' | '.$record->account_number;
				return $record;
			})
			->pluck('payment_receiver', 'id');

		$units = Unit::orderBy('default', 'desc')->orderBy('name', 'asc')->get();

		$structuralunits = $this->invoices->getStructuralunits();

		$invoicetypes = InvoiceType::get();

		$selectedBank = null;

		if($invoice){
			$selectedBank = CompanyBank::where('payment_receiver', $invoice['payment_receiver'])
				->where('bank', $invoice['bank'])
				->where('swift', $invoice['swift'])
				->where('account_number', $invoice['account_number'])->first();
		}

		$vats = Vat::orderBy('default', 'desc')->orderBy('name', 'asc')->get();;

		$companyVatNumbers = CompanyVatNumber::where(
			'company_id', $company->id
		)->get();

		return [
			'invoice' => $invoice,
			'partners' => $partners,
			'currencies' => $currencies,
			'vats' => $vats,
			'bank' => $bank,
			'units' => $units,
			'selectedBank' => $selectedBank,
			'companyVatNumbers' => $companyVatNumbers,
			'structuralunits' => $structuralunits,
			'invoicetypes' => $invoicetypes,
		];
	}

	public function saveInvoice(Request $request, Company $company, $invoiceId = null): Invoice
	{
		$data = $request->all();

		$data['company_id'] = $company->id;

		if (isset($data['bank_id']) && $data['bank_id']) {

			$selectedOptionalBank = CompanyBank::where('company_id', $company->id)->find($data['bank_id']);
			$data['payment_receiver'] = $selectedOptionalBank['payment_receiver'];
			$data['bank'] = $selectedOptionalBank['bank'];
			$data['swift'] = $selectedOptionalBank['swift'];
			$data['account_number'] = $selectedOptionalBank['account_number'];

		} else {
			// default data!
			$data['payment_receiver'] = $company->title;
			$data['bank'] = $company->bank;
			$data['swift'] = $company->swift;
			$data['account_number'] = $company->account_number;
		}

		if ($invoiceId) {
			$invoice = Invoice::where('company_id', $data['company_id'])->find($invoiceId);
		} else {
			$invoice = new Invoice;
			$invoice->company_id = $company->id;
			$invoice->save();
		}

		if ($partner = $company->partners->where('id', $data['partner_id'])->first()) {
			$data['partner_name'] = $partner->name;
			$data['partner_address'] = $partner->address;
			$data['partner_registration_number'] = $partner->registration_number;
			$data['partner_vat_number'] = $partner->vat_number;
			$data['partner_bank'] = $partner->bank;
			$data['partner_swift'] = $partner->swift;
			$data['partner_account_number'] = $partner->account_number;
		}

		$invoice->update($data);

		foreach ($data['title'] as $key => $val) {
			if ($val) {

				$array = [];
				$array['code'] = substr(($data['code'][$key] ?? ''), 0, 15);
				$array['title'] = $data['title'][$key] ?? null;
				$array['unit_id'] = $data['unit_id'][$key] ?? null;
				$array['price'] = $data['price'][$key] ?? null;
				$array['quantity'] = $data['quantity'][$key] ?? null;
				$array['vat_id'] = $data['vat_id'][$key] ?? null;

				$array['invoice_id'] = $invoice->id;
				$array['currency_id'] = $invoice->currency_id;

				if ($data['line_id'][$key]) {
					$lineId = $data['line_id'][$key];
					$invoiceLinesIdsExist[] = $lineId;// none existing lines will be deleted after update
					$invoiceLine = InvoiceLine::find($lineId);
					$invoiceLine->update($array);
				} else {
					$invoiceLine = new InvoiceLine();
					$invoiceLine->create($array);
					$invoiceLinesIdsExist[] = $invoiceLine->id;
				}
			}
		}

		$deletedInvoiceLines = InvoiceLine::where('invoice_id', $invoice->id)
			->whereNotIn('id', isset($invoiceLinesIdsExist) && $invoiceLinesIdsExist ? $invoiceLinesIdsExist : []);

		$deletedInvoiceLines->delete();

		InvoiceAdvancePayment::where('invoice_id', $invoiceId)->delete();

		foreach($data['prePaymentAmount'] ?? [] as $index => $prePaymentAmount){


            $prePaymentAmount = floatval($prePaymentAmount);

            $date = $data['prePaymentDate'][$index] ?? null;


            if($prePaymentAmount && $date){

                try {
                    $date = Carbon::createFromFormat('d.m.Y', $date)->format('d.m.Y');
                    $prePaymentLine = new InvoiceAdvancePayment;
                    $prePaymentLine->amount = ROUND($prePaymentAmount, 2);
                    $prePaymentLine->date = $date;
                    $prePaymentLine->invoice_id = $invoiceId;
                    $prePaymentLine->save();
                } catch (\Exception $e){

                }
            }
        }

		$this->calculateTotalInvoiceAmount($invoice->id);

		return $invoice;
	}


	public function calculateTotalInvoiceAmount($id): void
	{
		$total = [];
		$vats = Vat::get();
		foreach ($vats as $key => $vat) {
			$total[$vat->id] = 0;
			$total['rate'][$vat->id] = $vat->rate;
		}

		$invoiceLines = InvoiceLine::with('vat')->where('invoice_id', $id)->get();

		foreach ($invoiceLines as $key => $line) {
			$total[$line->vat_id] += round($line->quantity * $line->price, 2);
		}

		$vats = Vat::get();
		$invoiceTotal = 0;
		foreach ($vats as $key => $vat) {
			$invoiceTotal += $total[$vat->id] + ROUND($total[$vat->id] * $total['rate'][$vat->id], 2);
		}

		$invoice = Invoice::find($id);
		$invoice->amount_total = $invoiceTotal;
		$invoice->save();
	}

	public function copy($invoiceId){
		$invoice = Invoice::find($invoiceId);

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
	}

	public function lockInvoice($invoiceId){
		$invoice = Invoice::find($invoiceId);
		/**
		 * @var $invoice Invoice
		 */
		$invoice->locker_user_id = Auth::user()->id;
		$invoice->is_locked = 1;
		$invoice->save();
	}

	public function deleteInvoice(Company $company, $invoiceId){
		$invoice = Invoice::with('invoiceLines')->where(
			'company_id', $company->id
		)->find($invoiceId);

		$invoice->invoiceLines()->delete();
		$invoice->delete();
	}

	public function fillInvoiceData(){

		Invoice::get()->each(function(Invoice $invoice){

			/*** @var $partner Partner */
			$partner = $invoice->partner;

			if(!$invoice->partner_name){
				$invoice->partner_name = $partner->name;
			}

			if(!$invoice->partner_address){
				$invoice->partner_address = $partner->address;
			}

			if(!$invoice->partner_registration_number){
				$invoice->partner_registration_number = $partner->registration_number;
			}

			if(!$invoice->partner_vat_number){
				$invoice->partner_vat_number = $partner->vat_number;
			}

			if(!$invoice->partner_bank){
				$invoice->partner_bank = $partner->bank;
			}

			if(!$invoice->partner_swift){
				$invoice->partner_swift = $partner->swift;
			}

			if(!$invoice->partner_account_number){
				$invoice->partner_account_number = $partner->account_number;
			}

			$invoice->save();
		});

	}
}