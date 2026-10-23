@extends('layouts.admin')

@section('title', 'Dashboard — Admin Excellent Barbershop')

@section('content')
    <div class="admin-page-heading">
        <div>
            <span>OVERVIEW STUDIO</span>
            <h1>Dashboard</h1>
            <p>Ringkasan aktivitas dan operasional Excellent Barbershop.</p>
        </div>
        <a href="{{ route('admin.bookings') }}">LIHAT BOOKING →</a>
    </div>
    <section class="admin-panel">
        <h2>Selamat datang di studio.</h2>
        <p>Statistik kunjungan, layanan populer, dan jadwal hari ini akan tampil di bagian ini.</p>
    </section>
@endsection
