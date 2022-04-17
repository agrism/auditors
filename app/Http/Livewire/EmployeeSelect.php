<?php

namespace App\Http\Livewire;

use App\Company;
use App\Employee;
use App\Services\AuthUser;
use Illuminate\Support\Collection;
use Livewire\Component;

class EmployeeSelect extends Component
{

    /**
     * @var Collection
     */
	public $employees;
	public $selectedEmployeeId;
	public $selectedEmployeeName;
	public $selectedEmployeeRegNo;
	public $selectedEmployeeRole;

	private $company;
	private int $companyId;

	public function __construct($id = null)
	{
		parent::__construct($id);

        if(!AuthUser::instance()->isLoggedIn()){
            return redirect('/');
        }

        if(!$this->company = AuthUser::instance()->selectedCompany()){
            return redirect('/');
        }

        if(!$this->companyId = AuthUser::instance()->selectedCompanyId()){
            return redirect('/');
        }
	}

	public function edit($id = null){
		$employee = Employee::where('company_id', $this->companyId)->whereId($id)->first();

		$this->selectedEmployeeId = $employee->id ?? '';
		$this->selectedEmployeeName = $employee->name ?? '';
		$this->selectedEmployeeRegNo = $employee->registration_number ?? '';
		$this->selectedEmployeeRole = $employee->role ?? '';

		$this->dispatchBrowserEvent('employee_modal_open');
	}

	public function cancel(){
	    $this->dispatchBrowserEvent('employee_modal_close');
    }

	public function save(){

		$this->validate([
			'selectedEmployeeName' => 'required',
			'selectedEmployeeRegNo' => 'required',
		]);

		if(!$employee = Employee::whereId($this->selectedEmployeeId)->whereCompanyId($this->companyId)->first()){
			$employee = new Employee;
			$employee->company_id = $this->companyId;
		}
		$employee->name = $this->selectedEmployeeName;
		$employee->registration_number = $this->selectedEmployeeRegNo;
		$employee->role = $this->selectedEmployeeRole;
		$employee->save();

		$this->selectedEmployeeId = $employee->id;
		$this->employees = $this->employees();

		$this->dispatchBrowserEvent('employee_modal_close');

		$this->updatedSelectedEmployeeId();

	}

	public function updatedSelectedEmployeeId(){
        $this->emit('updatedSelectedEmployeeId', $this->selectedEmployeeId);
    }


	public function mount(){
		$this->employees = $this->employees();
	}

    public function render()
    {
        return view('livewire.employee-select');
    }

    private function employees(){
		$employees =  Employee::where('company_id', $this->companyId)
			->orderBy('name', 'asc')
			->get()->map(function($p){
			return [
				'name' => $p->name,
				'id' => $p->id,
			];
		});

		$employee = new Employee;
		$employee->name = '-';
		$employee->id = null;
		$employees->prepend($employee);

		return $employees;
	}
}