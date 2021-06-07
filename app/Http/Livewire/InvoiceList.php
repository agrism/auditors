<?php

namespace App\Http\Livewire;

use App\Company;
use App\Exports\InvoiceExport;
use App\Repositories\Invoice\InvoiceRepository;
use App\Services\AuthUser;
use App\Services\InvoiceService;
use App\Vat;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class InvoiceList extends Component
{
    use WithPagination;

    protected $listeners = ['parentAction', 'closeInvoice', 'updatedSelectedId'];

    protected $paginationTheme = 'bootstrap';

    public $showModal = false;

    public $showInvoiceFom = false;

    public $activeInvoiceId;
    public $activeInvoiceNo;
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

        if (!AuthUser::instance()->isLoggedIn()) {
            return redirect('/');
        }

        if (!$this->company = AuthUser::instance()->selectedCompany()) {
            return redirect('/');
        }

        $this->invoiceRepository = app()->make(InvoiceRepository::class);
        $this->invoiceService = app()->make(InvoiceService::class);

        $this->shortcutInvoice['date'] = Carbon::now()->format('d.m.Y');

    }


    public $shortcutInvoice = [
        'date' => null,
        'number' => null,
        'partnerId' => null,
        'details' => null,
        'amountWithoutVat' => null,
        'vatId' => null,
        'amountVat' => null,
        'amountWithVat' => null,
        'typeId' => null,
        'structId' => null,
        'vatRates' => [],
    ];


    public function getQueryString()
    {
        return method_exists($this, 'queryString')
            ? $this->queryString()
            : $this->queryString;
    }


    public function mount(InvoiceRepository $invoiceRepository)
    {

    }

    public function render()
    {
        $this->invoices = $this->getInvoices();
        $this->partners = $this->invoiceRepository->getPartners();
        if (!$this->shortcutInvoice['partnerId'] && $first = $this->partners->first() ?? null) {
            $this->shortcutInvoice['partnerId'] = $first->id ?? null;
        }
        $this->params = [];
        $this->structuralunits = $this->invoiceRepository->getStructuralunits();
        $this->invoicetypes = $this->invoiceRepository->getInvoicetypes();

        return view('livewire.invoice-list', ['invoices' => $this->invoices]);
    }

    public function openNewInvoice()
    {
        $this->activeInvoiceId = null;
        $this->showInvoiceFom = true;
    }

    public function setActiveInvoiceId($id)
    {
        if ($this->activeInvoiceId === $id) {
            $this->activeInvoiceId = null;
            return;
        }
        $this->activeInvoiceId = $id;
    }

    public function setInvoicePrintLocale($locale = 'lv')
    {
        $this->invoicePrintLocale = $locale == 'en' ? 'en' : 'lv';
    }

    public function setInvoicePrintFormat($format = 'html')
    {
        $this->invoicePrintFormat = $format == 'pdf' ? 'pdf' : 'html';
    }

    public function filterForm()
    {
        $this->resetPage();

        $this->invoices = $this->getInvoices();
    }

    public function clearFilterForm()
    {
        foreach ($this->filter as &$value) {
            $value = null;
        }
    }

    public function sortBy($column)
    {
        $this->sortColumn = $column;
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        $this->invoices = $this->getInvoices();
    }

    public function parentAction()
    {
        $this->activeInvoiceId = 9999;
    }

    public function closeInvoice()
    {
        $this->showInvoiceFom = false;
    }

    public function copyInvoice()
    {
        $this->activeInvoiceNo = "";
        if ($activeInvoice = $this->getActiveInvoiceModel()) {
            $this->activeInvoiceNo = $activeInvoice->number;
        }
        $this->dispatchBrowserEvent('openModal_copy_invoice');
    }

    public function copyInvoiceConfirm()
    {
        $this->invoiceService->copy($this->company, $this->activeInvoiceId);
        $this->dispatchBrowserEvent('closeModal_copy_invoice');
    }

    public function copyInvoiceCancel()
    {
        $this->dispatchBrowserEvent('closeModal_copy_invoice');
    }


    public function deleteInvoice()
    {
        $this->activeInvoiceNo = "";
        if ($activeInvoice = $this->getActiveInvoiceModel()) {
            $this->activeInvoiceNo = $activeInvoice->number;
        }
        $this->dispatchBrowserEvent('openModal_delete_invoice');
    }

    public function deleteInvoiceConfirm()
    {
        $this->invoiceService->deleteInvoice($this->company, $this->activeInvoiceId);
        $this->dispatchBrowserEvent('closeModal_delete_invoice');
    }

    public function deleteInvoiceCancel()
    {
        $this->dispatchBrowserEvent('closeModal_delete_invoice');
    }

    public function updatingFilterPartnerId()
    {
        $this->resetPage();

        $this->invoices = $this->getInvoices();
    }

    public function export()
    {
//        $invoices->load(['company', 'partner', 'invoiceLines', 'currency', 'invoiceType', 'structuralunit']);

        $invoices = collect($this->getInvoices()->all());

        return \Maatwebsite\Excel\Facades\Excel::download(new InvoiceExport($invoices), 'invoices.xlsx');
    }

    public function shortcutInvoiceOpen()
    {

        if (!$this->shortcutInvoice['vatRates']) {
            $this->shortcutInvoice['vatRates'] = Vat::select(['id', 'rate', 'name'])->get()->toArray();
        };

        if (!$this->shortcutInvoice['vatId']) {
            $this->shortcutInvoice['vatId'] = $this->shortcutInvoice['vatRates'][0]['id'] ?? null;
        }

        if ($first = $this->structuralunits->first() ?? null) {
            $this->shortcutInvoice['structId'] = $first['id'] ?? null;
        }

        if ($first = $this->invoicetypes->first() ?? null) {
            $this->shortcutInvoice['typeId'] = $first['id'] ?? null;
        }

        $this->dispatchBrowserEvent('openModal_shortcut_invoice');
    }

    public function shortcutInvoiceConfirm()
    {

        $this->validate([
            'shortcutInvoice.number' => 'required',
            'shortcutInvoice.date' => 'required',
            'shortcutInvoice.partnerId' => 'required',
            'shortcutInvoice.amountWithoutVat' => 'required',
            'shortcutInvoice.vatRates' => 'required',
        ]);

        $request = request();
        $request->request->add([
            'date' => $this->shortcutInvoice['date'],
            'payment_date' => $this->shortcutInvoice['date'],
            'number' => $this->shortcutInvoice['number'],
            'partner_id' => $this->shortcutInvoice['partnerId'],
            'details_self' => $this->shortcutInvoice['details'],
            'structuralunit_id' => $this->shortcutInvoice['structId'],
            'invoicetype_id' => $this->shortcutInvoice['typeId'],
            'title' => [
                $this->shortcutInvoice['details'],
            ],
            'unit_id' => [
                6,
            ],
            'price' => [
                $this->shortcutInvoice['amountWithoutVat'],
            ],
            'quantity' => [
                1,
            ],
            'vat_id' => [
                $this->shortcutInvoice['vatId'],
            ],
            'currency_id' => 1,
            'currency_rate' => 1,
        ]);

        $this->invoiceService->saveInvoice($request, $this->company);

        $this->shortcutInvoice = (new self())->shortcutInvoice;

        $this->dispatchBrowserEvent('closeModal_shortcut_invoice');
    }


    public function updatedSelectedId($param)
    {
//        dd($param);
        $this->shortcutInvoice['partnerId'] = $param;
    }

    public function shortcutInvoiceCancel()
    {
        $this->dispatchBrowserEvent('closeModal_shortcut_invoice');
    }

    public function updatedShortcutInvoiceAmountWithoutVat()
    {
        $rate = $this->getVatRateById($this->shortcutInvoice['vatId']);

        $this->shortcutInvoice['amountVat'] = ROUND($this->shortcutInvoice['amountWithoutVat'] * $rate, 2);
        $this->shortcutInvoice['amountWithVat'] = ROUND($this->shortcutInvoice['amountWithoutVat'] + $this->shortcutInvoice['amountVat'],
            2);
    }

    public function updatedShortcutInvoiceAmountWithVat()
    {
        $rate = $this->getVatRateById($this->shortcutInvoice['vatId']);

        $this->shortcutInvoice['amountWithoutVat'] = ROUND($this->shortcutInvoice['amountWithVat'] / (1 + $rate), 2);
        $this->shortcutInvoice['amountVat'] = ROUND($this->shortcutInvoice['amountWithVat'] - $this->shortcutInvoice['amountWithoutVat'],
            2);
    }

    public function updatedShortcutInvoiceVatId()
    {

        $rate = $this->getVatRateById($this->shortcutInvoice['vatId']);

        if ($this->shortcutInvoice['amountWithoutVat']) {
            $this->shortcutInvoice['amountWithoutVat'] = round($this->shortcutInvoice['amountWithoutVat'], 2);
            $this->shortcutInvoice['amountVat'] = ROUND($this->shortcutInvoice['amountWithoutVat'] * $rate, 2);
            $this->shortcutInvoice['amountWithVat'] = ROUND($this->shortcutInvoice['amountWithoutVat'] + $this->shortcutInvoice['amountVat'],
                2);
            return;
        }

        $this->shortcutInvoice['amountWithoutVat'] = ROUND($this->shortcutInvoice['amountWithVat'] / (1 + $rate), 2);
        $this->shortcutInvoice['amountVat'] = ROUND($this->shortcutInvoice['amountWithVat'] - $this->shortcutInvoice['amountWithoutVat'],
            2);
    }

    private function getVatRateById($id): float
    {

        $rate = 0;

        foreach ($this->shortcutInvoice['vatRates'] as $record) {
            if ($record['id'] == $id) {
                $rate = $record['rate'];
                break;
            }
        }

        return floatval($rate);
    }

    private function getInvoices()
    {
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

    private function getActiveInvoiceModel()
    {
        return $this->invoiceService->getInvoice($this->company, $this->activeInvoiceId);
    }


}
