<?php

namespace App\Http\Livewire;

use App\Company;
use App\Http\Requests\Request;
use App\InvoiceAdvancePayment;
use App\InvoiceLine;
use Illuminate\Support\Collection;
use App\Repositories\Invoice\InvoiceRepository;
use App\Services\AuthUser;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class InvoiceForm extends Component
{

    public $invoice = null;

    public $tempId = null;

    public $invoiceLines = [];
    public $invoiceAdvancePayments = [];

    public $goToListAfterSave = true;
    public $goToListWithoutSave = false;

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

        if(!AuthUser::instance()->isLoggedIn()){
            return redirect('/');
        }

        if(!$this->company = AuthUser::instance()->selectedCompany()){
            return redirect('/');
        }

        $this->invoiceRepository = app()->make(InvoiceRepository::class);
        $this->invoiceService = app()->make(InvoiceService::class);
    }

    public function render()
    {
        return view('livewire.invoice-form');
    }

    public function saveInvoice($formData): void
    {
        if($this->goToListWithoutSave){
            $this->goToListWithoutSave = false;
            $this->emit('closeInvoice');
            $this->dispatchBrowserEvent('contentChanged');
            return;
        }


        $this->save($formData);

        if ($this->goToListAfterSave) {
            $this->emit('closeInvoice');
            $this->dispatchBrowserEvent('contentChanged');
            return;
        }

        $this->goToListAfterSave = true;

        $this->refreshData();

        $this->render();

        $this->dispatchBrowserEvent('contentChanged');
    }

    public function mount(InvoiceRepository $invoiceRepository, InvoiceService $invoiceService)
    {
        $this->tempId = uniqid();
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

        Log::debug('refreshData');

        $this->invoice = $r['invoice'];

        // $this->invoice['vat_number'] = 1;

        Log::debug('---: '. json_encode($r['companyVatNumbers']->first()));

        if($r['companyVatNumbers'] instanceof Collection){
            Log::debug('collection');

            if($first = $r['companyVatNumbers']->first()){

                Log::debug($first->vat_number);
                if($this->invoice && isset($this->invoice['vat_number']) && $this->invoice['vat_number'] === null){
                    $this->invoice['vat_number'] = $first->vat_number ?? null;
                }
                Log::debug(json_encode($this->invoice));
            }
        }

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

        // Log::debug('$request0', $request->all());
        // Log::debug('------------------------------------------------------');

        $request->request->add($newData);

        // Log::debug('$request', $request->all());

        $this->invoiceService->saveInvoice($request, $this->company, $this->invoiceId);
    }

}