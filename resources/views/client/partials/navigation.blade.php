@if( \App::bound('Company') )
    <nav class="navbar navbar-expand-lg navbar-dark navbar-modern sticky-top">
        <div class="container-fluid px-3">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url(route('client.companies.index')) }}">
                <span class="navbar-brand-badge"><i class="fa-solid fa-calculator me-1"></i> Auditors</span>
                <span class="text-white-50 small fw-normal d-none d-sm-inline">|</span>
                <span class="text-truncate small fw-semibold" style="max-width: 220px;" title="Switch Company">
                    <i class="fa-regular fa-building me-1 text-primary-300"></i>
                    {{ \App\Services\SelectedCompanyService::getCompany()->title ?? 'Select Company' }}
                    <i class="fa-solid fa-chevron-down text-white-50 ms-1" style="font-size: 0.7rem;"></i>
                </span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                    data-bs-target="#clientNavbarContent" aria-controls="clientNavbarContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="clientNavbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3 gap-1">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('client.partners.*') ? 'active' : '' }}"
                           href="{{ url(route('client.partners.index')) }}">
                            <i class="fa-solid fa-users-line me-1 opacity-75"></i> {{ __('Partners') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('client.invoices.*') ? 'active' : '' }}"
                           href="{{ url(route('client.invoices.index')) }}">
                            <i class="fa-solid fa-file-invoice-dollar me-1 opacity-75"></i> {{ __('Invoices') }}
                        </a>
                    </li>
                    @if(config('app.debug-available'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('client.personal-incomes.*') ? 'active' : '' }}"
                               href="{{ url(route('client.personal-incomes.index')) }}">
                                <i class="fa-solid fa-hand-holding-dollar me-1 opacity-75"></i> {{ __('Personal incomes') }}
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
                            <i class="fa-solid fa-gear me-1 opacity-75"></i> {{ __('Self data') }}
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
@endif