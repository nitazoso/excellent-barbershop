@extends('layouts.app')

@section('title', 'Booking diterima — Excellent Barbershop')

@section('content')
    <section class="booking-page booking-result">
        <span class="section-kicker">BOOKING</span>
        <h1>Terima kasih atas kunjungan Anda.</h1>
        <p>Kode booking: <strong>{{ $booking->booking_code }}</strong></p>
        <p>{{ $booking->booking_date->format('d M Y') }} · {{ substr($booking->start_time, 0, 5) }}–{{ substr($booking->end_time, 0, 5) }}</p>
        <p>{{ $booking->service->name }} dengan {{ $booking->barber->name }}</p>
        <p>Status: {{ ucfirst($booking->status) }}</p>
        <a class="primary" href="{{ url('/') }}">KEMBALI KE BERANDA <span aria-hidden="true">→</span></a>
    </section>
@endsection
