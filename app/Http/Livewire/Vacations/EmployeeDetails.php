<?php

namespace App\Http\Livewire\Vacations;

use App\Employee;
use App\Services\VacationService;
use Livewire\Component;
use App\Services\AuthUser;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class EmployeeDetails extends Component
{
    public $details = [];
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

    public function details(): array
    {
        return VacationService::factory()->registerCompany($this->companyId)->registerEmployee($this->employeeId)->getData();
    }

    public function getEmployeeName(): string
    {
        return Employee::where('company_id', $this->companyId)->where('id', $this->employeeId)->first()->name ?? 'x';
    }

    public function render()
    {
        return view('livewire.vacations.employee-details', ['someData' => []]);
    }

    public function boot()
    {
//        dd(1);
    }


}