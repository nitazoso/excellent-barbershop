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
        @auth
            <aside class="admin-sidebar" id="admin-sidebar" aria-label="Navigasi admin">
                <a class="admin-brand" href="{{ route('admin.dashboard') }}" aria-label="Excellent Atelier — Dashboard">
                    <span class="admin-brand-mark" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><circle cx="6" cy="6" r="2.5"/><circle cx="6" cy="18" r="2.5"/><path d="m8 7 12 12M8 17 19 6m-5 5 5 5"/></svg>
                    </span>
                    <span class="admin-brand-copy">
                        <span class="admin-brand-name">EXCELLENT <small>BARBERSHOP</small></span>
                        <span class="admin-brand-subtitle">Owner Portal · Senopati</span>
                    </span>
                </a>

                <button class="admin-menu-close" type="button" aria-label="Tutup menu navigasi">×</button>

                <nav class="admin-navigation" aria-label="Navigasi utama">
                    <p class="admin-nav-label">Navigasi utama</p>
                    <a class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <span class="admin-nav-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 3h8v8H3zm10 0h8v5h-8zm0 7h8v11h-8zM3 13h8v8H3z"/></svg></span>
                        <span>Dashboard</span>
                    </a>
                    <a class="admin-nav-link {{ request()->routeIs('admin.bookings') ? 'is-active' : '' }}" href="{{ route('admin.bookings') }}">
                        <span class="admin-nav-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2Zm0 16H5V9h14v11ZM7 11h4v4H7z"/></svg></span>
                        <span>Kelola Booking</span>
                        @if (isset($pendingBookingsCount) && $pendingBookingsCount > 0)
                            <span class="admin-nav-badge">{{ $pendingBookingsCount }} Baru</span>
                        @endif
                    </a>
                    <span class="admin-nav-link is-disabled" aria-disabled="true" title="Fitur belum tersedia">
                        <span class="admin-nav-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="m19.7 4.3-5.6 5.6 5.6 5.6 1.4-1.4a4 4 0 0 0 0-5.6l-1.4-1.4ZM3 21l7.5-2L21 8.5 15.5 3 5 13.5 3 21Z"/></svg></span>
                        <span>Kelola Layanan</span>
                    </span>
                    <span class="admin-nav-link is-disabled" aria-disabled="true" title="Fitur belum tersedia">
                        <span class="admin-nav-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M9 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm0 2c-3.3 0-6 1.7-6 3.8V21h12v-3.2C15 15.7 12.3 14 9 14Zm7-2c1.7 0 3-1.3 3-3s-1.3-3-3-3c-.4 0-.8.1-1.1.2a6 6 0 0 1 0 5.6c.3.1.7.2 1.1.2Zm0 2h-.7c1.1.8 1.7 1.9 1.7 3.2V21h4v-3.2c0-2.1-2.2-3.8-5-3.8Z"/></svg></span>
                        <span>Kelola Barber</span>
                    </span>
                    <span class="admin-nav-link is-disabled" aria-disabled="true" title="Fitur belum tersedia">
                        <span class="admin-nav-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Zm1 11h5v-2h-4V5h-2v7a1 1 0 0 0 1 1Z"/></svg></span>
                        <span>Jadwal Operasional</span>
                    </span>

                    <p class="admin-nav-label">Konten &amp; lokasi</p>
                    <span class="admin-nav-link is-disabled" aria-disabled="true" title="Fitur belum tersedia">
                        <span class="admin-nav-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M21 3H3a2 2 0 0 0-2 2v13h22V5a2 2 0 0 0-2-2ZM3 16V5h18v11H3Zm5 6h8v-2H8z"/></svg></span>
                        <span>Kelola Landing Page</span>
                    </span>
                    <span class="admin-nav-link is-disabled" aria-disabled="true" title="Fitur belum tersedia">
                        <span class="admin-nav-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 2a8 8 0 0 0-8 8c0 5.2 8 12 8 12s8-6.8 8-12a8 8 0 0 0-8-8Zm0 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z"/></svg></span>
                        <span>Kelola Lokasi</span>
                    </span>
                    <a class="admin-nav-link {{ request()->routeIs('admin.bookings.report') ? 'is-active' : '' }}" href="{{ route('admin.bookings.report') }}">
                        <span class="admin-nav-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M4 19h3V9H4zm6 0h3V4h-3zm6 0h3v-7h-3zm6 2H2V3h2v16h18z"/></svg></span>
                        <span>Laporan</span>
                    </a>

                    <p class="admin-nav-label">Hak akses &amp; akun</p>
                    <a class="admin-nav-link {{ request()->routeIs('admin.admins.*') ? 'is-active' : '' }}" href="{{ route('admin.admins.index') }}">
                        <span class="admin-nav-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm0 2c-4 0-7 2-7 4v2h14v-2c0-2-3-4-7-4Zm7-13v4m-2-2h4"/></svg></span>
                        <span>Kelola Admin</span>
                    </a>
                    <span class="admin-nav-link is-disabled" aria-disabled="true" title="Fitur belum tersedia">
                        <span class="admin-nav-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M19.4 13a7.7 7.7 0 0 0 0-2l2-1.5-2-3.5L17 7a8 8 0 0 0-1.7-1L15 3h-4l-.4 3a8 8 0 0 0-1.7 1L6.5 6l-2 3.5 2 1.5a7.7 7.7 0 0 0 0 2l-2 1.5 2 3.5 2.4-1a8 8 0 0 0 1.7 1l.4 3h4l.4-3a8 8 0 0 0 1.7-1l2.4 1 2-3.5ZM13 15a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z"/></svg></span>
                        <span>Pengaturan Akun</span>
                    </span>
                </nav>

                <div class="admin-sidebar-footer">
                    <div class="admin-user-card">
                        @if (auth()->user()->avatar)
                            <img class="admin-user-avatar" src="{{ auth()->user()->avatar }}" alt="">
                        @else
                            <span class="admin-user-avatar admin-user-initials" aria-hidden="true">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                        @endif
                        <span class="admin-user-details">
                            <strong>{{ auth()->user()->name }}</strong>
                            <small><span class="admin-online-dot"></span> Online · Master</small>
                        </span>
                    </div>
                    <form class="admin-logout-form" action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button class="admin-logout-button" type="submit">
                            <span class="admin-logout-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M10 17l5-5-5-5m5 5H3m9-9h7a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-7"/></svg></span>
                            <span>Keluar</span>
                            <small>LOGOUT</small>
                        </button>
                    </form>
                </div>
            </aside>

            <button class="admin-sidebar-backdrop" type="button" aria-label="Tutup menu navigasi" tabindex="-1"></button>

            <header class="admin-topbar">
                <button class="admin-menu-toggle" type="button" aria-label="Buka atau tutup menu" aria-expanded="false" aria-controls="admin-sidebar">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <a class="admin-topbar-brand" href="{{ route('admin.dashboard') }}">EXCELLENT BARBERSHOP</a>
                <div class="admin-topbar-summary" aria-label="Informasi operasional hari ini">
                    <span class="admin-topbar-calendar" aria-hidden="true">
                        <svg viewBox="0 0 24 24"><path d="M7 2v4m10-4v4M4 9h16M5 4h14a1 1 0 0 1 1 1v15H4V5a1 1 0 0 1 1-1Z"/></svg>
                    </span>
                    <strong>{{ $todayDateLabel ?? now()->locale('id')->translatedFormat('l, d F Y') }}</strong>
                    <span class="admin-operational-status"><i aria-hidden="true"></i>Operasional Buka</span>
                </div>
                <div class="admin-topbar-actions">
                    <button class="admin-topbar-icon admin-notification-button" type="button" aria-label="Notifikasi">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></svg>
                        <span class="admin-notification-dot"></span>
                    </button>
                    <button class="admin-topbar-icon admin-profile-button" type="button" aria-label="Profil {{ auth()->user()->name }}">
                        @if (auth()->user()->avatar)
                            <img src="{{ auth()->user()->avatar }}" alt="">
                        @else
                            <span aria-hidden="true">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        @endif
                    </button>
                </div>
            </header>
        @endauth

        <main class="admin-main {{ request()->routeIs('admin.dashboard') ? 'admin-main-dashboard' : '' }} {{ request()->routeIs('admin.login') ? 'admin-main-login' : '' }}">
            @yield('content')
        </main>
    </div>

    <script src="/js/admin.js" defer></script>
    @stack('scripts')
</body>
</html>
