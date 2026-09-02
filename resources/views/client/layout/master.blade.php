<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Auditors.lv') }}</title>

    @include('client.partials.head')
    @yield('style')
    @livewireStyles
</head>
<body class="bg-light">

@section('navigation')
    @include('client.partials.navigation')
@stop
@yield('navigation')

<main role="main" class="py-4">
    <div class="container-fluid px-lg-4">
        @include('includes.messages')
        @yield('content')
    </div>
</main>

@yield('sidebar')
@yield('modals')

@include('client.partials.js')
@livewireScripts
@yield('js')
</body>
</html>
