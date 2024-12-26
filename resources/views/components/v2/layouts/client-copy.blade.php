<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'auditors') }}</title>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <style>


        .indicator {
            display: none;
        }
        .htmx-request.indicator {
            display: flex;
        }

    </style>
</head>
<body class="bg-gray-100 min-h-screen flex justify-center" hx-boost="true" hx-indicator="#status">
    <script>
        document.body.addEventListener('htmx:configRequest', (event) => {
            event.detail.headers['X-CSRF-TOKEN'] = '{{ csrf_token() }}';
        });
    </script>



    <div id="status" class="indicator fixed top-0 left-0 w-full h-full bg-black/5 flex items-center justify-center">
        <div class="bg-white p-4 rounded-lg shadow-lg flex items-center space-x-3">
            <div class="animate-spin h-5 w-5 border-2 border-blue-500 rounded-full border-t-transparent"></div>
            <span class="text-gray-700">Loading...</span>
        </div>
    </div>

    <div class="container mx-auto px-0 py-0 max-w-7xl">
        <x-v2.client-menu :menu="$menu ?? []"></x-v2.client-menu>client.blade.php
        <div class="max-w-7xl mx-auto py-3">
            {{ $slot }}
        </div>

    </div>
    <script>
        function toggleMenu() {
            const menu = document.querySelector('.mobile-menu');
            menu.classList.toggle('hidden');
        }
    </script>
    <!-- Loading indicator -->
    <div id="global-loader" class="htmx-indicator fixed top-0 left-0 w-full h-1">
        <div class="h-full bg-blue-500 animate-[loader_2s_ease-in-out_infinite]"></div>
    </div>
</body>
</html>
