<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Application</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <style>
        .htmx-indicator {
            display: none;
            pointer-events: all;
        }

        .htmx-request .htmx-indicator {
            display: flex !important;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100vw;
            height: 100vh;
        }

        .htmx-request.htmx-indicator {
            display: flex !important;
        }
    </style>
</head>
<body class="bg-gray-100" hx-indicator="#global-spinner">

<div id="global-spinner"
     class="htmx-indicator fixed inset-0 flex items-center justify-center bg-black/20 z-[9999]">
{{--    <svg class="animate-spin h-8 w-8 text-blue-900" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">--}}
{{--        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>--}}
{{--        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>--}}
{{--    </svg>--}}


    <svg class="animate-spin h-8 w-8 text-blue-900" viewBox="0 0 24 24">
        <!-- Light background circle -->
        <circle
            class="opacity-5"
            cx="12"
            cy="12"
            r="10"
            stroke="currentColor"
            stroke-width="4"
            fill="none"
        />
        <!-- Spinning part -->
        <circle
            class="opacity-95"
            cx="12"
            cy="12"
            r="10"
            stroke="currentColor"
            stroke-width="4"
            stroke-dasharray="58 200"
            fill="none"
        />
    </svg>

{{--    <svg class="animate-spin h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">--}}
{{--        <circle class="opacity-25 text-blue-900" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>--}}
{{--        <path class="opacity-75 text-white" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>--}}
{{--    </svg>--}}
</div>

<!-- Top Navigation Bar -->
<header class="fixed top-0 left-0 right-0 h-10 bg-blue-900 text-white border-b border-blue-800 flex items-center justify-between1 px-4 lg:pl-72 z-30">
    <!-- Mobile Menu Button -->
    <button id="mainMenuBtn" class="lg:hidden pr-2 rounded-lg hover:bg-blue-800">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>



    <!-- Header Right Items -->
    <div class="flex items-end space-x-4 order-2 ml-auto">
        <!-- Company Selector Button - Moves to left on mobile -->
        <button id="companyMenuBtn" class="flex items-center space-x-2 px-3 py-0 rounded-lg hover:bg-blue-800 order-ddd-1 lg:1order-none">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <div class="text-left">
                <span class="block text-xs scale-y-75 uppercase text-left -pt-0">Uzņēmums</span>
                <div class="overflow-hidden text-ellipsis whitespace-nowrap max-w-[140px] sm:max-w-[300px] md:max-w-[400px] text-xs pt-0 -pb-1 mb-0"
                     id="selected-company-name"
                >
                    {{\App\Services\V2\UserCompanyHelper::instance()->getSelectedCompany()?->title ?: '-'}}
                </div>
            </div>

        </button>
        <button id="userMenuBtn" class="p-2 hover:bg-blue-800 rounded-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
        </button>
        <button class="p-2 hover:bg-blue-800 rounded-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>

        </button>
    </div>
</header>

<!-- Main Sidebar Navigation -->
<aside id="mainNav" class="fixed top-0 left-0 h-full w-48 bg-white border-r border-gray-200 z-40 transform -translate-x-full lg:translate-x-0 transition-transform duration-200">
    <!-- Company Logo -->
    <div class="h-10 flex items-center justify-center1 border-b border-gray-200 bg-blue-900 text-white">
        <div class="px-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </div>
        <span class="text-lg font-semibold">Darba virsma</span>
    </div>

    <!-- Main Navigation Menu -->
    <!-- Navigation Menu -->
    <nav class="py-4">
        <div class="px-3 pb-2">
            <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Finanses</h2>
        </div>

        <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-900 bg-gray-100 border-l-4 border-blue-600">
            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>

            <span>Rēķini</span>
        </a>

        <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-50">
            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span>Partneri</span>
        </a>

        <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-50">
            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Avansu norēķini</span>
        </a>
    </nav>
</aside>

<!-- Company Selection Sidebar -->
<aside id="companyNav" class="fixed top-0 right-0 h-full w-86 bg-white border-l border-gray-200 z-50 transform translate-x-full transition-transform duration-200">
    <div class="h-10 flex items-center justify-between px-4 border-b border-gray-200 bg-blue-900 text-white">
        <span class="text-lg font-semibold">Izvēlies uzņēmumu</span>
        <button id="closeCompanyNav" class="p-2 hover:bg-blue-800 rounded-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    <div id="customer-nav-list">
        <x-v2.client.customer-list></x-v2.client.customer-list>
    </div>


</aside>

<!-- User Profile Sidebar -->
<aside id="userNav" class="fixed top-0 right-0 h-full w-64 bg-white border-l border-gray-200 z-50 transform translate-x-full transition-transform duration-200">
    <div class="h-10 flex items-center justify-between px-4 border-b border-gray-200 bg-blue-900 text-white">
        <span class="text-lg font-semibold">User Profile</span>
        <button id="closeUserNav" class="p-2 hover:bg-blue-800 rounded-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <nav class="py-4 overflow-y-auto h-[calc(100vh-4rem)]">
        <div class="px-4 py-3 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="h-10 w-10 rounded-full bg-blue-800"></div>
                <div>
                    <div class="font-medium text-sm">John Doe</div>
                    <div class="text-sm text-gray-500">john@example.com</div>
                </div>
            </div>
        </div>

        <div class="px-3 py-3">
            <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Account</h2>
        </div>

        <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-50">
            <span>Profile Settings</span>
        </a>
        <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-50">
            <span>Preferences</span>
        </a>
        <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-50">
            <span>Security</span>
        </a>
        <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-50">
            <span>Notifications</span>
        </a>

        <div class="px-3 py-3 mt-4">
            <h2 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">System</h2>
        </div>
        <a href="#" class="flex items-center px-3 py-2 text-sm text-gray-600 hover:bg-gray-50">
            <span>Help & Support</span>
        </a>
        <a href="#" class="flex items-center px-3 py-2 text-sm text-red-600 hover:bg-gray-50">
            <span>Sign Out</span>
        </a>
    </nav>
</aside>

<!-- Main Content Area -->
<main class="lg:ml-48 pt-12 min-h-screen bg-gray-100">
    <div class="p-6">
        <h1 class="text-xl font-semibold text-gray-900">Dashboard</h1>
        <div class="mt-2 bg-white rounded-lg shadow p-6">
            <p>Your content goes here...</p>
        </div>
    </div>
</main>

<!-- Overlay for mobile -->
<div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden lg:hidden"></div>
<div id="empty"></div>
<script>
    // Elements
    const mainMenuBtn = document.getElementById('mainMenuBtn');
    const companyMenuBtn = document.getElementById('companyMenuBtn');
    const userMenuBtn = document.getElementById('userMenuBtn');
    const closeCompanyNav = document.getElementById('closeCompanyNav');
    const closeUserNav = document.getElementById('closeUserNav');
    const mainNav = document.getElementById('mainNav');
    const companyNav = document.getElementById('companyNav');
    const userNav = document.getElementById('userNav');
    const overlay = document.getElementById('overlay');

    // Toggle functions
    function toggleMainNav() {
        mainNav.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }

    function toggleCompanyNav() {
        companyNav.classList.toggle('translate-x-full');
        overlay.classList.toggle('hidden');
    }

    function toggleUserNav() {
        userNav.classList.toggle('translate-x-full');
        overlay.classList.toggle('hidden');
    }

    // Event listeners
    mainMenuBtn.addEventListener('click', toggleMainNav);
    companyMenuBtn.addEventListener('click', toggleCompanyNav);
    userMenuBtn.addEventListener('click', toggleUserNav);
    closeCompanyNav.addEventListener('click', toggleCompanyNav);
    closeUserNav.addEventListener('click', toggleUserNav);

    // Close all navs when clicking overlay
    overlay.addEventListener('click', () => {
        mainNav.classList.add('-translate-x-full');
        companyNav.classList.add('translate-x-full');
        userNav.classList.add('translate-x-full');
        overlay.classList.add('hidden');
    });

    // function removeActive() {
    //     // Remove class from all elements first
    //     document.querySelectorAll('#companyNav *').forEach(element => {
    //         element.classList.remove('border-blue-600');  // example class name
    //     });
    //
    //     // toggleCompanyNav();
    //
    //     // Optionally add class to clicked element
    //     // e.target.classList.add('active');  // if you want to add class to clicked element
    // };
</script>
</body>
</html>
