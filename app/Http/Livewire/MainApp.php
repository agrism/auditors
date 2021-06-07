<?php

namespace App\Http\Livewire;

use App\Services\AuthUser;
use Livewire\Component;

class MainApp extends Component
{

    protected $listeners = ['changeActiveCompany' => 'setActiveCompanyId'];

    public $activeCompanyId = 'x';

    private $nav = [
        'companies' => [
            'title' => 'My companies',
            'active' => true,
            'available' => true,
            'shouldAuth' => true,
            'shouldHaveSelectedCompany' => false,

        ],
        'partners' => [
            'title' => 'Partners',
            'active' => false,
            'available' => true,
            'shouldAuth' => true,
            'shouldHaveSelectedCompany' => true,
        ],
        'invoices' => [
            'title' => 'Invoices',
            'active' => false,
            'available' => true,
            'shouldAuth' => true,
            'shouldHaveSelectedCompany' => true,
        ],
        'personal-income' => [
            'title' => 'Personal Income',
            'active' => false,
            'available' => false,
            'shouldAuth' => true,
            'shouldHaveSelectedCompany' => true,
        ],
    ];

    public function nav()
    {
        $availableNav = [];

        foreach ($this->nav as $navSysName => $nav) {
            if ($nav['shouldAuth'] && !AuthUser::instance()->isLoggedIn()) {
                continue;
            }

            if ($nav['shouldHaveSelectedCompany'] && !AuthUser::instance()->selectedCompany()) {
                continue;
            }

            $availableNav[$navSysName] = $nav;
        }

        return $availableNav;
    }

    public function render()
    {
        return view('livewire.main-app')->layout('layouts.app-test');
    }

    public function setActiveCompanyId($id)
    {
        $this->activeCompanyId = $id;
    }

    public function activeComponent()
    {
        foreach ($this->nav as $sysName => $item) {
            if ($item['active']) {
                return $sysName;
            }
        }

        return null;
    }


    public function activateComponent(string $name)
    {

        if (!in_array($name, array_keys($this->nav))) {

            dd('wrong nav sys name: '.$name);
            return;
        }

        foreach ($this->nav as $navSysName => &$nav) {
            if ($navSysName !== $name) {
                $nav['active'] = false;
                continue;
            }

            if ($nav['shouldAuth'] && !AuthUser::instance()->isLoggedIn()) {
                continue;
            }

            if ($nav['shouldHaveSelectedCompany'] && !AuthUser::instance()->selectedCompany()) {
                continue;
            }

            $nav['active'] = true;
        }
    }

    public function mount()
    {

        foreach ($this->nav as $navSysName => &$nav) {
            if ($navSysName === 'personal-income') {
                $nav['available'] = config('app.debug-available');
            }
        }
    }
}
