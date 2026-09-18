<?php

namespace App\Http\Livewire;

use App\Log;
use Livewire\Component;
use App\Services\AuthUser;
use Illuminate\Support\Arr;

class MainApp extends Component
{

    public $activeCompanyId = 'x';
    public $globalSearchQuery = '';

    protected $listeners = [
        'changeActiveCompany' => 'setActiveCompanyId',
        'clearActiveCompany' => 'clearActiveCompany',
        'activateComponent' => 'activateComponent',
        'unreadBugReportsUpdated' => '$refresh',
        'refreshNavigationBadges' => '$refresh',
    ];
    public $nav = [
        'companies' => [
            'title' => 'Sākums',
            'active' => true,
            'available' => true,
            'shouldAuth' => true,
            'shouldHaveSelectedCompany' => false,

        ],
        'partners' => [
            'title' => 'Partneri',
            'active' => false,
            'available' => true,
            'shouldAuth' => true,
            'shouldHaveSelectedCompany' => true,
        ],
        'invoices' => [
            'title' => 'Rēķini',
            'active' => false,
            'available' => true,
            'shouldAuth' => true,
            'shouldHaveSelectedCompany' => true,
        ],
        'cash-expenses' => [
            'title' => 'Avansu norēķini',
            'active' => false,
            'available' => true,
            'shouldAuth' => true,
            'shouldHaveSelectedCompany' => true,
        ],
        'personal-income' => [
            'title' => 'IIN / Algas',
            'active' => false,
            'available' => false,
            'shouldAuth' => true,
            'shouldHaveSelectedCompany' => true,
        ],
        'other' => [
            'items' => [
                'company-data' => [
                    'title' => 'Uzņēmuma dati',
                    'active' => false,
                    'available' => true,
                    'shouldAuth' => true,
                    'shouldHaveSelectedCompany' => true,
                ],
                'other-payment-receivers' => [
                    'title' => 'Citi maksājumu saņēmēji',
                    'active' => false,
                    'available' => true,
                    'shouldAuth' => true,
                    'shouldHaveSelectedCompany' => true,
                ],
                'settings' => [
                    'title' => 'Iestatījumi',
                    'active' => false,
                    'available' => false,
                    'shouldAuth' => true,
                    'shouldHaveSelectedCompany' => true,
                ],
                'vacations' => [
                    'title' => 'Atvaļinājumi',
                    'active' => false,
                    'available' => true,
                    'shouldAuth' => true,
                    'shouldHaveSelectedCompany' => true,
                ],
            ],
        ],
        'profile' => [
            'title' => 'Lietotāja profils',
            'active' => false,
            'available' => false,
            'shouldAuth' => true,
            'shouldHaveSelectedCompany' => false,
        ],
        'feedback' => [
            'title' => 'Manas saziņas',
            'active' => false,
            'available' => true,
            'shouldAuth' => true,
            'shouldHaveSelectedCompany' => false,
        ],
    ];

    public function mount()
    {
        if (!\Illuminate\Support\Facades\Auth::check()) {
            return redirect()->route('login');
        }
    }

    public function render()
    {
        $this->nav['personal-income']['available'] = AuthUser::instance()->isAdmin();

        return view('livewire.main-app')->layout('layouts.app-test');
    }

    public function setActiveCompanyId($id)
    {
        AuthUser::instance()->setCompany($id);
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

            if ($navSysName === 'personal-income' && !AuthUser::instance()->isAdmin()) {
                continue;
            }

            $availableNav[$navSysName] = $nav;
        }

