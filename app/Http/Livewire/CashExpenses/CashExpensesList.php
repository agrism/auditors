<?php

namespace App\Http\Livewire\CashExpenses;

use Livewire\Component;
use App\Services\AuthUser;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class CashExpensesList extends Component
{
    public $filter = [
        'no'   => null,
        'date' => null,
        'name' => null,
    ];

    public $activeId = null;
    public $editMode = false;
    public $ignoreActiveId = false;

    use WithPagination;
    public $sortColumn    = 'date';
    public $sortDirection = 'desc';
    protected $paginationTheme = 'bootstrap';
    protected $listeners = [
        'closeForm',
    ];
    private $company;
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

    public function render()
    {
        return view('livewire.cash-expenses.cash-expenses-list',
            ['cashExpenses' => $this->getCashExpenses()]);
    }

    public function getCashExpenses()
    {
        $expenses = DB::table('cash_expenses as ce')
            ->select([
                'ce.id',
                'ce.no',
                'ce.date',
                'empl.name',
            ])
            ->leftJoin('employees as empl',
                'ce.employee_id',
                '=',
                'empl.id')
            ->where('ce.company_id',
                $this->companyId);

        if (
        in_array($this->sortColumn,
            [
                'name',
                'date',
                'no',
            ])
        ) {
            $expenses = $expenses->orderBy($this->sortColumn,
                $this->sortDirection);
        }

        if ($value = $this->filter['no']) {
            $expenses = $expenses->where('ce.no',
                'like',
                '%' . $value . '%');
        }

        if ($value = $this->filter['date']) {
            $expenses = $expenses->where('ce.date',
                'like',
                '%' . $value . '%');
        }

        if ($value = $this->filter['name']) {
            $expenses = $expenses->where('empl.name',
                'like',
                '%' . $value . '%');
        }

        return $expenses->paginate(15);
    }

    public function getQueryString()
    {
        return method_exists($this,
            'queryString')
            ? $this->queryString()
            : $this->queryString;
    }

    public function sortBy($column)
    {
        $this->sortColumn    = $column;
        $this->sortDirection = $this->sortDirection === 'asc'
            ? 'desc'
            : 'asc';
        $this->partners      = $this->getCashExpenses();
    }

    public function clearFilterForm()
    {
        $this->filter = [
            'no'   => null,
            'date' => null,
            'name' => null,
        ];
    }

    public function setActive($id)
    {
        $this->editMode = false;
        if ($this->activeId === $id) {
            $this->activeId = null;

            return;
        }
        $this->activeId = $id;
    }

    public function isEditMode() : bool
    {
        if($this->ignoreActiveId){
            return $this->editMode;
        }

        return $this->activeId && $this->editMode;
    }

    public function setEditMode()
    {
        if ( !$this->activeId) {
            $this->editMode = false;

            return;
        }
        $this->editMode = true;
    }

    public function closeForm()
    {
        $this->editMode = false;
    }

    public function new()
    {
        $this->activeId = null;
        $this->editMode = true;
        $this->ignoreActiveId = true;
    }


}