@extends('layouts.admin')

@section('title', 'Booking — Admin Excellent Barbershop')

@section('content')
    <div class="admin-page-heading">
        <div>
            <span>RESERVASI</span>
            <h1>Daftar Booking</h1>
            <p>Pantau jadwal kunjungan dan informasi reservasi pelanggan.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}">← DASHBOARD</a>
    </div>
    <section class="admin-panel">
        <h2>Belum ada data booking</h2>
        <p>Reservasi pelanggan akan ditampilkan di sini setelah sistem booking dihubungkan.</p>
    </section>
@endsection
