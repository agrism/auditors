<?php


namespace App\Http\Livewire\CashExpenses;

use App\Services\AuthUser;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class CashExpensesFormLine extends Component
{
    public $recordId;
    public $no;
    public $date;
    public $partnerId;
    public $description;
    public $amount;

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
    }

    public function render()
    {
        return view('livewire.cash-expenses.cash-expenses-form-line', []);
    }

    public function remove(){
        $this->emitUp('removeLine', $this->recordId);
    }
}