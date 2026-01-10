<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AndRPaid | @yield('title')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('styles/main.css') }}">
    @yield('additionalCSS')
</head>

<body>
    <div class="page-wrapper-foot-nav">

        @unless (View::hasSection('hideNavbar'))
            @include('partials.navbar')
        @endunless

        <main class="flex-grow-1">
            @yield('content')
        </main>

        @unless (View::hasSection('hideFooter'))
            @include('partials.footer')
        @endunless

    </div>

    @stack('scripts')
</body>
</html>
