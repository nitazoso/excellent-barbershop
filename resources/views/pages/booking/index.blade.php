@extends('layouts.app')

@section('title', 'Booking — Excellent Barbershop')

@section('content')
    <section class="booking-page">
        <span class="section-kicker">BOOKING ONLINE</span>
        <h1>Atur waktu untuk diri sendiri.</h1>
        <p>Pilih layanan, barber, dan jadwal kunjungan Anda.</p>
        <x-booking-stepper :current="1" />
        <a class="primary" href="{{ url('/') }}#layanan">LIHAT LAYANAN <span aria-hidden="true">→</span></a>
    </section>
@endsection

@push('scripts')
    <script src="/js/booking.js" defer></script>
@endpush
