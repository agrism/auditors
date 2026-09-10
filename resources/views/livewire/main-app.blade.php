<div class="eds-app-wrapper" data-page-title="{{ config('app.name', 'Auditors.lv') }} :: {{ match($this->activeComponent()) {
    'companies' => \App\Services\AuthUser::instance()->selectedCompany() ? 'SĀKUMS' : 'UZŅĒMUMA IZVĒLE',
    'invoices' => 'RĒĶINI',
    'partners' => 'PARTNERI',
    'cash-expenses' => 'AVANSU NORĒĶINI',
    'personal-income' => 'IIN / ALGAS',
    'profile' => 'LIETOTĀJA PROFILS',
    'other.company-data' => 'UZŅĒMUMA DATI',
    'other.other-payment-receivers' => 'CITI MAKSĀJUMU SAŅĒMĒJI',
    'other.vacations' => 'ATVAĻINĀJUMI',
    'other.settings' => 'IESTATĪJUMI',
    default => \App\Services\AuthUser::instance()->selectedCompany() ? 'SĀKUMS' : 'UZŅĒMUMA IZVĒLE'
} }}">
    <?php
    $activeComp = $this->activeComponent();
    $selectedCompany = \App\Services\AuthUser::instance()->selectedCompany();
    $headerTitle = match($activeComp) {
        'companies' => $selectedCompany ? 'SĀKUMS' : 'UZŅĒMUMA IZVĒLE',
        'invoices' => 'RĒĶINI',
        'partners' => 'PARTNERI',
        'cash-expenses' => 'AVANSU NORĒĶINI',
        'personal-income' => 'IIN / ALGAS',
        'profile' => 'LIETOTĀJA PROFILS',
        'other.company-data' => 'UZŅĒMUMA DATI',
        'other.other-payment-receivers' => 'CITI MAKSĀJUMU SAŅĒMĒJI',
        'other.vacations' => 'ATVAĻINĀJUMI',
        'other.settings' => 'IESTATĪJUMI',
        default => $selectedCompany ? 'SĀKUMS' : 'UZŅĒMUMA IZVĒLE'
    };
    $fullTitle = config('app.name', 'Auditors.lv') . ' :: ' . $headerTitle;
    ?>
    @section('title', $headerTitle)
    <script>
        if (typeof document !== 'undefined') {
            document.title = @json($fullTitle);
        }
    </script>
    <!-- Left Navigation Sidebar -->
    <aside class="eds-sidebar" id="edsSidebar">
        <!-- Logo & Brand Header: Auditors.lv -->
        <div class="eds-brand-header">
            <a class="eds-brand-link text-decoration-none d-inline-flex flex-column align-items-center justify-content-center" href="#" wire:click.prevent="activateComponent('companies')">
                <div class="eds-logo-container">
                    <div class="eds-logo-icon">
                        <svg width="28" height="28" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="32" height="32" rx="7" fill="url(#auditors_brand_grad)" />
                            <!-- Modern financial / ledger / auditing mark -->
                            <rect x="7" y="7" width="18" height="18" rx="3" stroke="#ffffff" stroke-width="1.6" stroke-opacity="0.9" fill="none"/>
                            <rect x="10" y="10.5" width="12" height="2.8" rx="1" fill="#ffffff" fill-opacity="0.95"/>
                            <circle cx="11.5" cy="17.5" r="1.4" fill="#38bdf8"/>
                            <circle cx="16" cy="17.5" r="1.4" fill="#ffffff" fill-opacity="0.9"/>
                            <circle cx="20.5" cy="17.5" r="1.4" fill="#ffffff" fill-opacity="0.9"/>
                            <path d="M10.5 21.5H21.5" stroke="#ffffff" stroke-width="1.4" stroke-linecap="round" stroke-opacity="0.6"/>
                            <defs>
                                <linearGradient id="auditors_brand_grad" x1="0" y1="0" x2="32" y2="32" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#002855"/>
                                    <stop offset="1" stop-color="#0056b3"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                    <div class="eds-logo-text">
                        <span class="eds-logo-name">Auditors</span><span class="eds-logo-tld">.lv</span>
                    </div>
                </div>
                <span class="eds-brand-sub">Rēķinu vadības sistēma</span>
            </a>
        </div>

        <!-- Sidebar Navigation Menu -->
        <ul class="eds-sidebar-menu">
            @foreach($this->getNav() as $sysName => $item)
                @if(isset($item['items']))
                    <?php
                    $isSubActive = false;
                    foreach($item['items'] as $subItem) {
                        if(!empty($subItem['active'])) { $isSubActive = true; break; }
                    }
                    ?>
                    <li class="eds-menu-item">
                        <a class="eds-menu-link d-flex justify-content-between align-items-center @if($isSubActive) active @endif"
                           data-bs-toggle="collapse"
                           href="#edsMenu{{ ucfirst($sysName) }}"
                           role="button"
                           aria-expanded="{{ $isSubActive ? 'true' : 'false' }}"
                           aria-controls="edsMenu{{ ucfirst($sysName) }}">
                            <span class="d-inline-flex align-items-center gap-2">
                                <i class="fa-solid fa-layer-group opacity-75"></i>
                                <span>{{ $item['title'] ?? __('Vairāk') }}</span>
                            </span>
                            <i class="fa-solid fa-chevron-down eds-chevron"></i>
                        </a>

                        <ul class="collapse @if($isSubActive) show @endif eds-submenu" id="edsMenu{{ ucfirst($sysName) }}">
                            @foreach($item['items'] as $subSysName => $subItem)
                                @if(!isset($subItem['available']) || !$subItem['available'])
                                    @continue
                                @endif
                                <li>
                                    <a href="#"
                                       wire:click.prevent="activateComponent('{{$sysName.'.'.$subSysName}}')"
                                       class="eds-submenu-link @if(!empty($subItem['active'])) active @endif">
                                        <span class="d-inline-flex align-items-center gap-2">
                                            @if($subSysName === 'company-data')
                                                <i class="fa-solid fa-id-card opacity-75"></i>
                                            @elseif($subSysName === 'other-payment-receivers')
                                                <i class="fa-solid fa-building-columns opacity-75"></i>
                                            @elseif($subSysName === 'settings')
                                                <i class="fa-solid fa-sliders opacity-75"></i>
                                            @elseif($subSysName === 'vacations')
                                                <i class="fa-solid fa-umbrella-beach opacity-75"></i>
                                            @endif
                                            <span>{{ $subItem['title'] ?? '---' }}</span>
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                    @continue
                @endif

                @if(!isset($item['available']) || !$item['available'])
                    @continue
                @endif

                <li class="eds-menu-item">
                    <a href="#"
                       wire:click.prevent="activateComponent('{{$sysName}}')"
                       class="eds-menu-link @if(!empty($item['active'])) active @endif">
                        <span class="d-inline-flex align-items-center gap-2">
                            @if($sysName === 'companies')
                                <i class="fa-solid fa-house opacity-75"></i>
                            @elseif($sysName === 'invoices')
                                <i class="fa-solid fa-file-invoice-dollar opacity-75"></i>
                            @elseif($sysName === 'partners')
                                <i class="fa-solid fa-users-line opacity-75"></i>
                            @elseif($sysName === 'cash-expenses')
                                <i class="fa-solid fa-money-bill-transfer opacity-75"></i>
                            @elseif($sysName === 'personal-income')
                                <i class="fa-solid fa-hand-holding-dollar opacity-75"></i>
                            @endif
                            <span>{{ $item['title'] }}</span>
                        </span>
                    </a>
                </li>
            @endforeach

            @if(\Auth::check() && \Auth::user()->isAdmin())
                <li class="eds-menu-item">
                    <a href="{{ route('admin.home') }}" class="eds-menu-link text-warning fw-semibold">
                        <span class="d-inline-flex align-items-center gap-2">
                            <i class="fa-solid fa-shield-halved"></i>
                            <span>{{ __('Admin Panel') }}</span>
                        </span>
                    </a>
                </li>
            @endif
        </ul>

        <!-- Sidebar Footer -->
        <div class="eds-sidebar-footer">
            <a href="{{ route('logout') }}" class="eds-logout-link">
                <i class="fa-solid fa-arrow-right-from-bracket me-2 text-secondary"></i>
                <span>{{ __('Log Out') }}</span>
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="eds-main-layout">
        <!-- Top Navy Navigation Header -->
        <header class="eds-topbar">
            <!-- Left: Mobile Toggle & Page Title -->
            <?php
            $userCompanies = (\App\Services\AuthUser::instance()->companies() ?? collect())->sortBy('title', SORT_NATURAL | SORT_FLAG_CASE);
            $userName = \Illuminate\Support\Facades\Auth::user()->name ?? 'Lietotājs';
            ?>

            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-link text-white p-0 d-inline-flex align-items-center me-1 d-lg-none"
                        type="button"
                        onclick="document.getElementById('edsSidebar').classList.toggle('show')">
                    <i class="fa-solid fa-bars fs-5"></i>
                </button>
                <h1 class="eds-topbar-title">{{ $headerTitle }}</h1>
            </div>

            <!-- Right: Search, Taxpayer, User, Info -->
            <div class="eds-topbar-controls">
                <!-- Search Button & Dropdown -->
                <div class="dropdown">
                    <a href="#" class="eds-topbar-btn eds-search-block" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" title="Meklēt">
                        <i class="fa-solid fa-magnifying-glass eds-topbar-icon"></i>
                        <span class="eds-topbar-text eds-label-wide fw-bold text-uppercase">MEKLĒT</span>
                        <i class="fa-solid fa-caret-down eds-topbar-caret eds-label-wide"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow-lg p-0 border-0 eds-search-menu" style="min-width: 360px; max-width: 440px; z-index: 1050;">
                        <div class="p-3 border-bottom bg-light rounded-top">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <label class="form-label small fw-bold text-muted mb-0 text-uppercase letter-spacing-1">
                                    <i class="fa-solid fa-magnifying-glass me-1 text-primary"></i> Ātrā meklēšana
                                </label>
                                <span wire:loading wire:target="globalSearchQuery" class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Meklē...</span>
                                </span>
                            </div>
                            <div class="input-group input-group-sm mt-2">
                                <span class="input-group-text bg-white border-end-0 text-muted">
                                    <i class="fa-solid fa-search"></i>
                                </span>
                                <input type="text"
                                       class="form-control border-start-0 border-end-0 shadow-none ps-1"
                                       placeholder="Meklēt uzņēmumus, rēķinus, partnerus..."
                                       wire:model.debounce.300ms="globalSearchQuery">
                                @if(!empty($globalSearchQuery))
                                    <button class="btn btn-outline-secondary border-start-0 bg-white"
                                            type="button"
                                            wire:click="clearGlobalSearch"
                                            title="Notīrīt">
                                        <i class="fa-solid fa-xmark text-muted"></i>
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Results List -->
                        <div class="eds-search-results-list" style="max-height: 380px; overflow-y: auto;">
                            @php
                                $queryLen = mb_strlen(trim($globalSearchQuery));
                                $results = $this->searchResults;
                                $hasResults = false;
                                if ($queryLen >= 2 && !empty($results)) {
                                    $hasResults = ($results['companies']->isNotEmpty() || $results['invoices']->isNotEmpty() || $results['partners']->isNotEmpty() || $results['cashExpenses']->isNotEmpty());
                                }
                            @endphp

                            @if($queryLen < 2)
                                <div class="p-4 text-center text-muted">
                                    <i class="fa-solid fa-keyboard fs-3 text-secondary opacity-50 mb-2"></i>
                                    <div class="small fw-medium">Ievadiet vismaz 2 simbolus</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">Meklējiet pēc uzņēmuma nosaukuma, reģ. Nr., rēķina numura vai partnera</div>
                                </div>
                            @elseif(!$hasResults)
                                <div class="p-4 text-center text-muted">
                                    <i class="fa-solid fa-magnifying-glass fs-3 text-secondary opacity-50 mb-2"></i>
                                    <div class="small fw-semibold text-dark">Nekas netika atrasts</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">Nav atrasts neviens ieraksts vaicājumam "{{ $globalSearchQuery }}"</div>
                                </div>
                            @else
                                <!-- Companies -->
                                @if($results['companies']->isNotEmpty())
                                    <div class="eds-search-group-header">
                                        <i class="fa-solid fa-building me-1 text-primary"></i> Uzņēmumi ({{ $results['companies']->count() }})
                                    </div>
                                    @foreach($results['companies'] as $comp)
                                        <a href="#"
                                           class="eds-search-item"
                                           wire:click.prevent="selectSearchResult('company', {{ $comp->id }})">
                                            <div class="eds-search-item-icon bg-primary-subtle text-primary">
                                                <i class="fa-solid fa-building"></i>
                                            </div>
                                            <div class="eds-search-item-content">
                                                <div class="eds-search-item-title">{{ $comp->title }}</div>
                                                <div class="eds-search-item-subtitle">
                                                    @if(!empty($comp->registration_number))
                                                        Reģ. Nr.: <span class="font-monospace">{{ $comp->registration_number }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <i class="fa-solid fa-chevron-right eds-search-item-arrow"></i>
                                        </a>
                                    @endforeach
                                @endif

                                <!-- Invoices -->
                                @if($results['invoices']->isNotEmpty())
                                    <div class="eds-search-group-header">
                                        <i class="fa-solid fa-file-invoice me-1 text-success"></i> Rēķini ({{ $results['invoices']->count() }})
                                    </div>
                                    @foreach($results['invoices'] as $inv)
                                        <a href="#"
                                           class="eds-search-item"
                                           wire:click.prevent="selectSearchResult('invoices')">
                                            <div class="eds-search-item-icon bg-success-subtle text-success">
                                                <i class="fa-solid fa-file-invoice"></i>
                                            </div>
                                            <div class="eds-search-item-content">
                                                <div class="eds-search-item-title">
                                                    Nr. {{ $inv->number }}
                                                    @if($inv->partner_name)
                                                        <span class="text-muted fw-normal"> &bull; {{ $inv->partner_name }}</span>
                                                    @endif
                                                </div>
                                                <div class="eds-search-item-subtitle">
                                                    @if($inv->date)
                                                        <span>{{ \Carbon\Carbon::parse($inv->date)->format('d.m.Y') }}</span>
                                                    @endif
                                                    @if($inv->amount_total)
                                                        <span class="ms-2 fw-semibold text-dark">{{ number_format($inv->amount_total, 2, '.', ' ') }} EUR</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <i class="fa-solid fa-chevron-right eds-search-item-arrow"></i>
                                        </a>
                                    @endforeach
                                @endif

                                <!-- Partners -->
                                @if($results['partners']->isNotEmpty())
                                    <div class="eds-search-group-header">
                                        <i class="fa-solid fa-handshake me-1 text-info"></i> Partneri ({{ $results['partners']->count() }})
                                    </div>
                                    @foreach($results['partners'] as $part)
                                        <a href="#"
                                           class="eds-search-item"
                                           wire:click.prevent="selectSearchResult('partners')">
                                            <div class="eds-search-item-icon bg-info-subtle text-info">
                                                <i class="fa-solid fa-handshake"></i>
                                            </div>
                                            <div class="eds-search-item-content">
                                                <div class="eds-search-item-title">{{ $part->name }}</div>
                                                <div class="eds-search-item-subtitle">
                                                    @if(!empty($part->registration_number))
                                                        Reģ. Nr.: <span class="font-monospace">{{ $part->registration_number }}</span>
                                                    @elseif(!empty($part->vat_number))
                                                        PVN: <span class="font-monospace">{{ $part->vat_number }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <i class="fa-solid fa-chevron-right eds-search-item-arrow"></i>
                                        </a>
                                    @endforeach
                                @endif

                                <!-- Cash Expenses -->
                                @if($results['cashExpenses']->isNotEmpty())
                                    <div class="eds-search-group-header">
                                        <i class="fa-solid fa-receipt me-1 text-warning"></i> Avansu norēķini ({{ $results['cashExpenses']->count() }})
                                    </div>
                                    @foreach($results['cashExpenses'] as $ce)
                                        <a href="#"
                                           class="eds-search-item"
                                           wire:click.prevent="selectSearchResult('cash-expenses')">
                                            <div class="eds-search-item-icon bg-warning-subtle text-warning">
                                                <i class="fa-solid fa-receipt"></i>
                                            </div>
                                            <div class="eds-search-item-content">
                                                <div class="eds-search-item-title">
                                                    Akts Nr. {{ $ce->no }}
                                                    @if($ce->employee_name)
                                                        <span class="text-muted fw-normal"> &bull; {{ $ce->employee_name }}</span>
                                                    @endif
                                                </div>
                                                <div class="eds-search-item-subtitle">
                                                    @if($ce->date)
                                                        <span>{{ \Carbon\Carbon::parse($ce->date)->format('d.m.Y') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <i class="fa-solid fa-chevron-right eds-search-item-arrow"></i>
                                        </a>
                                    @endforeach
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Taxpayer / Company Switcher Dropdown (shown when company is selected) -->
                @if($selectedCompany)
                    <div class="dropdown">
                        <a href="#" class="eds-topbar-btn eds-taxpayer-block" data-bs-toggle="dropdown" aria-expanded="false" title="{{ $selectedCompany->title }}">
                            <i class="fa-solid fa-building eds-topbar-icon"></i>
                            <div class="eds-topbar-text-group eds-label-mid">
                                <span class="eds-topbar-sublabel">NODOKĻU MAKSĀTĀJS</span>
                                <span class="eds-topbar-mainval">
                                    {{ $selectedCompany->title }}@if(!empty($selectedCompany->reg_number)) ({{ $selectedCompany->reg_number }})@endif
                                </span>
                            </div>
                            <i class="fa-solid fa-caret-down eds-topbar-caret eds-label-mid"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 py-1 eds-taxpayer-dropdown-menu">
                            <li><div class="dropdown-header text-uppercase small fw-bold text-muted py-2 px-3">Pārslēgt uzņēmumu</div></li>
                            <div class="eds-taxpayer-list" style="max-height: 320px; overflow-y: auto; overflow-x: hidden;">
                                @foreach($userCompanies as $company)
                                    <?php
                                    $compRegNo = $company->registration_number ?? $company->reg_number;
                                    $isSelected = ($selectedCompany && $selectedCompany->id == $company->id);
                                    ?>
                                    <li>
                                        <a class="dropdown-item py-2 px-3 d-flex align-items-center justify-content-between @if($isSelected) active @endif"
                                           href="#" wire:click.prevent="setActiveCompanyId({{ $company->id }})">
                                            <div class="text-truncate me-2" style="min-width: 0; max-width: 230px;">
                                                <div class="fw-semibold text-truncate" style="font-size: 0.85rem;">{{ $company->title }}</div>
                                                @if(!empty($compRegNo))
                                                    <div class="font-monospace" style="font-size: 0.75rem; {{ $isSelected ? 'color: rgba(255,255,255,0.85);' : 'color: #64748b;' }}">
                                                        {{ $compRegNo }}
                                                    </div>
                                                @endif
                                            </div>
                                            @if($isSelected)
                                                <i class="fa-solid fa-check text-white ms-2 flex-shrink-0"></i>
                                            @endif
                                        </a>
                                    </li>
                                @endforeach
                            </div>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <a class="dropdown-item py-2 px-3 text-primary fw-semibold d-flex align-items-center gap-2" href="#" wire:click.prevent="clearActiveCompany">
                                    <i class="fa-solid fa-list text-primary"></i> <span>Visi uzņēmumi</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                @endif

                <!-- User Profile Offcanvas Button -->
                <button type="button" 
                        class="eds-topbar-btn eds-user-block border-0 bg-transparent" 
                        data-bs-toggle="offcanvas" 
                        data-bs-target="#userSidebarOffcanvas" 
                        aria-controls="userSidebarOffcanvas" 
                        title="{{ $userName }}">
                    <i class="fa-solid fa-user eds-topbar-icon"></i>
                    <div class="eds-topbar-text-group eds-label-mid">
                        <span class="eds-topbar-sublabel">LIETOTĀJS</span>
                        <span class="eds-topbar-mainval">
                            {{ strtoupper($userName) }}
                        </span>
                    </div>
                    <i class="fa-solid fa-chevron-right eds-topbar-caret eds-label-mid" style="font-size: 0.75rem;"></i>
                </button>

                <!-- Info Icon -->
                <a href="#"
                   class="eds-topbar-btn eds-info-icon-btn"
                   title="Informācija un palīdzība"
                   data-bs-toggle="modal"
                   data-bs-target="#edsInfoModal">
                    <i class="fa-solid fa-circle-info eds-topbar-icon"></i>
                </a>
            </div>
        </header>

        <!-- Loading Indicator -->
        <div wire:loading style="position: fixed; top: 1.25rem; right: 1.25rem; z-index: 9999;">
            <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <!-- Main Workspace Components Container -->
        <main class="flex-grow-1 p-3 p-lg-4">
            <?php $companyId = \App\Services\AuthUser::instance()->selectedCompanyId(); ?>

            @if(empty($companyId) || !$selectedCompany)
                <livewire:company-list :wire:key="'comp-list-'.$this->activeCompanyId"/>
            @elseif($this->activeComponent() === 'companies')
                <!-- Selected Company Executive Dashboard -->
                <?php
                $invoicesCount = \App\Invoice::where('company_id', $companyId)->count();
                $invoicesSum = (float)\App\Invoice::where('company_id', $companyId)->sum('amount_total');
                $partnersCount = \App\Partner::where('company_id', $companyId)->count();
                $expensesCount = \Illuminate\Support\Facades\DB::table('cash_expenses')->where('company_id', $companyId)->count();
                $employeesCount = \App\Employee::where('company_id', $companyId)->count();
                $recentInvoices = \App\Invoice::where('company_id', $companyId)
                    ->orderBy('date', 'desc')
                    ->orderBy('id', 'desc')
                    ->limit(5)
                    ->get();
                $vatNumber = $selectedCompany->vatNumbers->first()->vat_number ?? null;
                ?>

                <div class="company-dashboard">
                    <!-- 1. Executive Hero Header Banner -->
                    <div class="dash-hero mb-4">
                        <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="dash-hero-avatar">
                                    <i class="fa-solid fa-building"></i>
                                </div>
                                <div>
                                    <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                        <h3 class="fw-bold text-white mb-0">{{ $selectedCompany->title }}</h3>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 flex-wrap mt-2">
                                        <span class="dash-hero-badge">
                                            <i class="fa-solid fa-circle-check text-emerald-400"></i>
                                            <span>{{ __('Aktīvs uzņēmums') }}</span>
                                        </span>
                                        @if(!empty($selectedCompany->reg_number))
                                            <span class="dash-hero-badge">
                                                <i class="fa-solid fa-hashtag text-cyan-300"></i>
                                                <span>{{ __('Reģ. Nr.') }}: <strong class="font-monospace">{{ $selectedCompany->reg_number }}</strong></span>
                                            </span>
                                        @endif
                                        @if(!empty($vatNumber))
                                            <span class="dash-hero-badge">
                                                <i class="fa-solid fa-receipt text-cyan-300"></i>
                                                <span>{{ __('PVN Nr.') }}: <strong class="font-monospace">{{ $vatNumber }}</strong></span>
                                            </span>
                                        @endif
                                        @if(!empty($selectedCompany->address))
                                            <span class="dash-hero-badge text-truncate" style="max-width: 360px;" title="{{ $selectedCompany->address }}">
                                                <i class="fa-solid fa-location-dot text-cyan-300"></i>
                                                <span>{{ $selectedCompany->address }}</span>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <button class="btn btn-sm btn-outline-light rounded-pill px-3 py-1.5 shadow-sm fw-medium d-inline-flex align-items-center gap-1.5"
                                        wire:click.prevent="clearActiveCompany"
                                        title="{{ __('Izvēlēties citu uzņēmumu') }}">
                                    <i class="fa-solid fa-repeat"></i>
                                    <span>{{ __('Mainīt uzņēmumu') }}</span>
                                </button>
                                <button class="btn btn-sm btn-light rounded-pill px-3 py-1.5 shadow-sm fw-medium text-slate-800 d-inline-flex align-items-center gap-1.5"
                                        wire:click.prevent="activateComponent('other.company-data')"
                                        title="{{ __('Skatīt uzņēmuma datus') }}">
                                    <i class="fa-solid fa-id-card text-primary"></i>
                                    <span>{{ __('Rekvizīti') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- 2. KPI / Metrics Summary Ribbon -->
                    <div class="row g-3 mb-4">
                        <!-- KPI: Invoices -->
                        <div class="col-md-4">
                            <div class="dash-kpi-card" wire:click.prevent="activateComponent('invoices')">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-muted small fw-bold text-uppercase letter-spacing-wide">{{ __('Rēķini') }}</span>
                                    <div class="dash-icon-box bg-primary-50 text-primary-600">
                                        <i class="fa-solid fa-file-invoice-dollar"></i>
                                    </div>
                                </div>
                                <div class="my-1">
                                    <h3 class="fw-bold text-slate-900 mb-0 font-monospace">{{ $invoicesCount }}</h3>
                                    <div class="small text-muted mt-1">
                                        {{ __('Apgrozījums') }}: <strong class="text-slate-800 font-monospace">€ {{ number_format($invoicesSum, 2, '.', ' ') }}</strong>
                                    </div>
                                </div>
                                <div class="pt-2 border-top mt-2">
                                    <span class="text-primary-600 fw-semibold small d-inline-flex align-items-center gap-1">
                                        <span>{{ __('Pāriet uz rēķiniem') }}</span> <i class="fa-solid fa-arrow-right" style="font-size: 0.7rem;"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- KPI: Partners -->
                        <div class="col-md-4">
                            <div class="dash-kpi-card" wire:click.prevent="activateComponent('partners')">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-muted small fw-bold text-uppercase letter-spacing-wide">{{ __('Partneri') }}</span>
                                    <div class="dash-icon-box" style="background-color: #ecfdf5; color: #059669;">
                                        <i class="fa-solid fa-users-line"></i>
                                    </div>
                                </div>
                                <div class="my-1">
                                    <h3 class="fw-bold text-slate-900 mb-0 font-monospace">{{ $partnersCount }}</h3>
                                    <div class="small text-muted mt-1">
                                        {{ __('Reģistrētie partneri un klienti') }}
                                    </div>
                                </div>
                                <div class="pt-2 border-top mt-2">
                                    <span class="text-emerald-600 fw-semibold small d-inline-flex align-items-center gap-1">
                                        <span>{{ __('Pāriet uz partneriem') }}</span> <i class="fa-solid fa-arrow-right" style="font-size: 0.7rem;"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- KPI: Expenses -->
                        <div class="col-md-4">
                            <div class="dash-kpi-card" wire:click.prevent="activateComponent('cash-expenses')">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-muted small fw-bold text-uppercase letter-spacing-wide">{{ __('Avansu norēķini') }}</span>
                                    <div class="dash-icon-box" style="background-color: #fffbeb; color: #d97706;">
                                        <i class="fa-solid fa-money-bill-transfer"></i>
                                    </div>
                                </div>
                                <div class="my-1">
                                    <h3 class="fw-bold text-slate-900 mb-0 font-monospace">{{ $expensesCount }}</h3>
                                    <div class="small text-muted mt-1">
                                        {{ __('Čeki un izdevumu dokumenti') }}
                                    </div>
                                </div>
                                <div class="pt-2 border-top mt-2">
                                    <span class="text-amber-600 fw-semibold small d-inline-flex align-items-center gap-1">
                                        <span>{{ __('Pāriet uz avansiem') }}</span> <i class="fa-solid fa-arrow-right" style="font-size: 0.7rem;"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Primary Core Workspace Row: Actions & Company Passport -->
                    <div class="row g-4 mb-4">
                        <!-- Left: Core Action Grid -->
                        <div class="col-12 col-xl-8">
                            <div class="dash-side-card p-3.5 p-sm-4 h-100">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h6 class="fw-bold text-slate-900 mb-0 d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-cubes text-primary-500"></i>
                                        {{ __('Pieejamās darbības un sadaļas') }}
                                    </h6>
                                </div>

                                <div class="row g-3">
                                    <!-- Rēķini -->
                                    <div class="col-12 col-sm-6">
                                        <div class="dash-action-card">
                                            <div>
                                                <div class="d-flex align-items-center justify-content-between mb-2 gap-2">
                                                    <div class="d-flex align-items-center gap-2 min-w-0">
                                                        <div class="dash-icon-box bg-primary-50 text-primary-600">
                                                            <i class="fa-solid fa-file-invoice-dollar"></i>
                                                        </div>
                                                        <h6 class="fw-bold text-slate-900 mb-0 text-truncate">{{ __('Rēķinu vadība') }}</h6>
                                                    </div>
                                                    <span class="dash-action-badge badge-invoices flex-shrink-0" title="{{ __('Kopā rēķini') }}">
                                                        {{ $invoicesCount }}
                                                    </span>
                                                </div>
                                                <p class="small text-muted mb-3">
                                                    {{ __('Izejošo un ienākošo rēķinu sagatavošana, apmaksas statusi, PDF un UBL e-rēķinu eksports.') }}
                                                </p>
                                            </div>
                                            <button class="btn btn-modern btn-modern-primary btn-sm w-100" wire:click.prevent="activateComponent('invoices')">
                                                <i class="fa-solid fa-arrow-right me-1"></i> {{ __('Atvērt rēķinus') }}
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Partneri -->
                                    <div class="col-12 col-sm-6">
                                        <div class="dash-action-card">
                                            <div>
                                                <div class="d-flex align-items-center justify-content-between mb-2 gap-2">
                                                    <div class="d-flex align-items-center gap-2 min-w-0">
                                                        <div class="dash-icon-box" style="background-color: #ecfdf5; color: #059669;">
                                                            <i class="fa-solid fa-users-line"></i>
                                                        </div>
                                                        <h6 class="fw-bold text-slate-900 mb-0 text-truncate">{{ __('Partneru reģistrs') }}</h6>
                                                    </div>
                                                    <span class="dash-action-badge badge-partners flex-shrink-0" title="{{ __('Kopā partneri') }}">
                                                        {{ $partnersCount }}
                                                    </span>
                                                </div>
                                                <p class="small text-muted mb-3">
                                                    {{ __('Klientu, piegādātāju un sadarbības partneru pārvaldība, reģistrācijas un PVN numuri.') }}
                                                </p>
                                            </div>
                                            <button class="btn btn-modern btn-modern-primary btn-sm w-100" wire:click.prevent="activateComponent('partners')">
                                                <i class="fa-solid fa-arrow-right me-1"></i> {{ __('Atvērt partnerus') }}
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Avansu norēķini -->
                                    <div class="col-12 col-sm-6">
                                        <div class="dash-action-card">
                                            <div>
                                                <div class="d-flex align-items-center justify-content-between mb-2 gap-2">
                                                    <div class="d-flex align-items-center gap-2 min-w-0">
                                                        <div class="dash-icon-box" style="background-color: #fffbeb; color: #d97706;">
                                                            <i class="fa-solid fa-money-bill-transfer"></i>
                                                        </div>
                                                        <h6 class="fw-bold text-slate-900 mb-0 text-truncate">{{ __('Avansu norēķini') }}</h6>
                                                    </div>
                                                    <span class="dash-action-badge badge-expenses flex-shrink-0" title="{{ __('Kopā avansu norēķini') }}">
                                                        {{ $expensesCount }}
                                                    </span>
                                                </div>
                                                <p class="small text-muted mb-3">
                                                    {{ __('Darbinieku izdevumu pārskati, čeku un izdevumu dokumentu uzskaite un kopsavilkumi.') }}
                                                </p>
                                            </div>
                                            <button class="btn btn-modern btn-modern-primary btn-sm w-100" wire:click.prevent="activateComponent('cash-expenses')">
                                                <i class="fa-solid fa-arrow-right me-1"></i> {{ __('Atvērt avansu norēķinus') }}
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Atvaļinājumi -->
                                    <div class="col-12 col-sm-6">
                                        <div class="dash-action-card">
                                            <div>
                                                <div class="d-flex align-items-center justify-content-between mb-2 gap-2">
                                                    <div class="d-flex align-items-center gap-2 min-w-0">
                                                        <div class="dash-icon-box" style="background-color: #f0fdfa; color: #0d9488;">
                                                            <i class="fa-solid fa-umbrella-beach"></i>
                                                        </div>
                                                        <h6 class="fw-bold text-slate-900 mb-0 text-truncate">{{ __('Atvaļinājumi') }}</h6>
                                                    </div>
                                                    <span class="dash-action-badge badge-vacations flex-shrink-0" title="{{ __('Kopā darbinieki') }}">
                                                        {{ $employeesCount }}
                                                    </span>
                                                </div>
                                                <p class="small text-muted mb-3">
                                                    {{ __('Darbinieku atvaļinājumu grafiki, atlikušo dienu aprēķins un atvaļinājumu uzskaite.') }}
                                                </p>
                                            </div>
                                            <button class="btn btn-modern btn-modern-secondary btn-sm w-100" wire:click.prevent="activateComponent('other.vacations')">
                                                <i class="fa-solid fa-arrow-right me-1"></i> {{ __('Atvērt atvaļinājumus') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Company Passport Card (Uzņēmuma vizītkarte) -->
                        <div class="col-12 col-xl-4">
                            <div class="dash-side-card p-3.5 p-sm-4 h-100">
                                <div class="d-flex align-items-center justify-content-between mb-3 gap-2">
                                    <h6 class="fw-bold text-slate-900 mb-0 d-flex align-items-center gap-2 text-nowrap">
                                        <i class="fa-solid fa-id-card-clip text-primary-500"></i>
                                        {{ __('Uzņēmuma vizītkarte') }}
                                    </h6>
                                    <span class="badge bg-slate-100 text-slate-700 font-monospace small flex-shrink-0">
                                        ID: {{ $selectedCompany->id }}
                                    </span>
                                </div>

                                <div class="dash-info-list mb-3">
                                    <div class="dash-info-row">
                                        <span class="dash-info-label">{{ __('Reģistrācijas Nr.') }}:</span>
                                        <div class="dash-info-val">
                                            <strong class="font-monospace text-slate-800">{{ $selectedCompany->reg_number ?: '-' }}</strong>
                                            @if(!empty($selectedCompany->reg_number))
                                                <button type="button" class="dash-copy-btn" title="{{ __('Kopēt reģistrācijas numuru') }}"
                                                        onclick="navigator.clipboard.writeText('{{ $selectedCompany->reg_number }}'); this.innerHTML='<i class=\'fa-solid fa-check text-success\'></i>'; setTimeout(() => this.innerHTML='<i class=\'fa-regular fa-copy\'></i>', 2000);">
                                                    <i class="fa-regular fa-copy"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="dash-info-row">
                                        <span class="dash-info-label">{{ __('PVN Nr.') }}:</span>
                                        <div class="dash-info-val">
                                            <strong class="font-monospace text-slate-800">{{ $vatNumber ?: __('Nav reģistrēts') }}</strong>
                                            @if(!empty($vatNumber))
                                                <button type="button" class="dash-copy-btn" title="{{ __('Kopēt PVN numuru') }}"
                                                        onclick="navigator.clipboard.writeText('{{ $vatNumber }}'); this.innerHTML='<i class=\'fa-solid fa-check text-success\'></i>'; setTimeout(() => this.innerHTML='<i class=\'fa-regular fa-copy\'></i>', 2000);">
                                                    <i class="fa-regular fa-copy"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="dash-info-row">
                                        <span class="dash-info-label">{{ __('Juridiskā adrese') }}:</span>
                                        <div class="dash-info-val">
                                            <span class="text-slate-800 text-truncate" style="max-width: 220px;" title="{{ $selectedCompany->address }}">
                                                {{ $selectedCompany->address ?: '-' }}
                                            </span>
                                            @if(!empty($selectedCompany->address))
                                                <button type="button" class="dash-copy-btn" title="{{ __('Kopēt adresi') }}"
                                                        onclick="navigator.clipboard.writeText('{{ addslashes($selectedCompany->address) }}'); this.innerHTML='<i class=\'fa-solid fa-check text-success\'></i>'; setTimeout(() => this.innerHTML='<i class=\'fa-regular fa-copy\'></i>', 2000);">
                                                    <i class="fa-regular fa-copy"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="dash-info-row">
                                        <span class="dash-info-label">{{ __('Banka') }}:</span>
                                        <div class="dash-info-val">
                                            <span class="text-slate-800 fw-medium text-truncate" style="max-width: 200px;" title="{{ $selectedCompany->bank }}">{{ $selectedCompany->bank ?: '-' }}</span>
                                            @if(!empty($selectedCompany->bank))
                                                <button type="button" class="dash-copy-btn" title="{{ __('Kopēt banku') }}"
                                                        onclick="navigator.clipboard.writeText('{{ addslashes($selectedCompany->bank) }}'); this.innerHTML='<i class=\'fa-solid fa-check text-success\'></i>'; setTimeout(() => this.innerHTML='<i class=\'fa-regular fa-copy\'></i>', 2000);">
                                                    <i class="fa-regular fa-copy"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="dash-info-row">
                                        <span class="dash-info-label">{{ __('SWIFT / BIC') }}:</span>
                                        <div class="dash-info-val">
                                            <span class="font-monospace text-slate-800">{{ $selectedCompany->swift ?: '-' }}</span>
                                            @if(!empty($selectedCompany->swift))
                                                <button type="button" class="dash-copy-btn" title="{{ __('Kopēt SWIFT / BIC') }}"
                                                        onclick="navigator.clipboard.writeText('{{ addslashes($selectedCompany->swift) }}'); this.innerHTML='<i class=\'fa-solid fa-check text-success\'></i>'; setTimeout(() => this.innerHTML='<i class=\'fa-regular fa-copy\'></i>', 2000);">
                                                    <i class="fa-regular fa-copy"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="dash-info-row">
                                        <span class="dash-info-label">{{ __('Konta Nr. (IBAN)') }}:</span>
                                        <div class="dash-info-val">
                                            <strong class="font-monospace text-slate-800 text-truncate" style="font-size: 0.8rem; letter-spacing: -0.01em;" title="{{ $selectedCompany->account_number }}">
                                                {{ $selectedCompany->account_number ?: '-' }}
                                            </strong>
                                            @if(!empty($selectedCompany->account_number))
                                                <button type="button" class="dash-copy-btn" title="{{ __('Kopēt konta numuru') }}"
                                                        onclick="navigator.clipboard.writeText('{{ $selectedCompany->account_number }}'); this.innerHTML='<i class=\'fa-solid fa-check text-success\'></i>'; setTimeout(() => this.innerHTML='<i class=\'fa-regular fa-copy\'></i>', 2000);">
                                                    <i class="fa-regular fa-copy"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                    @if(!empty($selectedCompany->closed_data_date) && $selectedCompany->closed_data_date !== '1970-01-01' && $selectedCompany->closed_data_date !== '01.01.1970')
                                        <div class="dash-info-row">
                                            <span class="dash-info-label">{{ __('Slēgti dati līdz') }}:</span>
                                            <span class="dash-lock-badge" title="{{ __('Grāmatvedības dati slēgti līdz šim datumam') }}">
                                                <i class="fa-solid fa-lock"></i>
                                                <span>{{ $selectedCompany->closed_data_date }}</span>
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <button class="btn btn-modern btn-modern-secondary btn-sm w-100"
                                        wire:click.prevent="activateComponent('other.company-data')">
                                    <i class="fa-solid fa-pen-to-square me-1"></i> {{ __('Labot uzņēmuma datus') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Secondary Workspace Row: Recent Invoices & Shortcuts -->
                    <div class="row g-4 mb-4">
                        <!-- Left: Recent Invoices Table Card -->
                        <div class="col-12 col-xl-8">
                            <div class="dash-side-card h-100">
                                <div class="p-3.5 px-4 bg-white border-bottom d-flex align-items-center justify-content-between">
                                    <h6 class="fw-bold text-slate-900 mb-0 d-flex align-items-center gap-2">
                                        <i class="fa-solid fa-clock-rotate-left text-primary-500"></i>
                                        {{ __('Pēdējie uzņēmuma rēķini') }}
                                    </h6>
                                    @if($invoicesCount > 0)
                                        <a href="#" class="btn btn-sm btn-link text-primary-600 text-decoration-none fw-semibold p-0"
                                           wire:click.prevent="activateComponent('invoices')">
                                            {{ __('Skatīt visus') }} ({{ $invoicesCount }}) <i class="fa-solid fa-arrow-right ms-1"></i>
                                        </a>
                                    @endif
                                </div>

                                @if($recentInvoices->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0" style="font-size: 0.875rem;">
                                            <thead class="bg-slate-50 text-muted small text-uppercase font-monospace border-bottom">
                                                <tr>
                                                    <th class="ps-4 py-2.5">{{ __('Datums') }}</th>
                                                    <th class="py-2.5">{{ __('Numurs') }}</th>
                                                    <th class="py-2.5">{{ __('Partneris') }}</th>
                                                    <th class="text-end py-2.5">{{ __('Summa') }}</th>
                                                    <th class="text-center pe-4 py-2.5">{{ __('Statuss') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($recentInvoices as $inv)
                                                    <tr style="cursor: pointer;" wire:click.prevent="activateComponent('invoices')">
                                                        <td class="ps-4 text-muted font-monospace py-2.5">{{ $inv->date }}</td>
                                                        <td class="fw-bold text-slate-900 font-monospace py-2.5">{{ $inv->number }}</td>
                                                        <td class="py-2.5 text-truncate" style="max-width: 220px;" title="{{ $inv->partner_name }}">
                                                            {{ $inv->partner_name ?: '-' }}
                                                        </td>
                                                        <td class="text-end font-monospace fw-bold text-slate-800 py-2.5">
                                                            € {{ number_format((float)$inv->amount_total, 2, '.', ' ') }}
                                                        </td>
                                                        <td class="text-center pe-4 py-2.5">
                                                            @if($inv->is_locked)
                                                                <span class="dash-status-pill status-locked">
                                                                    <i class="fa-solid fa-lock me-1"></i> {{ __('Slēgts') }}
                                                                </span>
                                                            @else
                                                                <span class="dash-status-pill status-open">
                                                                    <i class="fa-solid fa-circle-check me-1"></i> {{ __('Atvērts') }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="p-4 text-center text-muted">
                                        <i class="fa-solid fa-file-invoice text-slate-300 fs-1 mb-2"></i>
                                        <p class="mb-2 small">{{ __('Šim uzņēmumam vēl nav izveidotu rēķinu.') }}</p>
                                        <button class="btn btn-modern btn-modern-primary btn-sm" wire:click.prevent="activateComponent('invoices')">
                                            <i class="fa-solid fa-plus me-1"></i> {{ __('Izrakstīt pirmo rēķinu') }}
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Right: Quick Shortcuts Card -->
                        <div class="col-12 col-xl-4">
                            <div class="dash-side-card p-3.5 p-sm-4 h-100">
                                <h6 class="fw-bold text-slate-900 mb-3 d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-compass text-primary-500"></i>
                                    {{ __('Papildu moduļi un rīki') }}
                                </h6>

                                <div class="d-flex flex-column gap-2">
                                    <a href="#" class="dash-shortcut-item"
                                       wire:click.prevent="activateComponent('other.other-payment-receivers')">
                                        <div class="d-flex align-items-center gap-2.5">
                                            <div class="dash-shortcut-icon bg-primary-50 text-primary-600">
                                                <i class="fa-solid fa-building-columns"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-slate-900 small">{{ __('Maksājumu saņēmēji') }}</div>
                                                <div class="text-muted" style="font-size: 0.75rem;">{{ __('Fiziskās un juridiskās personas') }}</div>
                                            </div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right text-slate-400 small"></i>
                                    </a>

                                    @if(\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->isAdmin())
                                        <a href="#" class="dash-shortcut-item"
                                           wire:click.prevent="activateComponent('personal-income')">
                                            <div class="d-flex align-items-center gap-2.5">
                                                <div class="dash-shortcut-icon text-indigo-600" style="background-color: #eef2ff;">
                                                    <i class="fa-solid fa-user-group"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold text-slate-900 small">{{ __('IIN / Algas') }}</div>
                                                    <div class="text-muted" style="font-size: 0.75rem;">{{ __('Algu un ienākumu uzskaite') }}</div>
                                                </div>
                                            </div>
                                            <i class="fa-solid fa-chevron-right text-slate-400 small"></i>
                                        </a>
                                    @endif

                                    <a href="#" class="dash-shortcut-item"
                                       data-bs-toggle="modal" data-bs-target="#edsInfoModal">
                                        <div class="d-flex align-items-center gap-2.5">
                                            <div class="dash-shortcut-icon bg-primary-50 text-primary-600">
                                                <i class="fa-solid fa-circle-question"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-slate-900 small">{{ __('Sistēmas palīdzība') }}</div>
                                                <div class="text-muted" style="font-size: 0.75rem;">{{ __('Informācija un rokasgrāmata') }}</div>
                                            </div>
                                        </div>
                                        <i class="fa-solid fa-chevron-right text-slate-400 small"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($this->activeComponent() === 'invoices')
                <livewire:invoice-list :wire:key="'invoices-'.$companyId.'-'.$this->activeCompanyId"/>
            @elseif($this->activeComponent() === 'partners')
                <livewire:partner-list :wire:key="'partners-'.$companyId.'-'.$this->activeCompanyId"/>
            @elseif($this->activeComponent() === 'cash-expenses')
                <livewire:cash-expenses.cash-expenses-list :wire:key="'cash-'.$companyId.'-'.$this->activeCompanyId"/>
            @elseif($this->activeComponent() === 'other.company-data')
                <livewire:other.company-data :wire:key="'data-'.$companyId.'-'.$this->activeCompanyId"/>
            @elseif($this->activeComponent() === 'other.other-payment-receivers')
                <livewire:other.other-payment-receivers :wire:key="'receivers-'.$companyId.'-'.$this->activeCompanyId"/>
            @elseif($this->activeComponent() === 'personal-income')
                <livewire:personal-income.personal-income-list :wire:key="'income-'.$companyId.'-'.$this->activeCompanyId"/>
            @elseif($this->activeComponent() === 'other.vacations')
                <livewire:vacations.vacation-summary :wire:key="'vacations-'.$companyId.'-'.$this->activeCompanyId"/>
            @elseif($this->activeComponent() === 'profile')
                <livewire:user-profile :wire:key="'user-profile-'.\Illuminate\Support\Facades\Auth::id()"/>
            @endif
        </main>
    </div>

    <!-- Info & Help Modal -->
    <div class="modal fade" id="edsInfoModal" tabindex="-1" aria-labelledby="edsInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-light border-bottom">
                    <h5 class="modal-title fw-bold" id="edsInfoModalLabel">
                        <i class="fa-solid fa-circle-info text-primary me-2"></i> Sistēmas informācija
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Aizvērt"></button>
                </div>
                <div class="modal-body py-4">
                    <div class="text-center mb-3">
                        <div class="navbar-brand-badge mb-2">
                            <i class="fa-solid fa-calculator"></i>
                            <span>Auditors.lv</span>
                        </div>
                        <h6 class="fw-bold mt-2 mb-1">Auditors.lv grāmatvedības platforma</h6>
                    </div>
                    <ul class="list-group list-group-flush small">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Aktīvais lietotājs:</span>
                            <span class="fw-semibold">{{ \Illuminate\Support\Facades\Auth::user()->name ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">E-pasts:</span>
                            <span class="fw-semibold">{{ \Illuminate\Support\Facades\Auth::user()->email ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Uzņēmums:</span>
                            <span class="fw-semibold">{{ $selectedCompany->title ?? 'N/A' }}</span>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer border-top bg-light">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Aizvērt</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Full-Height Right User Sidebar / Offcanvas -->
    <div class="offcanvas offcanvas-end eds-user-offcanvas" 
         tabindex="-1" 
         id="userSidebarOffcanvas" 
         aria-labelledby="userSidebarOffcanvasLabel">
        <div class="offcanvas-header text-white p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                     style="width: 48px; height: 48px; font-size: 1.25rem; color: #002855; flex-shrink: 0;">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="min-w-0">
                    <h5 class="offcanvas-title fw-bold text-white mb-0 text-truncate" id="userSidebarOffcanvasLabel" style="font-size: 1.1rem; line-height: 1.25;">
                        {{ $userName }}
                    </h5>
                    <div class="text-white-50 small text-truncate mt-0" style="font-size: 0.785rem;">
                        {{ \Illuminate\Support\Facades\Auth::user()->email ?? '' }}
                    </div>
                    <div class="mt-2">
                        @if(\Auth::check() && \Auth::user()->isAdmin())
                            <span class="badge bg-warning text-dark px-2 py-1" style="font-size: 0.7rem; font-weight: 700;">
                                <i class="fa-solid fa-shield-halved me-1"></i> Administrators
                            </span>
                        @else
                            <span class="badge bg-white bg-opacity-25 text-white px-2 py-1" style="font-size: 0.7rem; font-weight: 600;">
                                <i class="fa-solid fa-user-shield me-1"></i> Lietotājs
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <button type="button" class="btn-close btn-close-white align-self-start" data-bs-dismiss="offcanvas" aria-label="Aizvērt"></button>
        </div>

        <div class="offcanvas-body d-flex flex-column justify-content-between p-0 bg-light">
            <div class="p-3">
                <div class="text-uppercase small fw-bold text-muted px-1 mb-2" style="font-size: 0.7rem; letter-spacing: 0.05em;">
                    Konta darbības
                </div>

                <div class="mb-3">
                    <a href="#" 
                       class="eds-user-nav-link"
                       data-bs-dismiss="offcanvas"
                       wire:click.prevent="activateComponent('profile')">
                        <div class="eds-user-nav-icon bg-primary-subtle text-primary">
                            <i class="fa-solid fa-user-gear"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <div class="fw-bold" style="font-size: 0.875rem;">Profila iestatījumi</div>
                            <div class="text-muted" style="font-size: 0.75rem;">Lietotāja vārds un e-pasts</div>
                        </div>
                        <i class="fa-solid fa-chevron-right text-muted small"></i>
                    </a>

                    @if(\Auth::check() && \Auth::user()->isAdmin())
                        <a href="{{ route('admin.home') }}" 
                           class="eds-user-nav-link">
                            <div class="eds-user-nav-icon bg-warning-subtle text-warning">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="fw-bold" style="font-size: 0.875rem;">Admin Panelis</div>
                                <div class="text-muted" style="font-size: 0.75rem;">Sistēmas un auditācijas žurnāls</div>
                            </div>
                            <i class="fa-solid fa-arrow-up-right-from-square text-muted small"></i>
                        </a>
                    @endif
                </div>

                @if($selectedCompany)
                    <div class="text-uppercase small fw-bold text-muted px-1 mb-2 mt-4" style="font-size: 0.7rem; letter-spacing: 0.05em;">
                        Aktīvais uzņēmums
                    </div>
                    <div class="card border shadow-sm rounded-3 p-3 mb-3 bg-white">
                        <div class="d-flex align-items-start gap-3">
                            <div class="eds-user-nav-icon bg-secondary-subtle text-secondary" style="width: 32px; height: 32px; font-size: 0.9rem;">
                                <i class="fa-solid fa-building"></i>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="fw-bold text-dark text-truncate" style="font-size: 0.875rem;">{{ $selectedCompany->title }}</div>
                                @if(!empty($selectedCompany->reg_number))
                                    <div class="text-muted small font-monospace" style="font-size: 0.75rem;">Reģ. Nr. {{ $selectedCompany->reg_number }}</div>
                                @endif
                                @if(!empty($selectedCompany->vat_number))
                                    <div class="text-muted small font-monospace" style="font-size: 0.75rem;">PVN: {{ $selectedCompany->vat_number }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="p-3 bg-white border-top">
                <a href="{{ route('logout') }}" 
                   class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2 py-2 fw-semibold shadow-sm">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i> Beigt darbu
                </a>
                <div class="text-center text-muted mt-2" style="font-size: 0.725rem;">
                    Auditors.lv sistēma &bull; Droša sesija
                </div>
            </div>
        </div>
    </div>
</div>
