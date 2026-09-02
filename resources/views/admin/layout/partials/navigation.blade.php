<nav class="navbar navbar-expand-lg navbar-dark navbar-modern sticky-top">
    <div class="container-fluid px-3">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url(route('admin.home')) }}">
            <span class="navbar-brand-badge bg-danger"><i class="fa-solid fa-shield-halved me-1"></i> Admin Panelis</span>
            <span class="text-white-50 small fw-normal d-none d-sm-inline">|</span>
            <span class="small fw-semibold text-light">Auditors.lv</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#adminNavbarContent" aria-controls="adminNavbarContent"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNavbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-3 gap-1">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}"
                       href="{{ url(route('admin.home')) }}">
                        <i class="fa-solid fa-building me-1 opacity-75"></i> Uzņēmumi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                       href="{{ url(route('admin.users.index')) }}">
                        <i class="fa-solid fa-users me-1 opacity-75"></i> Lietotāji
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}"
                       href="{{ url(route('admin.invoices.index')) }}">
                        <i class="fa-solid fa-file-invoice-dollar me-1 opacity-75"></i> Rēķini
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ (request()->routeIs('admin.export') || request()->routeIs('admin.npi') || request()->routeIs('admin.working-hours.*') || request()->routeIs('admin.vacations.*') || request()->routeIs('admin.vat.*')) ? 'active' : '' }}"
                       href="#"
                       id="adminExportDropdown"
                       role="button"
                       data-bs-toggle="dropdown"
                       aria-expanded="false">
                        <i class="fa-solid fa-file-export me-1 opacity-75"></i> Eksports un atskaites
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark shadow-lg border-0" aria-labelledby="adminExportDropdown">
                        <li>
                            <a class="dropdown-item py-2 {{ request()->routeIs('admin.export') ? 'active' : '' }}"
                               href="{{ url(route('admin.export')) }}">
                                <i class="fa-solid fa-download me-2 text-primary-400"></i> Datu eksports
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 {{ request()->routeIs('admin.npi') ? 'active' : '' }}"
                               href="{{ url(route('admin.npi')) }}">
                                <i class="fa-solid fa-receipt me-2 text-primary-400"></i> NPI
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 {{ request()->routeIs('admin.working-hours.*') ? 'active' : '' }}"
                               href="{{ url(route('admin.working-hours.index')) }}">
                                <i class="fa-solid fa-business-time me-2 text-primary-400"></i> Darba stundas
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 {{ request()->routeIs('admin.vacations.*') ? 'active' : '' }}"
                               href="{{ url(route('admin.vacations.index')) }}">
                                <i class="fa-solid fa-umbrella-beach me-2 text-primary-400"></i> Atvaļinājumi
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 {{ request()->routeIs('admin.vat.*') ? 'active' : '' }}"
                               href="{{ url(route('admin.vat.index')) }}">
                                <i class="fa-solid fa-percent me-2 text-primary-400"></i> PVN deklarācija
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ (request()->routeIs('admin.roles.*') || request()->routeIs('admin.permissions.*')) ? 'active' : '' }}"
                       href="#"
                       id="adminRolesDropdown"
                       role="button"
                       data-bs-toggle="dropdown"
                       aria-expanded="false">
                        <i class="fa-solid fa-user-shield me-1 opacity-75"></i> Lomas un tiesības
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark shadow-lg border-0" aria-labelledby="adminRolesDropdown">
                        <li>
                            <a class="dropdown-item py-2 {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}"
                               href="{{ url(route('admin.roles.index')) }}">
                                <i class="fa-solid fa-id-badge me-2 text-primary-400"></i> Lomas
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}"
                               href="{{ url(route('admin.permissions.index')) }}">
                                <i class="fa-solid fa-key me-2 text-primary-400"></i> Tiesības
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-lg-center gap-2">
                <li class="nav-item">
                    <a class="nav-link btn btn-sm btn-outline-info text-info px-3 py-1"
                       href="{{ url(route('client.index')) }}">
                        <i class="fa-solid fa-arrow-left me-1"></i> Klientu portāls
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="{{ route('logout') }}" title="Iziet">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>