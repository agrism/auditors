<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@hasSection('title'){{ config('app.name', 'Auditors.lv') }} :: @yield('title')@else{{ config('app.name', 'Auditors.lv') }}@endif</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- FontAwesome & Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Datepicker & Plugins -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('build/virtual-select/virtual-select.min.css')}}">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Core JS Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.lv.min.js"></script>
    <script src="{{ URL::asset('build/admin-assets/accounting.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
    <script src="{{asset('build/virtual-select/virtual-select.min.js')}}"></script>

    @livewireStyles
</head>
<body id="app-layout" class="bg-light">
    {{ $slot }}

    @livewireScripts
    @stack('scripts')

    <script>
        function edsFilterTaxpayers(val) {
            val = (val || '').toLowerCase().trim();
            var items = document.querySelectorAll('.eds-taxpayer-list .eds-taxpayer-item');
            var visibleCount = 0;
            items.forEach(function(item) {
                var text = item.getAttribute('data-search-text') || '';
                if (!val || text.includes(val)) {
                    item.style.display = '';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });
            var noMatch = document.getElementById('edsTaxpayerNoMatch');
            if (noMatch) {
                noMatch.style.display = (visibleCount === 0) ? 'block' : 'none';
            }
        }
        window.edsFilterTaxpayers = edsFilterTaxpayers;

        function initDatepicker(selector) {
            $(selector).datepicker({
                language: 'lv',
                format: 'dd.mm.yyyy',
                weekStart: 1,
                todayBtn: "linked",
                todayHighlight: true,
                autoclose: true,
                clearBtn: true,
                daysOfWeekDisabled: [],
                daysOfWeekHighlighted: [0, 6],
            });
        }

        function autoResizeTextareas() {
            document.querySelectorAll('.invoice-lines-table textarea.line_title').forEach(function(el) {
                el.style.height = 'auto';
                var offset = el.offsetHeight - el.clientHeight;
                el.style.height = Math.max(31, el.scrollHeight + (offset > 0 ? offset : 2)) + 'px';
            });
        }
        window.autoResizeTextareas = autoResizeTextareas;

        function syncDocumentTitle() {
            var el = document.querySelector('[data-page-title]');
            if (el && el.dataset.pageTitle) {
                document.title = el.dataset.pageTitle;
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            syncDocumentTitle();
            autoResizeTextareas();
        });
        if (window.Livewire) {
            Livewire.hook('message.processed', function() {
                syncDocumentTitle();
                autoResizeTextareas();
            });
        } else {
            document.addEventListener('livewire:load', function() {
                if (window.Livewire) {
                    Livewire.hook('message.processed', function() {
                        syncDocumentTitle();
                        autoResizeTextareas();
                    });
                }
                autoResizeTextareas();
            });
        }
    </script>
    @include('includes.bug-report')
</body>
</html>

