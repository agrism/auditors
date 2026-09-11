<!doctype html>
<html lang="lv">
<head>
    @include('admin.layout.partials.head')
</head>
<body class="admin-panel-body">

<div class="eds-app-wrapper eds-admin-app-wrapper">
    @include('admin.layout.partials.navigation')

    <div class="eds-main-layout eds-admin-main-layout">
        <!-- Admin Top Navigation Header -->
        <header class="eds-topbar eds-admin-topbar">
            @php
                $adminTitle = 'Auditors.lv :: Administrācijas panelis';
                if (request()->routeIs('admin.home') || request()->routeIs('admin.companies.*')) {
                    $adminTitle = 'Auditors.lv :: Uzņēmumi';
                } elseif (request()->routeIs('admin.users.*')) {
                    $adminTitle = 'Auditors.lv :: Lietotāji';
                } elseif (request()->routeIs('admin.invoices.*')) {
                    $adminTitle = 'Auditors.lv :: Rēķini';
                } elseif (request()->routeIs('admin.logs.*')) {
                    $adminTitle = 'Auditors.lv :: Aktivitātes žurnāls';
                } elseif (request()->routeIs('admin.bug-reports.*')) {
                    $adminTitle = 'Auditors.lv :: Saziņas un ziņojumi';
                } elseif (request()->routeIs('admin.export')) {
                    $adminTitle = 'Auditors.lv :: Datu eksports';
                } elseif (request()->routeIs('admin.npi*')) {
                    $adminTitle = 'Auditors.lv :: NPI';
                } elseif (request()->routeIs('admin.working-hours.*')) {
                    $adminTitle = 'Auditors.lv :: Darba stundas';
                } elseif (request()->routeIs('admin.vacations.*')) {
                    $adminTitle = 'Auditors.lv :: Atvaļinājumi';
                } elseif (request()->routeIs('admin.vat.*')) {
                    $adminTitle = 'Auditors.lv :: PVN deklarācija';
                } elseif (request()->routeIs('admin.roles.*')) {
                    $adminTitle = 'Auditors.lv :: Lomas';
                } elseif (request()->routeIs('admin.permissions.*')) {
                    $adminTitle = 'Auditors.lv :: Tiesības';
                }
            @endphp

            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-link text-white p-0 d-inline-flex align-items-center me-1 d-lg-none"
                        type="button"
                        onclick="toggleAdminSidebar()"
                        title="Atvērt izvēlni">
                    <i class="fa-solid fa-bars fs-5"></i>
                </button>
                <h1 class="eds-topbar-title">@yield('title', $adminTitle)</h1>
            </div>

            <div class="eds-topbar-controls">
                <a href="{{ route('client.index') }}" class="eds-topbar-btn" title="Pāriet uz klientu portālu">
                    <i class="fa-solid fa-arrow-left eds-topbar-icon"></i>
                    <span class="eds-topbar-text eds-label-wide">Klientu portāls</span>
                </a>

                @if(\Auth::check())
                    <div class="dropdown">
                        <a href="#" class="eds-topbar-btn" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-solid fa-circle-user eds-topbar-icon"></i>
                            <span class="eds-topbar-text eds-label-wide">{{ \Auth::user()->name }}</span>
                            <i class="fa-solid fa-caret-down eds-topbar-caret eds-label-wide"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" style="min-width: 220px;">
                            <li>
                                <div class="px-3 py-2 border-bottom mb-1">
                                    <div class="fw-bold text-dark small">{{ \Auth::user()->name }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">{{ \Auth::user()->email }}</div>
                                </div>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('client.index') }}">
                                    <i class="fa-solid fa-house me-2 text-primary"></i> Klientu portāls
                                </a>
                            </li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <a class="dropdown-item py-2 text-danger" href="{{ route('logout') }}">
                                    <i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Iziet
                                </a>
                            </li>
                        </ul>
                    </div>
                @endif
            </div>
        </header>

        <!-- Main Content Area -->
        <main role="main" class="py-4 flex-grow-1">
            <div class="container-fluid px-lg-4 px-3">
                @include('includes.messages')
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Mobile Sidebar Backdrop -->
    <div class="eds-sidebar-backdrop d-lg-none" id="edsSidebarBackdrop" style="display: none;" onclick="toggleAdminSidebar()"></div>
</div>

@yield('sidebar')

@include('admin.layout.partials.js')
<script>
    function toggleAdminSidebar() {
        var sidebar = document.getElementById('edsSidebar');
        var backdrop = document.getElementById('edsSidebarBackdrop');
        if (sidebar) {
            sidebar.classList.toggle('show');
            if (backdrop) {
                backdrop.style.display = sidebar.classList.contains('show') ? 'block' : 'none';
            }
        }
    }
</script>
@yield('js')
</body>


</html>