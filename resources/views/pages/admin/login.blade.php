@extends('layouts.admin')

@section('title', 'Login Admin — Excellent Barbershop')

@section('content')
    <section class="admin-login-card" aria-labelledby="admin-login-title">
        <div class="admin-login-logo" aria-hidden="true">
            <svg viewBox="0 0 24 24"><circle cx="6" cy="6" r="2.5"/><circle cx="6" cy="18" r="2.5"/><path d="m8 7 12 12M8 17 19 6m-5 5 5 5"/></svg>
            <span></span>
        </div>

        <div class="admin-login-brand"><strong>EXCELLENT BARBERSHOP</strong><span></span><small>ATELIER</small></div>
        <div class="admin-login-access"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2 4 5v6c0 5.2 3.4 9.1 8 11 4.6-1.9 8-5.8 8-11V5l-8-3Zm-1 14-4-4 1.4-1.4L11 13.2l5.6-5.6L18 9l-7 7Z"/></svg> AKSES KHUSUS PEMILIK &amp; PENGELOLA</div>

        <h1 id="admin-login-title">Kelola barbershop dengan mudah</h1>

        @if (session('error'))
            <div class="admin-login-error" role="alert">{{ session('error') }}</div>
        @endif

        <a href="{{ route('admin.google.redirect') }}" class="admin-google-btn">
            <svg class="admin-google-logo" viewBox="0 0 24 24" aria-hidden="true">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
            </svg>
            <span>Lanjutkan dengan Google</span>
        </a>

        <div class="admin-login-security" aria-label="Keamanan login">
            <div><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="10" width="14" height="11" rx="2"/><path d="M8 10V7a4 4 0 1 1 8 0v3m-4 4v3"/></svg><span>256-bit<br>SSL</span></div>
            <i></i>
            <div><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2 5 5v6c0 4.7 2.8 8.5 7 11 4.2-2.5 7-6.3 7-11V5l-7-3Z"/></svg><span>Google<br>Identity</span></div>
            <i></i>
            <div><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m12 2 9 5v10l-9 5-9-5V7l9-5Zm0 0v10m9-5-9 5-9-5m9 5v10"/></svg><span>Enkripsi OAuth<br>2.0</span></div>
        </div>

        <p class="admin-login-note">Portal aman hanya diperuntukkan bagi pemilik resmi. Masuk untuk mengelola staf, pendapatan, dan operasional salon.</p>
    </section>

    <footer class="admin-login-footer">
        <span>© EXCELLENT BARBERSHOP STUDIO</span>
        <span><i></i> Sistem Operasional Aktif</span>
    </footer>
@endsection
