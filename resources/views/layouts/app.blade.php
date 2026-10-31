<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f8f6f1">
    <meta name="description" content="Excellent Barbershop — arsitektur pangkas presisi dan ritual botani tanpa kompromi di Senopati, Jakarta.">
    <title>@yield('title', 'Excellent Barbershop — Your Style. Your Rules.')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/app.css">
    @stack('styles')
    @if (request()->routeIs('booking.*'))
        <link rel="stylesheet" href="/css/booking-theme.css">
    @endif
</head>
<body class="{{ request()->routeIs('booking.*') ? 'booking-theme' : '' }}">
<div class="frame">
    <div class="page">
        <x-navbar />
        <main class="main" id="home">
            @yield('content')
        </main>
        <x-footer />
    </div>
</div>
<script src="/js/app.js" defer></script>
@stack('scripts')
</body>
</html>
