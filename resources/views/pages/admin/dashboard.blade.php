@extends('layouts.admin')

@section('title', 'Dashboard Admin — Excellent Barbershop')

@section('content')
    <div class="admin-page-heading">
        <div>
            <span>PANEL PENGELOLA</span>
            <h1>Dashboard Admin</h1>
            <p>Selamat datang, <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->email }}).</p>
        </div>
        <div style="display: flex; gap: 12px; align-items: center;">
            <a href="{{ route('admin.bookings') }}">LIHAT BOOKING →</a>
            <form action="{{ route('admin.logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" style="background: #ffffff; border: 1px solid #ded8cf; border-radius: 4px; padding: 6px 14px; cursor: pointer; font-size: 11px; font-weight: 700; color: #a93822; letter-spacing: 0.05em;">LOGOUT</button>
            </form>
        </div>
    </div>

    <section class="admin-panel">
        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px;">
            @if (auth()->user()->avatar)
                <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}" style="width: 56px; height: 56px; border-radius: 50%; object-fit: cover; border: 2px solid #e7e2da;">
            @endif
            <div>
                <h2 style="margin: 0; font-size: 20px;">Selamat datang di Dashboard Admin.</h2>
                <p style="margin: 4px 0 0; color: #6d655d;">Anda berhasil terautentikasi melalui Google OAuth sebagai Administrator Excellent Barbershop.</p>
            </div>
        </div>

        <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid #f0eae1; color: #8d8379; font-size: 13px;">
            <p>Halaman ini merupakan placeholder dashboard admin. Modul operasional dan manajemen booking dapat diakses melalui menu navigasi.</p>
        </div>
    </section>
@endsection
