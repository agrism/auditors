<?php

namespace App\Http\Livewire;

use App\Account;
use Livewire\Component;
use App\Services\AuthUser;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AccountSelect extends Component
{

    /** @var Collection */
    public $accounts;
    public $selectedAccountId;
    public $selectedAccountName;
    public $selectedAccountCode;

    private $company;
    /** @var int */
    private $companyId;

    public function __construct($id = null)
    {
        parent::__construct($id);

        if ( !AuthUser::instance()
            ->isLoggedIn()
        ) {
            return redirect('/');
        }

        if ( !$this->company = AuthUser::instance()
            ->selectedCompany()
        ) {
            return redirect('/');
        }

        if ( !$this->companyId = AuthUser::instance()
            ->selectedCompanyId()
        ) {
            return redirect('/');
        }
    }

    public function edit($id = null)
    {
        $account = DB::table('accounts')
            ->where('company_id',
                $this->companyId)
            ->whereId($id)
            ->first();

        $this->selectedAccountId   = $account->id ?? '';
        $this->selectedAccountName = $account->name ?? '';
        $this->selectedAccountCode = $account->code ?? '';

        $this->dispatchBrowserEvent('account_modal_open');
    }

    public function cancel()
    {
        $this->dispatchBrowserEvent('account_modal_close');
    }

    public function save()
    {
        $this->validate([
            'selectedAccountName' => 'required',
            'selectedAccountCode' => 'required',
        ]);

        if ( !$budget = Account::whereId($this->selectedAccountId)
            ->whereCompanyId($this->companyId)
            ->first()
        ) {
            $budget             = new Account;
            $budget->company_id = $this->companyId;
        }
        $budget->name = $this->selectedAccountName;
        $budget->code = $this->selectedAccountCode;
        $budget->save();

        $this->selectedAccountId = $budget->id;
        $this->accounts          = $this->accounts();

        $this->dispatchBrowserEvent('account_modal_close');
        $this->updatedSelectedAccountId();
    }

    private function accounts()
    {
        $budgets = DB::table('accounts')
            ->where('company_id',
                $this->companyId)
            ->orderBy('order',
                'asc')
            ->orderBy('code',
                'asc')
            ->get()
            ->map(function ($p)
            {
                return [
                    'name' => $p->name,
                    'code' => $p->code . ' - ' . $p->name,
                    'id'   => $p->id,
                ];
            });

        $budgets->prepend([
            'name' => '-',
            'code' => '-',
            'id'   => null,
        ]);

        return $budgets->toArray();
    }

    public function updatedSelectedAccountId()
    {
        $this->emit('updatedSelectedAccountId',
            $this->selectedAccountId);
    }

    public function mount()
    {
        $this->accounts = $this->accounts();
    }

    public function render()
    {
        return view('livewire.account-select');
    }
}