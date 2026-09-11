<aside class="eds-sidebar" id="edsSidebar">
    <!-- Logo & Brand Header: Auditors.lv Admin -->
    <div class="eds-brand-header">
        <a class="eds-brand-link text-decoration-none d-inline-flex flex-column align-items-center justify-content-center" href="{{ route('admin.home') }}">
            <div class="eds-logo-container">
                <div class="eds-logo-icon">
                    <svg width="28" height="28" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="32" height="32" rx="7" fill="url(#auditors_admin_brand_grad)" />
                        <!-- Modern financial / ledger mark -->
                        <rect x="7" y="7" width="18" height="18" rx="3" stroke="#ffffff" stroke-width="1.6" stroke-opacity="0.9" fill="none"/>
                        <rect x="10" y="10.5" width="12" height="2.8" rx="1" fill="#ffffff" fill-opacity="0.95"/>
                        <circle cx="11.5" cy="17.5" r="1.4" fill="#38bdf8"/>
                        <circle cx="16" cy="17.5" r="1.4" fill="#ffffff" fill-opacity="0.9"/>
                        <circle cx="20.5" cy="17.5" r="1.4" fill="#ffffff" fill-opacity="0.9"/>
                        <path d="M10.5 21.5H21.5" stroke="#ffffff" stroke-width="1.4" stroke-linecap="round" stroke-opacity="0.6"/>
                        <defs>
                            <linearGradient id="auditors_admin_brand_grad" x1="0" y1="0" x2="32" y2="32" gradientUnits="userSpaceOnUse">
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
            <span class="eds-brand-sub text-danger fw-bold"><i class="fa-solid fa-shield-halved me-1"></i> Admin Panelis</span>
        </a>
    </div>

    <!-- Sidebar Navigation Menu -->
    <ul class="eds-sidebar-menu">
        <!-- Uzņēmumi -->
        <li class="eds-menu-item">
            <a href="{{ route('admin.home') }}"
               class="eds-menu-link {{ (request()->routeIs('admin.home') || request()->routeIs('admin.companies.*')) ? 'active' : '' }}">
                <span class="d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-building opacity-75"></i>
                    <span>Uzņēmumi</span>
                </span>
            </a>
        </li>

        <!-- Lietotāji -->
        <li class="eds-menu-item">
            <a href="{{ route('admin.users.index') }}"
               class="eds-menu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <span class="d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-users opacity-75"></i>
                    <span>Lietotāji</span>
                </span>
            </a>
        </li>

        <!-- Rēķini -->
        <li class="eds-menu-item">
            <a href="{{ route('admin.invoices.index') }}"
               class="eds-menu-link {{ request()->routeIs('admin.invoices.*') ? 'active' : '' }}">
                <span class="d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-file-invoice-dollar opacity-75"></i>
                    <span>Rēķini</span>
                </span>
            </a>
        </li>

        <!-- Aktivitātes žurnāls -->
        <li class="eds-menu-item">
            <a href="{{ route('admin.logs.index') }}"
               class="eds-menu-link {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}">
                <span class="d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-list-check opacity-75"></i>
                    <span>Žurnāls</span>
                </span>
            </a>
        </li>

        <!-- Eksports un atskaites (Submenu) -->
        @php
            $isExportActive = request()->routeIs('admin.export') || request()->routeIs('admin.npi*') || request()->routeIs('admin.working-hours.*') || request()->routeIs('admin.vacations.*') || request()->routeIs('admin.vat.*');
        @endphp
        <li class="eds-menu-item">
            <a class="eds-menu-link d-flex justify-content-between align-items-center {{ $isExportActive ? 'active' : '' }}"
               data-bs-toggle="collapse"
               href="#adminMenuExport"
               role="button"
               aria-expanded="{{ $isExportActive ? 'true' : 'false' }}"
               aria-controls="adminMenuExport">
                <span class="d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-file-export opacity-75"></i>
                    <span>Eksports & Atskaites</span>
                </span>
                <i class="fa-solid fa-chevron-down eds-chevron"></i>
            </a>
            <ul class="collapse {{ $isExportActive ? 'show' : '' }} eds-submenu" id="adminMenuExport">
                <li>
                    <a href="{{ route('admin.export') }}"
                       class="eds-submenu-link {{ request()->routeIs('admin.export') ? 'active' : '' }}">
                        <span class="d-inline-flex align-items-center gap-2">
                            <i class="fa-solid fa-download opacity-75"></i>
                            <span>Datu eksports</span>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.npi') }}"
                       class="eds-submenu-link {{ request()->routeIs('admin.npi*') ? 'active' : '' }}">
                        <span class="d-inline-flex align-items-center gap-2">
                            <i class="fa-solid fa-receipt opacity-75"></i>
                            <span>NPI</span>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.working-hours.index') }}"
                       class="eds-submenu-link {{ request()->routeIs('admin.working-hours.*') ? 'active' : '' }}">
                        <span class="d-inline-flex align-items-center gap-2">
                            <i class="fa-solid fa-business-time opacity-75"></i>
                            <span>Darba stundas</span>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.vacations.index') }}"
                       class="eds-submenu-link {{ request()->routeIs('admin.vacations.*') ? 'active' : '' }}">
                        <span class="d-inline-flex align-items-center gap-2">
                            <i class="fa-solid fa-umbrella-beach opacity-75"></i>
                            <span>Atvaļinājumi</span>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.vat.index') }}"
                       class="eds-submenu-link {{ request()->routeIs('admin.vat.*') ? 'active' : '' }}">
                        <span class="d-inline-flex align-items-center gap-2">
                            <i class="fa-solid fa-percent opacity-75"></i>
                            <span>PVN deklarācija</span>
                        </span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Lomas un tiesības (Submenu) -->
        @php
            $isRolesActive = request()->routeIs('admin.roles.*') || request()->routeIs('admin.permissions.*');
        @endphp
        <li class="eds-menu-item">
            <a class="eds-menu-link d-flex justify-content-between align-items-center {{ $isRolesActive ? 'active' : '' }}"
               data-bs-toggle="collapse"
               href="#adminMenuRoles"
               role="button"
               aria-expanded="{{ $isRolesActive ? 'true' : 'false' }}"
               aria-controls="adminMenuRoles">
                <span class="d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-user-shield opacity-75"></i>
                    <span>Lomas un tiesības</span>
                </span>
                <i class="fa-solid fa-chevron-down eds-chevron"></i>
            </a>
            <ul class="collapse {{ $isRolesActive ? 'show' : '' }} eds-submenu" id="adminMenuRoles">
                <li>
                    <a href="{{ route('admin.roles.index') }}"
                       class="eds-submenu-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                        <span class="d-inline-flex align-items-center gap-2">
                            <i class="fa-solid fa-id-badge opacity-75"></i>
                            <span>Lomas</span>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.permissions.index') }}"
                       class="eds-submenu-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                        <span class="d-inline-flex align-items-center gap-2">
                            <i class="fa-solid fa-key opacity-75"></i>
                            <span>Tiesības</span>
                        </span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Return to Client Portal -->
        <li class="eds-menu-item">
            <a href="{{ route('client.index') }}" class="eds-menu-link text-primary fw-semibold">
                <span class="d-inline-flex align-items-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i>
                    <span>Klientu portāls</span>
                </span>
            </a>
        </li>
    </ul>

    <!-- Sidebar Footer -->
    <div class="eds-sidebar-footer">
        <a href="{{ route('logout') }}" class="eds-logout-link">
            <i class="fa-solid fa-arrow-right-from-bracket me-2 text-secondary"></i>
            <span>{{ __('Iziet') }}</span>
        </a>
    </div>
</aside>