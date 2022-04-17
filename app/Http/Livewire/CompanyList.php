<?php

namespace App\Http\Livewire;

use App\Services\AuthUser;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class CompanyList extends Component
{
    public function __construct($id = null)
    {
        parent::__construct($id);
    }


    public function render()
    {
        return view('livewire.company-list', ['companies' => $this->companies()]);
    }


    public function setActiveCompanyId($id){
        session()->forget('companyId');
        session()->put('companyId', $id);
        session()->save();
        $this->emit('changeActiveCompany', $id);
    }

    /**
     * @return Collection[Company]
     */
    private function companies(): Collection
    {
        if(!Auth::user()){
            return new Collection();
        }

        if(!$user = User::with(
            [
                'companies' => function ($q) {
                    $q->orderBy('title', 'asc');
                },
            ]
        )->find(Auth::user()->id)){
            $user = new User;
        }

        return $user->companies;
    }

    public function mount(AuthUser $authUser){
        if(!$authUser->isLoggedIn()){
            return redirect('/');
        }
    }
}
