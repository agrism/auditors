<?php

namespace App\Http\Livewire\Other;

use App\Partner;
use App\CompanyBank;
use Livewire\Component;
use App\Services\AuthUser;
use Livewire\WithPagination;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\Invoice\InvoiceRepository;

class OtherPaymentReceivers extends Component
{
    use WithPagination;

    public $filter = [
        'payment_receiver' => null,
        'bank' => null,
        'swift' => null,
        'account_number' => null,
        'comment' => null,
    ];

    protected $paginationTheme = 'bootstrap';

    public $active = [
        'id' => null,
        'payment_receiver' => null,
        'bank' => null,
        'swift' => null,
        'account_number' => null,
        'comment' => null,
    ];

    public $sortColumn = 'payment_receiver';
    public $sortDirection = 'asc';

    private $paymentReceivers;

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
        return view('livewire.other.other-payment-receivers', ['paymentReceivers' => $this->get()]);
    }

    public function mount(){
        $this->paymentReceivers = $this->get();
    }

    public function sortBy($column)
    {
        $this->sortColumn = $column;
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        $this->partners = $this->get();
    }

    public function clearFilterForm()
    {
        $this->filter = [
            'payment_receiver' => null,
            'bank' => null,
            'swift' => null,
            'account_number' => null,
            'comment' => null,
        ];
    }

    private function get()
    {

        $partners = DB::table('companies_bank')->where('company_id', $this->companyId);

        if (in_array($this->sortColumn, ['payment_receiver', 'bank', 'swift', 'account_number', 'comment'])) {
            $partners = $partners->orderBy($this->sortColumn, $this->sortDirection);
        }

        if ($value = $this->filter['payment_receiver']) {
            $partners = $partners->where('payment_receiver', 'like', '%'.$value.'%');
        }

        if ($value = $this->filter['bank']) {
            $partners = $partners->where('bank', 'like', '%'.$value.'%');
        }

        if ($value = $this->filter['swift']) {
            $partners = $partners->where('swift', 'like', '%'.$value.'%');
        }

        if ($value = $this->filter['account_number']) {
            $partners = $partners->where('account_number', 'like', '%'.$value.'%');
        }

        if ($value = $this->filter['comment']) {
            $partners = $partners->where('comment', 'like', '%'.$value.'%');
        }


        return $partners->paginate(15);
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

        if (!$partner = CompanyBank::where('company_id', $this->companyId)->where('id', $id)->first()) {
            $this->clearActive();
            $this->dispatchBrowserEvent('openModal_handle_payment_receiver');
            return;
        }

        $this->active = [
            'id' => $partner->id,
            'payment_receiver' => $partner->payment_receiver,
            'bank' => $partner->bank,
            'swift' => $partner->swift,
            'account_number' => $partner->account_number,
            'comment' => $partner->comment,
        ];

        $this->dispatchBrowserEvent('openModal_handle_payment_receiver');

    }

    private function clearActive(): self
    {
        $this->active = [
            'id' => null,
            'payment_receiver' => null,
            'bank' => null,
            'swift' => null,
            'account_number' => null,
            'comment' => null,
        ];

        return $this;
    }

    public function savePaymentReceiverConfirm()
    {
        // $this->validate([
        //     'active.name' => 'required',
        //     'active.regNo' => 'required',
        // ]);

        if (!$this->active['id']) {
            $paymentReceiver = new CompanyBank;
            $paymentReceiver->company_id = $this->companyId;
        } elseif (!$paymentReceiver = CompanyBank::where('company_id', $this->companyId)->where('id',
            $this->active['id'])->first()) {
            return $this->clearActive();
        }

        $paymentReceiver->payment_receiver = $this->active['payment_receiver'];
        $paymentReceiver->bank = $this->active['bank'];
        $paymentReceiver->swift = $this->active['swift'];
        $paymentReceiver->account_number = $this->active['account_number'];
        $paymentReceiver->comment = $this->active['comment'];

        $paymentReceiver->save();

        $this->dispatchBrowserEvent('closeModal_handle_payment_receiver');
    }

    public function savePaymentReceiverCancel()
    {
        $this->dispatchBrowserEvent('closeModal_handle_payment_receiver');
    }

}