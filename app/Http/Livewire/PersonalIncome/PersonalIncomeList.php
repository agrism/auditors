<?php

namespace App\Http\Livewire\PersonalIncome;

use Livewire\Component;
use App\Services\AuthUser;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class PersonalIncomeList extends Component
{


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
        return view('livewire.personal-income.personal-income-list',
            ['someData' => []]);
    }

    public function boot(){
//        dd(1);
    }


}
