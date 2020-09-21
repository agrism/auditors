<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title>auditors.lv : client</title>
    <meta name="description" content="">
    <meta name="author" content="">
    <META HTTP-EQUIV="CACHE-CONTROL" CONTENT="PUBLIC">
    <META HTTP-EQUIV="PRAGMA" CONTENT="PUBLIC">

    {{--<META NAME="ROBOTS" CONTENT="ALL">--}}


    @include('client.partials.head')
</head>
<body>

@section('navigation')
    <nav class="navigation">
        @include('client.partials.navigation')
    </nav>
@stop
@yield('navigation')

{{-- <div class="container"> --}}
<div class="col-md-offset-1 col-md-10">
    @include('includes.messages')
</div>
<div>
    @yield('content')
</div>

<div class="sidebar">
    @yield('sidebar')
</div>

<div>
    @yield('modals')
</div>

<div class="loading">
    <img src="{!! asset('/admin-assets/ajax-loader.gif')  !!}" class="ajax-loader"/>
    <div class="ajax-loader">Loading</div>
</div>
</body>

@include('client.partials.js')


<div>
    @yield('js')
</div>
</html>