        return $availableNav;
    }

    public function activateComponent(string $name)
    {
        if (!in_array($name,
            array_keys($this->nav()))
        ) {
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

            $fullKey = $fullKey . '.active';

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

        if ($name === 'invoices') {
            $this->emit('closeInvoice');
        }

        if ($name === 'cash-expenses') {
            $this->emit('closeForm');
        }

        if ($name === 'feedback') {
            $this->emit('resetBugReportsList');
        }
    }

    public function clearActiveCompany()
    {
        AuthUser::instance()->clearCompany();
        $this->activeCompanyId = 'none';
        $this->activateComponent('companies');
    }

    public function getNav(): array
    {
        $hasSelectedCompany = boolval(AuthUser::instance()->selectedCompany());
        $isAdmin = boolval(AuthUser::instance()->isAdmin());
        $navCopy = $this->nav;

        foreach ($navCopy as $key0 => &$nav) {
            if ($key0 === 'personal-income') {
                $nav['available'] = $isAdmin && $hasSelectedCompany;
                continue;
            }

            if ($key0 === 'profile') {
                $nav['available'] = false;
                continue;
            }

            if (isset($nav['shouldHaveSelectedCompany']) && $nav['shouldHaveSelectedCompany']) {
                $nav['available'] = $hasSelectedCompany;
            }

            if (isset($nav['items'])) {
                $isAtLeastOneItemAvailable = false;
                foreach ($nav['items'] as $key => &$nav1) {
                    if (isset($nav1['shouldHaveSelectedCompany']) && $nav1['shouldHaveSelectedCompany']) {
                        $nav1['available'] = $hasSelectedCompany;
                    }
                    if (!empty($nav1['available'])) {
                        $isAtLeastOneItemAvailable = true;
                    }
                }
                $nav['available'] = $isAtLeastOneItemAvailable;
                if (!$isAtLeastOneItemAvailable) {
                    unset($navCopy[$key0]);
                }
            }
        }

        return $navCopy;
    }

    public function updating()
    {
        $this->dispatchBrowserEvent('open_event');
    }

    public function deleteInvoiceCancel()
    {
        $this->dispatchBrowserEvent('closeModal_delete_invoice');
    }

    public function copyInvoiceCancel()
    {
        $this->dispatchBrowserEvent('closeModal_copy_invoice');
    }

    public function shortcutInvoiceCancel()
    {
        $this->dispatchBrowserEvent('closeModal_shortcut_invoice');
    }

    public function getSearchResultsProperty(): array
    {
        $query = trim($this->globalSearchQuery);
        if (mb_strlen($query) < 2) {
            return [];
        }

        $results = [
            'invoices' => collect(),
            'partners' => collect(),
            'cashExpenses' => collect(),
        ];

        $companyId = AuthUser::instance()->selectedCompanyId();
        if (!$companyId) {
            return $results;
        }

        // 1. Invoices (for active selected company)
        $results['invoices'] = \App\Invoice::where('company_id', $companyId)
            ->where(function ($q) use ($query) {
                $q->where('number', 'like', "%{$query}%")
                  ->orWhere('partner_name', 'like', "%{$query}%")
                  ->orWhere('amount_total', 'like', "%{$query}%");
            })
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        // 2. Partners (for active selected company)
        $results['partners'] = \App\Partner::where('company_id', $companyId)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('registration_number', 'like', "%{$query}%")
                  ->orWhere('vat_number', 'like', "%{$query}%");
            })
            ->orderBy('name', 'asc')
            ->limit(5)
            ->get();

        // 3. Cash Expenses (for active selected company)
        $results['cashExpenses'] = \Illuminate\Support\Facades\DB::table('cash_expenses as ce')
            ->select(['ce.id', 'ce.no', 'ce.date', 'empl.name as employee_name'])
            ->leftJoin('employees as empl', 'ce.employee_id', '=', 'empl.id')
            ->where('ce.company_id', $companyId)
            ->where(function ($q) use ($query) {
                $q->where('ce.no', 'like', "%{$query}%")
                  ->orWhere('empl.name', 'like', "%{$query}%");
            })
            ->orderBy('ce.date', 'desc')
            ->limit(5)
            ->get();

        return $results;
    }

    public function selectSearchResult(string $type, $id = null)
    {
        $this->globalSearchQuery = '';

        if ($type === 'company' && $id) {
            $this->setActiveCompanyId($id);
            $this->activateComponent('companies');
        } elseif ($type === 'invoices') {
            $this->activateComponent('invoices');
        } elseif ($type === 'partners') {
            $this->activateComponent('partners');
        } elseif ($type === 'cash-expenses') {
            $this->activateComponent('cash-expenses');
        }
    }

    public function clearGlobalSearch()
    {
        $this->globalSearchQuery = '';
    }

    public function getUnreadBugReportsCountProperty(): int
    {
        $userId = AuthUser::instance()->userId() ?? \Illuminate\Support\Facades\Auth::id();
        if (!$userId) {
            return 0;
        }

        return \App\BugReport::unreadCountForUser($userId);
    }
}