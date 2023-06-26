<?php

namespace App\Http\Livewire\Vacations;

use App\Services\VacationService;
use Livewire\Component;
use App\Services\AuthUser;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class VacationSummary extends Component
{
    public $activeEmployeeId = '';
    public $employeeId = '';

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

    public function employees(): array
    {
        return VacationService::factory()->registerCompany($this->companyId)->getSummaryPerCompany();
    }

    public function render()
    {
        return view('livewire.vacations.vacation-summary', ['someData' => []]);
    }

    public function setActiveEmployeeId($id)
    {
        $this->activeEmployeeId = $id;
    }

    public function boot()
    {
//        dd(1);
    }


}