<?php

namespace App\Http\Livewire;

use App\Company;
use App\Http\Requests\Request;
use App\InvoiceAdvancePayment;
use App\InvoiceLine;
use App\Repositories\Invoice\InvoiceRepository;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class InvoiceForm extends Component
{

    public $invoice = null;

    public $invoiceLines = [];
    public $invoiceAdvancePayments = [];

    public $goToListAfterSave = true;

    public $currencies = [];
    public $bank = [];
    public $units = [];
    public $vats = [];
    public $selectedBank = null;
    public $companyVatNumbers = null;
    public $structuralunits = null;
    public $invoicetypes = null;
    public $invoiceId = null;

    public $company = null;

    private $invoiceRepository;
    private $invoiceService;

    public function __construct($id = null)
    {
        parent::__construct($id);

        $this->invoiceRepository = app()->make(InvoiceRepository::class);
        $this->invoiceService = app()->make(InvoiceService::class);

        $this->company = app()->Company;

        if (request()->session()->has('companyId')) {
            $this->companyId = request()->session()->get('companyId');

            if (!$this->company = Company::where('id', $this->companyId)->first()) {
                $this->companyId = null;
            }
        }
    }

    public function render()
    {
        return view('livewire.invoice-form');
    }

    public function saveInvoice($formData): void
    {
        $this->save($formData);

        if ($this->goToListAfterSave) {
            $this->emit('closeInvoice');
            $this->dispatchBrowserEvent('contentChanged');
            return;
        }

        $this->goToListAfterSave = true;

        $this->refreshData();

        $this->dispatchBrowserEvent('contentChanged');
    }

    public function mount(InvoiceRepository $invoiceRepository, InvoiceService $invoiceService)
    {
        $this->invoiceRepository = $invoiceRepository;
        $this->invoiceService = $invoiceService;

        $this->refreshData();
    }

    private function refreshData()
    {

        $this->company = app()->Company;

        if (request()->session()->has('companyId')) {
            $this->companyId = request()->session()->get('companyId');

            if (!$this->company = Company::where('id', $this->companyId)->first()) {
                $this->companyId = null;
            }
        }

        $this->units = collect([]);
        $this->vats = collect([]);

        $r = $this->invoiceService->getInvoiceFormData($this->company, $this->invoiceId);

        $this->invoice = $r['invoice'];

        $this->invoiceLines = [];

        foreach ($r['invoice']->invoiceLines ?? [] as $line) {
            /**
             * @var $line InvoiceLine
             */
            $this->invoiceLines[] = $line;
        }

        $this->invoiceAdvancePayments = [];

        foreach ($r['invoice']->invoiceAdvancePayments ?? [] as $line) {
            /**
             * @var $line InvoiceAdvancePayment
             */
            $this->invoiceAdvancePayments[] = $line;
        }


        $this->currencies = $r['currencies'];
        $this->vats = $r['vats'];
        $this->bank = $r['bank'];
        $this->units = $r['units'];
        $this->selectedBank = $r['selectedBank'];
        $this->companyVatNumbers = $r['companyVatNumbers'];
        $this->structuralunits = $r['structuralunits'];
        $this->invoicetypes = $r['invoicetypes'];
    }

    private function save($formData)
    {
        $newData = [];

        foreach ($formData as $index => $value) {
            if (strpos($index, '[') !== false) {
                $matches = [];
                preg_match('/^[^\[]*/', $index, $matches);
                $newData[$matches[0]][] = $value;
                continue;
            }
            $newData[$index] = $value;
        }

        $request = request();

        $request->request->add($newData);

        $this->invoiceService->saveInvoice($request, $this->company, $this->invoiceId);
    }

}
