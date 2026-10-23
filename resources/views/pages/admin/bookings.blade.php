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
        @forelse ($bookings as $booking)
            <article class="admin-booking">
                <h2>{{ $booking->booking_code }} · {{ $booking->customer->name }}</h2>
                <p>{{ $booking->booking_date->format('d M Y') }} · {{ substr($booking->start_time, 0, 5) }}</p>
                <p>{{ $booking->service->name }} dengan {{ $booking->barber->name }}</p>
                <p>Status: {{ ucfirst($booking->status) }}</p>
            </article>
        @empty
            <h2>Belum ada data booking</h2>
            <p>Reservasi pelanggan akan ditampilkan di sini setelah pelanggan membuat booking.</p>
        @endforelse

        {{ $bookings->links() }}
    </section>
@endsection
