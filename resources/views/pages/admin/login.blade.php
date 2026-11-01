@extends('layouts.admin')

@section('title', 'Login Admin — Excellent Barbershop')

@push('styles')
    <style>
        .admin-login-card { width:100%; max-width:460px; margin:24px auto; padding:clamp(18px, 6vw, 38px); background:#fff; border:1px solid #e7e2da; border-radius:10px; box-shadow:0 8px 30px #211f1c0a; }
        .admin-login-card h1 { font-size:clamp(26px, 8vw, 32px); letter-spacing:-.05em; margin:9px 0; overflow-wrap:anywhere; }
        .admin-eyebrow { color:#a93822; font-size:10px; font-weight:700; letter-spacing:.1em; }
        .admin-login-card > p { color:#6d655d; font-size:14px; line-height:1.6; margin:0 0 24px; }
        .admin-login-form { display:grid; gap:9px; }
        .admin-login-form label { font-size:12px; font-weight:700; margin-top:6px; }
        .admin-login-form input { width:100%; min-width:0; height:44px; padding:0 12px; border:1px solid #ded8cf; border-radius:5px; font:inherit; }
        .admin-login-form button { min-height:44px; margin-top:12px; border:0; border-radius:5px; background:#a93822; color:white; font-weight:700; letter-spacing:.08em; }
        .admin-login-form button:disabled { opacity:.55; cursor:not-allowed; }
        .admin-login-form small { color:#8d8379; font-size:11px; line-height:1.5; }
        .admin-back-link { display:inline-block; margin-top:25px; color:#6d655d; font-size:10px; font-weight:700; text-decoration:none; letter-spacing:.06em; }
    </style>
@endpush

@section('content')
    <section class="admin-login-card">
        <span class="admin-eyebrow">PENGELOLA STUDIO</span>
        <h1>Masuk ke dashboard.</h1>
        <p>Gunakan akun admin untuk mengelola reservasi dan aktivitas studio.</p>

        <form class="admin-login-form">
            <label for="email">Alamat email</label>
            <input id="email" name="email" type="email" placeholder="nama@studio.com" autocomplete="username" required>

            <label for="password">Kata sandi</label>
            <input id="password" name="password" type="password" placeholder="Masukkan kata sandi" autocomplete="current-password" required>

            <button type="button" disabled>MASUK</button>
            <small>Form login akan aktif setelah autentikasi admin dikonfigurasi.</small>
        </form>
        <a class="admin-back-link" href="{{ route('home') }}">← KEMBALI KE WEBSITE</a>
    </section>
@endsection
