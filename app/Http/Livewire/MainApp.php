<?php

namespace App\Http\Livewire;

use App\Log;
use Livewire\Component;
use App\Services\AuthUser;
use Illuminate\Support\Arr;

class MainApp extends Component
{

    public    $activeCompanyId = 'x';
    protected $listeners       = ['changeActiveCompany' => 'setActiveCompanyId'];
    private   $nav             = [
        'companies'       => [
            'title'                     => 'My companies',
            'active'                    => true,
            'available'                 => true,
            'shouldAuth'                => true,
            'shouldHaveSelectedCompany' => false,

        ],
        'partners'        => [
            'title'                     => 'Partners',
            'active'                    => false,
            'available'                 => true,
            'shouldAuth'                => true,
            'shouldHaveSelectedCompany' => true,
        ],
        'invoices'        => [
            'title'                     => 'Invoices',
            'active'                    => false,
            'available'                 => true,
            'shouldAuth'                => true,
            'shouldHaveSelectedCompany' => true,
        ],
        'cash-expenses'   => [
            'title'                     => 'Cash expenses',
            'active'                    => false,
            'available'                 => true,
            'shouldAuth'                => true,
            'shouldHaveSelectedCompany' => true,
        ],
        'personal-income' => [
            'title'                     => 'Personal Income',
            'active'                    => false,
            'available'                 => false,
            'shouldAuth'                => true,
            'shouldHaveSelectedCompany' => true,
        ],
        'other'           => [
            'items' => [
                'company-data'            => [
                    'title'                     => 'Company data',
                    'active'                    => false,
                    'available'                 => true,
                    'shouldAuth'                => true,
                    'shouldHaveSelectedCompany' => true,
                ],
                'other-payment-receivers' => [
                    'title'                     => 'Other payment receivers',
                    'active'                    => false,
                    'available'                 => true,
                    'shouldAuth'                => true,
                    'shouldHaveSelectedCompany' => true,
                ],
                'settings'                => [
                    'title'                     => 'Settings',
                    'active'                    => false,
                    'available'                 => false,
                    'shouldAuth'                => true,
                    'shouldHaveSelectedCompany' => true,
                ],
            ],

        ],
        // 'help' =>[
        //     // 'items' => [
        //     //     'edit-create-partner'            => [
        //     //         'title'                     => 'Edit/Create partner',
        //     //         'active'                    => false,
        //     //         'available'                 => true,
        //     //         'shouldAuth'                => true,
        //     //         'shouldHaveSelectedCompany' => true,
        //     //     ],
        //     // ],
        // ],
    ];

    public function render()
    {
        foreach ($this->nav as $navSysName => &$nav) {
            if (
            in_array($navSysName,
                [
                    'personal-income',
                    // 'cash-expenses'
                ])
            ) {
                // $nav['available'] = config('app.debug-available');
                $nav['available'] = AuthUser::instance()
                        ->userId() === 9;
            }
        }

        return view('livewire.main-app')->layout('layouts.app-test');
    }

    public function setActiveCompanyId($id)
    {
        $this->activeCompanyId = $id;
    }

    public function activeComponent()
    {
        foreach ($this->nav() as $sysName => $item) {

            if ($item['active']) {

                return $sysName;
            }
        }

        return null;
    }

    public function nav()
    {
        $availableNav = [];

        $n = [];

        foreach ($this->nav as $mainKey => $val) {

            if (isset($val['items'])) {
                foreach ($val['items'] ?? [] as $subKey => $subVal) {
                    $n[$mainKey . '.' . $subKey] = $subVal;
                }
                continue;
            }

            $n[$mainKey] = $val;
        }

        foreach ($n as $navSysName => $nav) {
            if (
                $nav['shouldAuth'] && !AuthUser::instance()
                    ->isLoggedIn()
            ) {
                continue;
            }

            if (
                $nav['shouldHaveSelectedCompany'] && !AuthUser::instance()
                    ->selectedCompany()
            ) {
                continue;
            }

            $availableNav[$navSysName] = $nav;
        }

        return $availableNav;
    }

    public function activateComponent(string $name)
    {
        if ( !in_array($name,
            array_keys($this->nav()))
        ) {

            dd('wrong nav sys name: ' . $name);

            return;
        }

        foreach ($this->nav() as $navSysName => $nav) {

            $fullKey = '';

            $keys = explode('.',
                $navSysName);

            if (count($keys) === 2) {
                $fullKey = $keys[0] . '.' . 'items' . '.' . $keys[1];
            } else {
                $fullKey = $navSysName;
            }

            $fullKey = $fullKey.'.active';

            if ($navSysName !== $name) {

                Arr::set($this->nav,
                    $fullKey,
                    false);
                continue;
            }

            if (
                $nav['shouldAuth'] && !AuthUser::instance()
                    ->isLoggedIn()
            ) {
                continue;
            }

            $setValue = true;

            if (
                $nav['shouldHaveSelectedCompany'] && !AuthUser::instance()
                    ->selectedCompany()
            ) {
                $setValue = false;
            }

            Arr::set($this->nav,
                $fullKey,
                $setValue);
        }
    }

    public function mount()
    {
    }

    public function getNav() : array
    {
        foreach ($this->nav as $key0 => &$nav){
            if(isset($nav['shouldHaveSelectedCompany'])){
                $nav['available'] = $nav['shouldHaveSelectedCompany'] ? AuthUser::instance()->selectedCompany() : true;
            }

            $isAtLeastOneItemAvailable = false;

            foreach ($nav['items'] ?? [] as $key => $nav1){
                if(isset($nav1['shouldHaveSelectedCompany'])){
                    $nav['items'][$key]['available'] = $nav1['shouldHaveSelectedCompany'] ? boolval(AuthUser::instance()->selectedCompany()) : true;

                    if($nav['items'][$key]['available'] ){
                        $isAtLeastOneItemAvailable = true;
                    }
                }
            }

            if($isAtLeastOneItemAvailable){
                continue;
            }



            if(isset($nav['items'])){
                // dd($nav);
                unset($this->nav[$key0]);
            }

        }



        return $this->nav;
    }

    public function updating(){
        $this->dispatchBrowserEvent('open_event');

    }
}