<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Admin — Excellent Barbershop')</title>
    <link rel="stylesheet" href="/css/admin.css">
    @stack('styles')
</head>
<body class="admin-body">
    <div class="admin-shell">
        <header class="admin-header">
            <a href="{{ route('home') }}">EXCELLENT <span>BARBERSHOP</span></a>
            @auth
                <nav aria-label="Navigasi admin">
                    <a href="{{ route('admin.dashboard') }}">DASHBOARD</a>
                    <a href="{{ route('admin.bookings') }}">BOOKING</a>
                    <a href="{{ route('admin.admins.index') }}">KELOLA ADMIN</a>
                </nav>
                <div style="display: flex; align-items: center; gap: 14px;">
                    <span>{{ auth()->user()->name }}</span>
                    <form action="{{ route('admin.logout') }}" method="POST" style="display: inline; margin: 0;">
                        @csrf
                        <button type="submit" style="background: none; border: 1px solid #ded8cf; border-radius: 4px; padding: 6px 12px; cursor: pointer; font-size: 11px; font-weight: 700; color: #6d655d; letter-spacing: 0.05em;">KELUAR</button>
                    </form>
                </div>
            @else
                <span>ADMIN STUDIO</span>
            @endauth
        </header>
        <main class="admin-main">@yield('content')</main>
    </div>
    <script src="/js/admin.js" defer></script>
    @stack('scripts')
</body>
</html>
