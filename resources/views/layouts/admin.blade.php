<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Excellent Atelier — Admin Dashboard')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Cinzel:wght@700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy-dark: #0e1726;
            --navy-surface: #132238;
            --navy-card: #15243f;
            --navy-border: #1e3357;
            --gold-primary: #a67c37;
            --gold-light: #fef9ee;
            --gold-border: #fdecd0;
            --gold-badge: #fdeac2;
            --gold-badge-text: #8c5807;
            --gold-btn: #c69244;
            --gold-btn-hover: #b58237;
            --bg-canvas: #f8f9fa;
            --card-white: #ffffff;
            --text-main: #0e1726;
            --text-muted: #8c9aa8;
            --text-sub: #64748b;
            --border-light: #eef0f4;
            --border-subtle: #f1f3f7;
            --green-vol: #10b981;
            --green-badge-bg: #e6f9f0;
            --green-badge-text: #059669;
            --red-cancel-bg: #fef2f2;
            --red-cancel-text: #e53e3e;
            --red-cancel-border: #fed7d7;
            --sidebar-w: 280px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body.admin-body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            background-color: #e5e7eb;
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            justify-content: center;
        }

        /* Screen Wrapper matching Figma Canvas Frame */
        .figma-screen-frame {
            width: 100%;
            max-width: 480px;
            background-color: var(--bg-canvas);
            min-height: 100vh;
            position: relative;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
        }

        /* Top Bar exactly matching Screenshot Header */
        .figma-topbar {
            height: 60px;
            background: var(--card-white);
            border-bottom: 1px solid #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 18px;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .btn-hamburger {
            background: none;
            border: none;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            gap: 4.5px;
            padding: 6px;
        }

        .btn-hamburger span {
            display: block;
            width: 20px;
            height: 2.2px;
            background-color: #0e1726;
            border-radius: 2px;
        }

        .figma-brand-title {
            font-family: 'Cinzel', serif;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.12em;
            color: var(--gold-primary);
            text-transform: uppercase;
            text-decoration: none;
        }

        .figma-topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .bell-icon-btn {
            background: none;
            border: none;
            cursor: pointer;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0e1726;
            text-decoration: none;
            padding: 4px;
        }

        .bell-dot-alert {
            position: absolute;
            top: 3px;
            right: 3px;
            width: 7px;
            height: 7px;
            background-color: #f59e0b;
            border-radius: 50%;
            border: 1.5px solid #ffffff;
        }

        .user-avatar-circle {
            width: 32px;
            height: 32px;
            background-color: var(--navy-dark);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
        }

        /* App Content */
        .figma-app-content {
            padding: 20px 18px 40px;
            flex: 1;
        }

        /* Off-canvas Navigation Drawer */
        .offcanvas-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--sidebar-w);
            background: linear-gradient(180deg, #090e18 0%, #0e1726 100%);
            color: #ffffff;
            z-index: 200;
            transform: translateX(-100%);
            transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            box-shadow: 10px 0 30px rgba(0,0,0,0.3);
        }

        .offcanvas-sidebar.active {
            transform: translateX(0);
        }

        .sidebar-header {
            padding: 22px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sidebar-header h2 {
            font-family: 'Cinzel', serif;
            font-size: 13px;
            letter-spacing: 0.1em;
            color: var(--gold-primary);
        }

        .sidebar-close-btn {
            background: none;
            border: none;
            color: #94a3b8;
            font-size: 22px;
            cursor: pointer;
        }

        .sidebar-menu-list {
            padding: 16px 12px;
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .sidebar-menu-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: 8px;
            color: #cbd5e1;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .sidebar-menu-link:hover,
        .sidebar-menu-link.active {
            background: rgba(198, 146, 68, 0.15);
            color: #f1c479;
        }

        .sidebar-menu-link .badge-num {
            margin-left: auto;
            background: #d97706;
            color: #fff;
            font-size: 10px;
            font-weight: 800;
            padding: 2px 7px;
            border-radius: 9999px;
        }

        .sidebar-drawer-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(2px);
            z-index: 150;
        }

        .sidebar-drawer-backdrop.active {
            display: block;
        }
    </style>
    @stack('styles')
</head>
<body class="admin-body">
    {{-- Drawer Backdrop --}}
    <div id="drawerBackdrop" class="sidebar-drawer-backdrop" onclick="toggleSidebarDrawer()"></div>

    {{-- Offcanvas Drawer Navigation --}}
    <aside id="adminOffcanvas" class="offcanvas-sidebar">
        <div class="sidebar-header">
            <h2>EXCELLENT ATELIER</h2>
            <button type="button" class="sidebar-close-btn" onclick="toggleSidebarDrawer()">&times;</button>
        </div>
        <div style="padding: 12px 18px; background: rgba(255,255,255,0.03); font-size: 11px; display: flex; align-items: center; gap: 8px; color: #34d399; font-weight: 700;">
            <span style="width: 7px; height: 7px; border-radius: 50%; background: #10b981;"></span>
            ADMIN ON-DUTY (SENOPATI)
        </div>
        <nav class="sidebar-menu-list">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-menu-link {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                <span>📊</span> <span>Dashboard Studio</span>
            </a>
            <a href="{{ route('admin.bookings') }}" class="sidebar-menu-link {{ request()->routeIs('admin.bookings*') && !request()->routeIs('admin.bookings.report') ? 'active' : '' }}">
                <span>📋</span> <span>Kelola Booking</span>
                @php
                    $pendingBadge = \App\Models\Booking::query()->where('status', 'pending')->count();
                @endphp
                @if ($pendingBadge > 0)
                    <span class="badge-num">{{ sprintf('%02d', $pendingBadge) }}</span>
                @endif
            </a>
            <a href="{{ route('admin.bookings.report') }}" class="sidebar-menu-link {{ request()->routeIs('admin.bookings.report*') ? 'active' : '' }}">
                <span>📄</span> <span>Laporan & Ekspor PDF</span>
            </a>
            <div style="font-size: 10px; font-weight: 800; color: #64748b; text-transform: uppercase; padding: 14px 14px 4px; letter-spacing: 0.08em;">
                Aksi Cepat
            </div>
            <a href="javascript:void(0);" onclick="toggleSidebarDrawer(); openModal('modalAddBarber')" class="sidebar-menu-link">
                <span>✂️</span> <span>+ Tambah Barber</span>
            </a>
            <a href="javascript:void(0);" onclick="toggleSidebarDrawer(); openModal('modalAddService')" class="sidebar-menu-link">
                <span>💈</span> <span>+ Tambah Layanan</span>
            </a>
            <a href="javascript:void(0);" onclick="toggleSidebarDrawer(); openModal('modalSettings')" class="sidebar-menu-link">
                <span>⚙️</span> <span>Jam Operasional</span>
            </a>
            <div style="font-size: 10px; font-weight: 800; color: #64748b; text-transform: uppercase; padding: 14px 14px 4px; letter-spacing: 0.08em;">
                Portal
            </div>
            <a href="{{ route('home') }}" target="_blank" rel="noopener" class="sidebar-menu-link">
                <span>🌐</span> <span>Lihat Website Publik ↗</span>
            </a>
        </nav>
        <div style="padding: 16px; border-top: 1px solid rgba(255,255,255,0.08); font-size: 11px; color: #94a3b8; display: flex; align-items: center; gap: 10px;">
            <div style="width: 30px; height: 30px; border-radius: 50%; background: var(--gold-btn); color: #000; font-weight: 800; display: flex; align-items: center; justify-content: center;">AD</div>
            <div>
                <div style="color: #fff; font-weight: 700;">Studio Admin</div>
                <div style="color: #c69244;">Admin On-Duty</div>
            </div>
        </div>
    </aside>

    {{-- Figma Centered Screen Frame --}}
    <div class="figma-screen-frame">
        {{-- Exact Figma Topbar --}}
        <header class="figma-topbar">
            <button type="button" class="btn-hamburger" onclick="toggleSidebarDrawer()" aria-label="Menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <a href="{{ route('admin.dashboard') }}" class="figma-brand-title">
                EXCELLENT ATELIER
            </a>
            <div class="figma-topbar-right">
                <a href="{{ route('admin.bookings') }}" class="bell-icon-btn" title="Notifikasi Antrean">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                    @if ($pendingBadge > 0)
                        <span class="bell-dot-alert"></span>
                    @endif
                </a>
                <div class="user-avatar-circle" title="Admin On-Duty">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
            </div>
        </header>

        {{-- Content View --}}
        <main class="figma-app-content">
            @yield('content')
        </main>
    </div>

    <script>
        function toggleSidebarDrawer() {
            const drawer = document.getElementById('adminOffcanvas');
            const backdrop = document.getElementById('drawerBackdrop');
            drawer.classList.toggle('active');
            backdrop.classList.toggle('active');
        }
    </script>
    @stack('scripts')
</body>
</html>
