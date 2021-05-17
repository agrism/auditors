<?php

namespace App\Http\Livewire;

use App\Company;
use App\Repositories\Invoice\InvoiceRepository;
use App\Services\InvoiceService;
use Livewire\Component;
use Livewire\WithPagination;

class InvoiceList extends Component
{
    use WithPagination;

    protected $listeners = ['parentAction', 'closeInvoice'];

    protected $paginationTheme = 'bootstrap';

    public $showModal = false;

    public $showInvoiceFom = false;

    public $activeInvoiceId;
    public $invoicePrintLocale;
    public $invoicePrintFormat;

    private $invoices;
    public $partners;
    public $params;
    public $structuralunits;
    public $invoicetypes;
    public $filter = [
        'partnerId' => null,
        'details' => null,
        'typeId' => null,
        'structId' => null,
        'dateFrom' => null,
        'dateTo' => null,
    ];

    public $sortColumn = 'date';
    public $sortDirection = 'desc';

    private $invoiceRepository;
    private $invoiceService;

    private $company;
    private $companyId;


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



    public function mount(InvoiceRepository $invoiceRepository)
    {

    }

    public function render()
    {
        $this->invoices = $this->getInvoices();
        $this->partners = $this->invoiceRepository->getPartners();;
        $this->params = [];
        $this->structuralunits = $this->invoiceRepository->getStructuralunits();
        $this->invoicetypes = $this->invoiceRepository->getInvoicetypes();

        return view('livewire.invoice-list', ['invoices' => $this->invoices]);
    }

    public function openNewInvoice(){
        $this->activeInvoiceId = null;
        $this->showInvoiceFom = true;
    }

    public function setActiveInvoiceId($id){
        if($this->activeInvoiceId === $id){
            $this->activeInvoiceId = null;
            return;
        }
        $this->activeInvoiceId = $id;
    }

    public function setInvoicePrintLocale($locale = 'lv'){
        $this->invoicePrintLocale = $locale == 'en' ? 'en' : 'lv';
    }

    public function setInvoicePrintFormat($format = 'html'){
        $this->invoicePrintFormat = $format == 'pdf' ? 'pdf' : 'html';
    }

    public function filterForm()
    {
        $this->resetPage();

        $this->invoices = $this->getInvoices();
    }

    public function clearFilterForm(){
        foreach ($this->filter as &$value){
            $value = null;
        }
    }

    public function sortBy($column){
        $this->sortColumn = $column;
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        $this->invoices = $this->getInvoices();
    }

    public function openModal($invoiceId){
        $this->showModal = true;
    }

    public function parentAction(){
        $this->activeInvoiceId = 9999;
        $this->showModal = false;
    }

    public function closeInvoice(){
        $this->showInvoiceFom = false;
    }

    public function copyInvoice(){
        $this->invoiceService->copy($this->company, $this->activeInvoiceId);
    }

    public function deleteInvoice(){
        sleep(2);
//        $this->invoiceService->deleteInvoice($this->company, $this->activeInvoiceId);
    }

    public function updatingFilterPartnerId(){
        $this->resetPage();

        $this->invoices = $this->getInvoices();
    }

    private function getInvoices(){
        $this->invoiceRepository->partnerId = $this->filter['partnerId'];
        $this->invoiceRepository->details = $this->filter['details'];
        $this->invoiceRepository->typeId = $this->filter['typeId'];
        $this->invoiceRepository->structId = $this->filter['structId'];
        $this->invoiceRepository->dateFrom = $this->filter['dateFrom'];
        $this->invoiceRepository->dateTo = $this->filter['dateTo'];

        $this->invoiceRepository->sortColumn = $this->sortColumn;
        $this->invoiceRepository->sortDirection = $this->sortDirection;

        return $this->invoiceRepository->getInvoices([]);
    }


}
