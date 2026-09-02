@if( \App::bound('Company') )
    <nav class="navbar navbar-expand-xl navbar-dark navbar-modern sticky-top">
        <div class="container-fluid px-lg-4 px-3">
            <!-- Left: Brand & Company Switcher -->
            <div class="d-flex align-items-center gap-2 me-3">
                <a class="navbar-brand text-decoration-none m-0" href="{{ url(route('client.companies.index')) }}">
                    <span class="navbar-brand-badge">
                        <i class="fa-solid fa-calculator"></i>
                        <span>Auditors</span>
                    </span>
                </a>

                <a href="{{ url(route('client.companies.index')) }}"
                   class="company-switcher-pill"
                   title="{{ __('Switch active company') }}">
                    <i class="fa-solid fa-building text-primary-400"></i>
                    <span class="text-truncate" style="max-width: 190px;">
                        {{ \App\Services\SelectedCompanyService::getCompany()->title ?? __('Select Company') }}
                    </span>
                    <i class="fa-solid fa-chevron-down text-white-50 ms-1" style="font-size: 0.65rem;"></i>
                </a>
            </div>

            <!-- Mobile Hamburger Toggle -->
            <button class="navbar-toggler border-0 p-2" type="button" data-bs-toggle="collapse"
                    data-bs-target="#clientNavbarContent" aria-controls="clientNavbarContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navigation Links -->
            <div class="collapse navbar-collapse" id="clientNavbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-xl-0 gap-1 pt-2 pt-xl-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('client.invoices.*') ? 'active' : '' }}"
                           href="{{ url(route('client.invoices.index')) }}">
                            <i class="fa-solid fa-file-invoice-dollar opacity-75"></i>
                            <span>{{ __('Invoices') }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('client.partners.*') ? 'active' : '' }}"
                           href="{{ url(route('client.partners.index')) }}">
                            <i class="fa-solid fa-users-line opacity-75"></i>
                            <span>{{ __('Partners') }}</span>
                        </a>
                    </li>
                    @if(config('app.debug-available'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('client.personal-incomes.*') ? 'active' : '' }}"
                               href="{{ url(route('client.personal-incomes.index')) }}">
                                <i class="fa-solid fa-hand-holding-dollar opacity-75"></i>
                                <span>{{ __('Personal incomes') }}</span>
                            </a>
                        </li>
                    @endif

                    <?php
                    $companyId = \App\Services\SelectedCompanyService::getCompanyId();
                    ?>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ (request()->routeIs('client.companies.edit') || request()->routeIs('client.companies.bank.*') || request()->routeIs('client.companies.settings.*')) ? 'active' : '' }}"
                           href="#"
                           id="companySettingsDropdown"
                           role="button"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">
                            <i class="fa-solid fa-layer-group opacity-75"></i>
                            <span>{{ __('More') }}</span>
                        </a>

                        @if($companyId)
                            <ul class="dropdown-menu dropdown-menu-dark shadow-lg border-0" aria-labelledby="companySettingsDropdown">
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
@endif