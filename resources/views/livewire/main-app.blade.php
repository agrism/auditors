<div>
    <nav class="navbar navbar-expand-xl navbar-dark navbar-modern sticky-top">
        <div class="container-fluid px-lg-4 px-3">
            <!-- Left: Brand & Company Switcher -->
            <div class="d-flex align-items-center gap-2 me-3">
                <a class="navbar-brand text-decoration-none m-0" href="#" wire:click.prevent="activateComponent('companies')">
                    <span class="navbar-brand-badge">
                        <i class="fa-solid fa-calculator"></i>
                        <span>Auditors</span>
                    </span>
                </a>

                <a href="#" wire:click.prevent="activateComponent('companies')"
                   class="company-switcher-pill"
                   title="{{ __('Switch active company') }}">
                    <i class="fa-solid fa-building text-primary-400"></i>
                    <span class="text-truncate" style="max-width: 190px;">
                        {{ \App\Services\AuthUser::instance()->selectedCompany()->title ?? __('Select Company') }}
                    </span>
                    <i class="fa-solid fa-chevron-down text-white-50 ms-1" style="font-size: 0.65rem;"></i>
                </a>
            </div>

            <!-- Mobile Hamburger Toggle -->
            <button class="navbar-toggler border-0 p-2" type="button" data-bs-toggle="collapse"
                    data-bs-target="#mainAppNavbar" aria-controls="mainAppNavbar"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation Links -->
            <div class="collapse navbar-collapse" id="mainAppNavbar">
                <ul class="navbar-nav me-auto mb-2 mb-xl-0 gap-1 pt-2 pt-xl-0">
                    <?php
                    $companyId = \App\Services\AuthUser::instance()->selectedCompanyId();
                    ?>
                    @foreach($this->getNav() as $sysName => $item)
                        {{-- Skip 'companies' in the center menu since it is already on the left brand area --}}
                        @if($sysName === 'companies')
                            @continue
                        @endif

                        @if(isset($item['items']))
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle"
                                   href="#"
                                   id="otherDropdown"
                                   role="button"
                                   data-bs-toggle="dropdown"
                                   aria-expanded="false">
                                    <i class="fa-solid fa-layer-group opacity-75"></i>
                                    <span>{{ __('More') }}</span>
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
                                                @if($subSysName === 'company-data')
                                                    <i class="fa-solid fa-id-card me-2 text-primary-400"></i>
                                                @elseif($subSysName === 'other-payment-receivers')
                                                    <i class="fa-solid fa-building-columns me-2 text-primary-400"></i>
                                                @elseif($subSysName === 'settings')
                                                    <i class="fa-solid fa-sliders me-2 text-primary-400"></i>
                                                @elseif($subSysName === 'vacations')
                                                    <i class="fa-solid fa-umbrella-beach me-2 text-primary-400"></i>
                                                @endif
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
                               @if($sysName === 'invoices')
                                   <i class="fa-solid fa-file-invoice-dollar opacity-75"></i>
                               @elseif($sysName === 'partners')
                                   <i class="fa-solid fa-users-line opacity-75"></i>
                               @elseif($sysName === 'cash-expenses')
                                   <i class="fa-solid fa-money-bill-transfer opacity-75"></i>
                               @elseif($sysName === 'personal-income')
                                   <i class="fa-solid fa-hand-holding-dollar opacity-75"></i>
                               @endif
                               <span>{{ $item['title'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>

                <!-- Right Utilities Toolbar -->
                <ul class="navbar-nav ms-auto align-items-xl-center gap-2 pt-2 pt-xl-0">
                    @if(\Auth::check() && \Auth::user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link nav-admin-badge" href="{{ url(route('admin.home')) }}" title="Pāriet uz Admin paneli">
                                <i class="fa-solid fa-shield-halved"></i>
                                <span>{{ __('Admin Panel') }}</span>
                            </a>
                        </li>
                    @endif

                    <li class="nav-item dropdown">
                        <?php
                        $user = explode(' ', \Illuminate\Support\Facades\Auth::user()->name ?? '')[0] ?? 'Lietotājs';
                        ?>
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 py-1 px-2 text-decoration-none"
                           data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">
                            <div class="nav-user-avatar">
                                {{ strtoupper(substr($user, 0, 1)) }}
                            </div>
                            <span class="fw-medium text-light" style="font-size: 0.85rem;">{{ $user }}</span>
                            <i class="fa-solid fa-chevron-down text-white-50 ms-1" style="font-size: 0.65rem;"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" style="min-width: 220px;">
                            <li>
                                <div class="px-3 py-2 border-bottom border-secondary mb-1">
                                    <div class="fw-bold text-light small">{{ \Illuminate\Support\Facades\Auth::user()->name ?? 'Lietotājs' }}</div>
                                    <div class="text-white-50" style="font-size: 0.75rem;">{{ \Illuminate\Support\Facades\Auth::user()->email ?? '' }}</div>
                                </div>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('client.user.edit') }}">
                                    <i class="fa-solid fa-user-gear me-2 text-primary-400"></i> {{ __('Profile Settings') }}
                                </a>
                            </li>
                            <li><hr class="dropdown-divider my-1 border-secondary"></li>
                            <li>
                                <a class="dropdown-item py-2 text-danger" href="{{ route('logout') }}">
                                    <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> {{ __('Log Out') }}
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="py-4">
        <div wire:loading style="position: fixed; top: 1.25rem; right: 1.25rem; z-index: 9999;">
            <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <div class="container-fluid px-lg-4 px-3">
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
