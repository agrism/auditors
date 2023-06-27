<html>
<head>
    @include('admin.layout.partials.head')
</head>
<body>

@include('admin.layout.partials.navigation')

@include('includes.messages')
<div class="container">

    @yield('content')
</div>

<div class="sidebar">
    @yield('sidebar')
</div>
</body>

@include('admin.layout.partials.js')
@yield('js')
</html>