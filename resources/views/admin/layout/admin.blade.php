<!doctype html>
<html lang="en">
<head>
    @include('admin.layout.partials.head')
</head>
<body class="bg-light">

@include('admin.layout.partials.navigation')

<main role="main" class="py-4">
    <div class="container-fluid px-lg-4">
        @include('includes.messages')
        @yield('content')
    </div>
</main>

@yield('sidebar')

@include('admin.layout.partials.js')
@yield('js')
</body>
</html>