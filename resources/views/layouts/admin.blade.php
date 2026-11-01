<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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

        /* Off-canvas Navigation Drawer matching Figma Aside - Sidebar Container */
        .offcanvas-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--sidebar-w);
            background: #0d1726;
            color: #ffffff;
            z-index: 200;
            transform: translateX(-100%);
            transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            box-shadow: 10px 0 35px rgba(0, 0, 0, 0.45);
        }

        .offcanvas-sidebar.active {
            transform: translateX(0);
        }

        /* Sidebar Header */
        .sidebar-brand-header {
            padding: 20px 18px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
        }

        .brand-logo-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #cca04b;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(204, 160, 75, 0.25);
        }

        .brand-text-row {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
        }

        .brand-name {
            font-size: 15px;
            font-weight: 800;
            letter-spacing: 0.05em;
            color: #ffffff;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .brand-badge-atelier {
            background: #0d1726;
            border: 1px solid rgba(204, 160, 75, 0.6);
            color: #cca04b;
            font-size: 8.5px;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            padding: 2.5px 7px;
            border-radius: 4px;
        }

        .sidebar-close-btn {
            background: none;
            border: none;
            color: #64748b;
            font-size: 22px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4px;
            border-radius: 6px;
            transition: color 0.2s;
        }

        .sidebar-close-btn:hover {
            color: #ffffff;
        }

        /* Sidebar Scrollable Body */
        .sidebar-scroll-content {
            padding: 8px 14px;
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .sidebar-scroll-content::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-scroll-content::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.1);
            border-radius: 4px;
        }

        .sidebar-group-title {
            font-size: 9px;
            font-weight: 800;
            color: #64748b;
            letter-spacing: 0.09em;
            text-transform: uppercase;
            padding: 16px 12px 6px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 9.5px 12px;
            border-radius: 9px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.2s;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.04);
            color: #e2e8f0;
        }

        .nav-item.active {
            background: #cca04b;
            color: #0d1726;
            font-weight: 800;
        }

        .nav-item.active .nav-icon {
            color: #0d1726;
        }

        .nav-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            color: #64748b;
            flex-shrink: 0;
            transition: color 0.2s;
        }

        .nav-item:hover .nav-icon {
            color: #cbd5e1;
        }

        .nav-label {
            flex: 1;
            white-space: nowrap;
        }

        .nav-active-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #0d1726;
            margin-left: auto;
            flex-shrink: 0;
        }

        .nav-badge-pill {
            margin-left: auto;
            background: rgba(204, 160, 75, 0.15);
            border: 1px solid rgba(204, 160, 75, 0.4);
            color: #cca04b;
            font-size: 9.5px;
            font-weight: 800;
            padding: 2px 8px;
            border-radius: 12px;
            white-space: nowrap;
        }

        .nav-item.active .nav-badge-pill {
            background: #0d1726;
            color: #cca04b;
            border-color: #0d1726;
        }

        /* Sidebar Footer */
        .sidebar-footer-wrap {
            padding: 14px;
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            display: flex;
            flex-direction: column;
            gap: 8px;
            background: #0a111c;
        }

        .user-profile-card {
            background: #111a28;
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 10px;
            padding: 9px 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar-bo {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #162234;
            border: 1.5px solid #cca04b;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #cca04b;
            font-weight: 800;
            font-size: 11.5px;
            flex-shrink: 0;
        }

        .user-profile-meta {
            display: flex;
            flex-direction: column;
            gap: 1px;
            min-width: 0;
        }

        .user-name {
            font-size: 11.5px;
            font-weight: 700;
            color: #ffffff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-status-row {
            font-size: 9.5px;
            display: flex;
            align-items: center;
            gap: 4px;
            color: #64748b;
        }

        .green-live-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #10b981;
            display: inline-block;
        }

        .status-online {
            color: #10b981;
            font-weight: 700;
        }

        .status-role {
            color: #64748b;
            font-weight: 500;
        }

        .btn-logout-sidebar {
            background: #111a28;
            border: 1px solid rgba(239, 68, 68, 0.25);
            border-radius: 9px;
            padding: 8.5px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
        }

        .btn-logout-sidebar:hover {
            background: rgba(239, 68, 68, 0.12);
            border-color: rgba(239, 68, 68, 0.5);
        }

        .logout-left {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logout-text {
            font-size: 11.5px;
            font-weight: 700;
            color: #f87171;
        }

        .logout-tag {
            font-size: 9px;
            font-weight: 800;
            color: #ef4444;
            letter-spacing: 0.08em;
        }

        .sidebar-drawer-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.55);
            backdrop-filter: blur(2px);
            z-index: 150;
        }

        .sidebar-drawer-backdrop.active {
            display: block;
        }

        html, body { max-width: 100%; overflow-x: hidden; }
        .figma-screen-frame, .figma-app-content { min-width: 0; }
        .offcanvas-sidebar { width: min(var(--sidebar-w), calc(100vw - 40px)); }

        @media (min-width: 768px) {
            .figma-screen-frame { max-width: none; width: 100%; }
            .figma-topbar { height: 68px; padding: 0 32px; }
            .figma-app-content { padding: 30px 32px 48px; }
        }

        @media (min-width: 1200px) {
            .figma-app-content { padding-right: 48px; padding-left: 48px; }
        }

        @media (max-width: 380px) {
            .figma-topbar { padding: 0 12px; }
            .figma-app-content { padding: 16px 12px 30px; }
            .figma-brand-title { font-size: 11px; letter-spacing: .08em; }
            .figma-topbar-right { gap: 6px; }
        }

        .booking-screen-frame { width:100%; max-width:480px; }
        .booking-screen-frame .figma-topbar { height:60px; padding:0 16px; }
        .booking-screen-frame .figma-app-content { padding:20px 16px 32px; }

        @media (min-width: 540px) {
            .booking-screen-frame { max-width:none; }
        }

        @media (min-width: 768px) {
            .booking-screen-frame .figma-topbar { height:68px; padding:0 32px; }
            .booking-screen-frame .figma-app-content { padding:30px 32px 48px; }
        }

        @media (min-width: 1200px) {
            .booking-screen-frame .figma-app-content { padding-right:48px; padding-left:48px; }
        }
    </style>
    @stack('styles')
