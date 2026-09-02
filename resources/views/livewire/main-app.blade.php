<div>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-modern sticky-top">
        <div class="container-fluid px-3">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#" wire:click.prevent="activateComponent('companies')">
                <span class="navbar-brand-badge"><i class="fa-solid fa-calculator me-1"></i> Auditors</span>
                <span class="text-white-50 small fw-normal d-none d-sm-inline">|</span>
                <span class="text-truncate small fw-semibold" style="max-width: 240px;" title="Switch Company">
                    <i class="fa-regular fa-building me-1 text-primary-300"></i>
                    {{ \App\Services\AuthUser::instance()->selectedCompany()->title ?? 'Select Company' }}
                    <i class="fa-solid fa-chevron-down text-white-50 ms-1" style="font-size: 0.7rem;"></i>
                </span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                    data-bs-target="#mainAppNavbar" aria-controls="mainAppNavbar"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainAppNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3 gap-1">
                    <?php
                    $companyId = \App\Services\AuthUser::instance()->selectedCompanyId();
                    ?>
                    @foreach($this->getNav() as $sysName => $item)
                        @if(isset($item['items']))
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle"
                                   href="#"
                                   id="otherDropdown"
                                   role="button"
                                   data-bs-toggle="dropdown"
                                   aria-expanded="false">
                                    <i class="fa-solid fa-layer-group me-1 opacity-75"></i> {{ __('Other') }}
                                </a>

                                <ul class="dropdown-menu dropdown-menu-dark shadow-lg border-0" aria-labelledby="otherDropdown">
                                    @foreach($item['items'] as $subSysName => $subItem)
                                        @if(!isset($subItem['available']) || !$subItem['available'])
                                            @continue
                                        @endif
                                        <li>
                                            <a class="dropdown-item py-2 @if($subItem['active']) active @endif"
                                               wire:click.prevent="activateComponent('{{$sysName.'.'.$subSysName}}')"
                                               href="#"
                                               role="button">
                                                {{ $subItem['title'] ?? '---' }}
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

                        <li class="nav-item">
                            <a class="nav-link @if($item['active']) active @endif"
                               href="#"
                               role="button"
                               wire:click.prevent="activateComponent('{{$sysName}}')">
                               @if($sysName === 'companies')
                                   <i class="fa-solid fa-building me-1 opacity-75"></i>
                               @elseif($sysName === 'invoices')
                                   <i class="fa-solid fa-file-invoice-dollar me-1 opacity-75"></i>
                               @elseif($sysName === 'partners')
                                   <i class="fa-solid fa-users-line me-1 opacity-75"></i>
                               @elseif($sysName === 'cash-expenses')
                                   <i class="fa-solid fa-money-bill-transfer me-1 opacity-75"></i>
                               @elseif($sysName === 'personal-income')
                                   <i class="fa-solid fa-hand-holding-dollar me-1 opacity-75"></i>
                               @endif
                               {{ $item['title'] }}
                            </a>
                        </li>
                    @endforeach

                    @if(\App\Services\AuthUser::instance()->userId() === 9)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle @if(request()->routeIs('client.companies.edit') || request()->routeIs('client.companies.bank.*') || request()->routeIs('client.companies.settings.*')) active @endif"
                               href="#"
                               id="selfDataDropdown"
                               role="button"
                               data-bs-toggle="dropdown"
                               aria-expanded="false">
                                <i class="fa-solid fa-gear me-1 opacity-75"></i> {{ __('Self data') }}
                            </a>

                            @if($companyId)
                                <ul class="dropdown-menu dropdown-menu-dark shadow-lg border-0" aria-labelledby="selfDataDropdown">
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ url(route('client.companies.edit', $companyId)) }}">
                                            <i class="fa-solid fa-id-card me-2 text-primary-400"></i> {{ __('Requisites') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ url(route('client.companies.bank.index')) }}">
                                            <i class="fa-solid fa-building-columns me-2 text-primary-400"></i> {{ __('Other payment receivers') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ url(route('client.companies.settings.index')) }}">
                                            <i class="fa-solid fa-sliders me-2 text-primary-400"></i> {{ __('Settings') }}
                                        </a>
                                    </li>
                                </ul>
                            @endif
                        </li>
                    @endif
                </ul>

                <ul class="navbar-nav ms-auto align-items-lg-center gap-2">
                    @if(\Auth::check() && \Auth::user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }} btn btn-sm btn-outline-light px-3 py-1"
                               href="{{ url(route('admin.home')) }}">
                                <i class="fa-solid fa-shield-halved me-1 text-warning"></i> Admin Panel
                            </a>
                        </li>
                    @endif

                    <li class="nav-item dropdown">
                        <?php
                        $user = explode(' ', \Illuminate\Support\Facades\Auth::user()->name ?? '')[0] ?? 'Account';
                        ?>
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                            <span class="rounded-circle bg-primary-600 text-white d-inline-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.85rem;">
                                {{ strtoupper(substr($user, 0, 1)) }}
                            </span>
                            <span class="fw-medium text-light">{{ $user }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0">
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('client.user.edit') }}">
                                    <i class="fa-solid fa-user-pen me-2 text-primary-600"></i> Profile Settings
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item py-2 text-danger" href="{{ route('logout') }}">
                                    <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Log Out
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="py-4">
        <div wire:loading style="position: fixed; top: 1rem; right: 1rem; z-index: 9999;">
            <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <div class="container-fluid px-lg-4">
            @if(isset($companyId) && !$companyId)
                <livewire:company-list/>
            @elseif($this->activeComponent() === 'companies')
                <livewire:company-list/>
            @elseif($this->activeComponent() === 'invoices')
                <livewire:invoice-list/>
            @elseif($this->activeComponent() === 'partners')
                <livewire:partner-list/>
            @elseif($this->activeComponent() === 'cash-expenses')
                <livewire:cash-expenses.cash-expenses-list/>
            @elseif($this->activeComponent() === 'other.company-data')
                <livewire:other.company-data/>
            @elseif($this->activeComponent() === 'other.other-payment-receivers')
                <livewire:other.other-payment-receivers/>
            @elseif($this->activeComponent() === 'personal-income')
                <livewire:personal-income.personal-income-list/>
            @elseif($this->activeComponent() === 'other.vacations')
                <livewire:vacations.vacation-summary/>
            @endif
        </div>
    </div>
</div>
