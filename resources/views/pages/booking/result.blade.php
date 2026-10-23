@extends('layouts.app')

@section('title', 'Booking diterima — Excellent Barbershop')

@section('content')
    <section class="booking-page booking-result">
        <span class="section-kicker">BOOKING</span>
        <h1>Terima kasih atas kunjungan Anda.</h1>
        <p>Detail reservasi akan ditampilkan di sini setelah booking terhubung ke sistem.</p>
        <a class="primary" href="{{ url('/') }}">KEMBALI KE BERANDA <span aria-hidden="true">→</span></a>
    </section>
@endsection
