<?php

namespace App\Http\Livewire;

use App\Budget;
use Livewire\Component;
use App\Services\AuthUser;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BudgetSelect extends Component
{

    /** @var Collection */
    public $budgets;
    public $selectedBudgetId;
    public $selectedBudgetName;
    public $selectedBudgetCode;

    private     $company;
    private int $companyId;

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
        $budget = DB::table('budgets')
            ->where('company_id',
                $this->companyId)
            ->whereId($id)
            ->first();

        $this->selectedBudgetId   = $budget->id ?? '';
        $this->selectedBudgetName = $budget->name ?? '';
        $this->selectedBudgetCode = $budget->code ?? '';

        $this->dispatchBrowserEvent('budget_modal_open');
    }

    public function cancel()
    {
        $this->dispatchBrowserEvent('budget_modal_close');
    }

    public function save()
    {
        $this->validate([
            'selectedBudgetName' => 'required',
            'selectedBudgetCode' => 'required',
        ]);

        if ( !$budget = Budget::whereId($this->selectedBudgetId)
            ->whereCompanyId($this->companyId)
            ->first()
        ) {
            $budget             = new Budget;
            $budget->company_id = $this->companyId;
        }
        $budget->name = $this->selectedBudgetName;
        $budget->code = $this->selectedBudgetCode;
        $budget->save();

        $this->selectedBudgetId = $budget->id;
        $this->budgets          = $this->budgets();

        $this->dispatchBrowserEvent('budget_modal_close');
        $this->updatedSelectedBudgetId();
    }

    private function budgets()
    {
        $budgets = DB::table('budgets')
            ->where('company_id',
                $this->companyId)
            ->orderBy('order','asc')
            ->orderBy('code','asc')
            ->get()
            ->map(function ($p)
            {
                return [
                    'name' => $p->name,
                    'code' => $p->code . '-' .$p->name,
                    'id'   => $p->id,
                ];
            });

        $budgets->prepend([
            'code' => '-',
            'id'   => null,
        ]);

        return $budgets->toArray();
    }

    public function updatedSelectedBudgetId()
    {
        $this->emit('updatedSelectedBudgetId',
            $this->selectedBudgetId);
    }

    public function mount()
    {
        $this->budgets = $this->budgets();
    }

    public function render()
    {
        return view('livewire.budget-select');
    }
}