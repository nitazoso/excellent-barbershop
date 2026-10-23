<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin — Excellent Barbershop')</title>
    <link rel="stylesheet" href="/css/admin.css">
    @stack('styles')
</head>
<body class="admin-body">
    <div class="admin-shell">
        <header class="admin-header">
            <a href="{{ route('home') }}">EXCELLENT <span>BARBERSHOP</span></a>
            <nav aria-label="Navigasi admin">
                <a href="{{ route('admin.dashboard') }}">DASHBOARD</a>
                <a href="{{ route('admin.bookings') }}">BOOKING</a>
            </nav>
            <span>ADMIN STUDIO</span>
        </header>
        <main class="admin-main">@yield('content')</main>
    </div>
    <script src="/js/admin.js" defer></script>
    @stack('scripts')
</body>
</html>
