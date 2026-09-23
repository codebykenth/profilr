<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.head')
    @yield('head-extras')
</head>
<body>
    <a href="#main" class="skip-to-content">Skip to content</a>
    @section('nav')
        @include('layouts.nav')
    @show
    <main id="main">
        @yield('content')
    </main>
    @section('footer')
        @include('layouts.footer')
    @show
    @yield('scripts')
</body>
</html>
