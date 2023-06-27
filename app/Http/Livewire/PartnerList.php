<?php

namespace App\Http\Livewire;

use App\Company;
use App\Partner;
use App\Repositories\Invoice\InvoiceRepository;
use App\Services\AuthUser;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class PartnerList extends Component
{
    use WithPagination;

    public $filter = [
        'name' => null,
        'address' => null,
        'registration_number' => null,
    ];

    protected $paginationTheme = 'bootstrap';

    public $active = [
        'id' => null,
        'name' => null,
        'regNo' => null,
        'vatNo' => null,
        'address' => null,
        'bank' => null,
        'swift' => null,
        'accountNumber' => null,
    ];

    public $sortColumn = 'name';
    public $sortDirection = 'asc';

    private $partners;

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

        if (!$this->companyId = AuthUser::instance()->selectedCompanyId()) {
            return redirect('/');
        }

        $this->invoiceRepository = app()->make(InvoiceRepository::class);
        $this->invoiceService = app()->make(InvoiceService::class);
    }

    public function render()
    {
        return view('livewire.partner-list', ['partners' => $this->getPartners()]);
    }

    public function getQueryString()
    {
        return method_exists($this, 'queryString')
            ? $this->queryString()
            : $this->queryString;
    }

    public function openEdit($id)
    {
        if ($this->active['id'] === $id) {

            $this->clearActive();

            return;
        }

        $this->active['id'] = $id;

        if (!$partner = Partner::where('company_id', $this->companyId)->where('id', $id)->first()) {
            $this->clearActive();
            $this->dispatchBrowserEvent('openModal_handle_partner');
            return;
        }

        $this->active = [
            'id' => $partner->id,
            'name' => $partner->name,
            'address' => $partner->address,
            'regNo' => $partner->registration_number,
            'vatNo' => $partner->vat_number,
            'bank' => $partner->bank,
            'swift' => $partner->swift,
            'accountNumber' => $partner->account_number,
        ];

        $this->dispatchBrowserEvent('openModal_handle_partner');

    }

    public function sortBy($column)
    {
        $this->sortColumn = $column;
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        $this->partners = $this->getPartners();
    }

    public function getPartners()
    {

        $partners = DB::table('partners')->where('company_id', $this->companyId);

        if (in_array($this->sortColumn, ['name', 'address', 'registration_number'])) {
            $partners = $partners->orderBy($this->sortColumn, $this->sortDirection);
        }

        if ($value = $this->filter['name']) {
            $partners = $partners->where('name', 'like', '%'.$value.'%');
        }

        if ($value = $this->filter['address']) {
            $partners = $partners->where('address', 'like', '%'.$value.'%');
        }

        if ($value = $this->filter['registration_number']) {
            $partners = $partners->where('registration_number', 'like', '%'.$value.'%');
        }


        return $partners->paginate(15);
    }

    private function clearActive(): self
    {
        $this->active = [
            'id' => null,
            'name' => null,
            'regNo' => null,
            'vatNo' => null,
            'address' => null,
            'bank' => null,
            'swift' => null,
            'accountNumber' => null,
        ];

        return $this;
    }

    public function savePartnerConfirm()
    {
        $this->validate([
            'active.name' => 'required',
            'active.regNo' => 'required',
        ]);

        if (!$this->active['id']) {
            $partner = new Partner();
            $partner->company_id = $this->companyId;
        } elseif (!$partner = Partner::where('company_id', $this->companyId)->where('id',
            $this->active['id'])->first()) {
            return $this->clearActive();
        }

        $partner->name = $this->active['name'];
        $partner->registration_number = $this->active['regNo'];
        $partner->vat_number = $this->active['vatNo'];
        $partner->address = $this->active['address'];
        $partner->bank = $this->active['bank'];
        $partner->swift = $this->active['swift'];
        $partner->account_number = $this->active['accountNumber'];

        $partner->save();

        $this->dispatchBrowserEvent('closeModal_handle_partner');
    }

    public function savePartnerCancel()
    {
        $this->dispatchBrowserEvent('closeModal_handle_partner');
    }

    public function clearFilterForm()
    {
        $this->filter = [
            'name' => null,
            'address' => null,
            'registration_number' => null,
        ];
    }

}