</head>
<body class="admin-body">
    {{-- Drawer Backdrop --}}
    <div id="drawerBackdrop" class="sidebar-drawer-backdrop" onclick="toggleSidebarDrawer()"></div>

    {{-- Offcanvas Drawer Navigation - Aside Container Exactly Matching Figma --}}
    <aside id="adminOffcanvas" class="offcanvas-sidebar">
        {{-- Brand Header with Scissors Logo & Badge --}}
        <div class="sidebar-brand-header">
            <div class="brand-logo-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="6" cy="6" r="3"></circle>
                    <circle cx="6" cy="18" r="3"></circle>
                    <line x1="20" y1="4" x2="8.12" y2="15.88"></line>
                    <line x1="14.47" y1="14.48" x2="20" y2="20"></line>
                    <line x1="8.12" y1="8.12" x2="12" y2="12"></line>
                </svg>
            </div>
            <div class="brand-text-row">
                <span class="brand-name">EXCELLENT</span>
                <span class="brand-badge-atelier">ATELIER</span>
            </div>
            <button type="button" class="sidebar-close-btn" onclick="toggleSidebarDrawer()" aria-label="Tutup Menu">&times;</button>
        </div>

        {{-- Scrollable Navigation Links --}}
        <div class="sidebar-scroll-content">
            {{-- GROUP 1: NAVIGASI UTAMA --}}
            <div class="sidebar-group-title">NAVIGASI UTAMA</div>

            {{-- 1. Dashboard --}}
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor">
                        <rect x="3" y="3" width="8" height="8" rx="1.5"></rect>
                        <rect x="13" y="3" width="8" height="8" rx="1.5"></rect>
                        <rect x="3" y="13" width="8" height="8" rx="1.5"></rect>
                        <rect x="13" y="13" width="8" height="8" rx="1.5"></rect>
                    </svg>
                </span>
                <span class="nav-label">Dashboard</span>
                @if(request()->routeIs('admin.dashboard*'))
                    <span class="nav-active-dot"></span>
                @endif
            </a>

            {{-- 2. Kelola Booking --}}
            @php
                $pendingBadge = \App\Models\Booking::query()->where('status', 'pending')->count();
            @endphp
            <a href="{{ route('admin.bookings') }}" class="nav-item {{ request()->routeIs('admin.bookings*') && !request()->routeIs('admin.bookings.report') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                        <polyline points="9 16 11 18 15 14"></polyline>
                    </svg>
                </span>
                <span class="nav-label">Kelola Booking</span>
                <span class="nav-badge-pill">{{ $pendingBadge > 0 ? $pendingBadge . ' Baru' : '3 Baru' }}</span>
                @if(request()->routeIs('admin.bookings*') && !request()->routeIs('admin.bookings.report'))
                    <span class="nav-active-dot"></span>
                @endif
            </a>

            {{-- 3. Kelola Layanan --}}
            <a href="{{ route('admin.services.index') }}" class="nav-item {{ request()->routeIs('admin.services*') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                    </svg>
                </span>
                <span class="nav-label">Kelola Layanan</span>
                @if(request()->routeIs('admin.services*'))
                    <span class="nav-active-dot"></span>
                @endif
            </a>

            {{-- 4. Kelola Barber --}}
            <a href="{{ route('admin.barbers.index') }}" class="nav-item {{ request()->routeIs('admin.barbers*') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </span>
                <span class="nav-label">Kelola Barber</span>
                @if(request()->routeIs('admin.barbers*'))
                    <span class="nav-active-dot"></span>
                @endif
            </a>

            {{-- 5. Jadwal Operasional --}}
            <a href="javascript:void(0);" onclick="toggleSidebarDrawer(); openModal('modalSettings')" class="nav-item">
                <span class="nav-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                </span>
                <span class="nav-label">Jadwal Operasional</span>
            </a>

            {{-- GROUP 2: KONTEN & LOKASI --}}
            <div class="sidebar-group-title">KONTEN &amp; LOKASI</div>

            {{-- 6. Kelola Landing Page --}}
            <a href="{{ route('home') }}" target="_blank" rel="noopener" class="nav-item">
                <span class="nav-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                        <line x1="8" y1="21" x2="16" y2="21"></line>
                        <line x1="12" y1="17" x2="12" y2="21"></line>
                    </svg>
                </span>
                <span class="nav-label">Kelola Landing Page</span>
            </a>

            {{-- 7. Kelola Lokasi --}}
            <a href="javascript:void(0);" onclick="toggleSidebarDrawer(); alert('Lokasi Studio: Jl. Senopati No. 42B, Kebayoran Baru, Jakarta Selatan\nTelepon: +62 21 5550 1928');" class="nav-item">
                <span class="nav-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                </span>
                <span class="nav-label">Kelola Lokasi</span>
            </a>

            {{-- 8. Laporan --}}
            <a href="{{ route('admin.bookings.report') }}" class="nav-item {{ request()->routeIs('admin.bookings.report*') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                        <polyline points="17 6 23 6 23 12"></polyline>
                    </svg>
                </span>
                <span class="nav-label">Laporan</span>
                @if(request()->routeIs('admin.bookings.report*'))
                    <span class="nav-active-dot"></span>
                @endif
            </a>

            {{-- GROUP 3: HAK AKSES & AKUN --}}
            <div class="sidebar-group-title">HAK AKSES &amp; AKUN</div>

            {{-- 9. Kelola Admin --}}
            <a href="javascript:void(0);" onclick="toggleSidebarDrawer(); alert('Role Aktif: Master Owner (Bramantyo Owner)\nAkses: Full Access Control');" class="nav-item">
                <span class="nav-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="8.5" cy="7" r="4"></circle>
                        <polyline points="17 11 19 13 23 9"></polyline>
                    </svg>
                </span>
                <span class="nav-label">Kelola Admin</span>
            </a>

            {{-- 10. Pengaturan Akun --}}
            <a href="javascript:void(0);" onclick="toggleSidebarDrawer(); openModal('modalSettings')" class="nav-item">
                <span class="nav-icon">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="3"></circle>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                    </svg>
                </span>
                <span class="nav-label">Pengaturan Akun</span>
            </a>
        </div>

        {{-- Sidebar Footer: User Profile & Logout --}}
        <div class="sidebar-footer-wrap">
            {{-- User Profile Card --}}
            <div class="user-profile-card">
                <div class="user-avatar-bo">BO</div>
                <div class="user-profile-meta">
                    <div class="user-name">Bramantyo Owner</div>
                    <div class="user-status-row">
                        <span class="green-live-dot"></span>
                        <span class="status-online">Online</span>
                        <span class="status-role">• Master</span>
                    </div>
                </div>
            </div>

            {{-- Logout Button --}}
            <a href="{{ route('admin.login') }}" class="btn-logout-sidebar" onclick="return confirm('Apakah Anda yakin ingin keluar dari sesi admin?');">
                <div class="logout-left">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span class="logout-text">Keluar</span>
                </div>
                <span class="logout-tag">LOGOUT</span>
            </a>
        </div>
    </aside>

    {{-- Figma Centered Screen Frame --}}
    <div class="figma-screen-frame {{ request()->routeIs('admin.bookings*') ? 'booking-screen-frame' : '' }}">
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
