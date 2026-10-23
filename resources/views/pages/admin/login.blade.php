@extends('layouts.admin')

@section('title', 'Login Admin — Excellent Barbershop')

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
