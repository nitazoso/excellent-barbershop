@extends('layouts.app')

@section('title', 'Cek Booking — Excellent Barbershop')

@section('content')
    <section class="booking-page">
        <span class="section-kicker">CEK BOOKING</span>
        <h1>Temukan reservasi Anda.</h1>
        <p>Masukkan nomor HP yang digunakan saat melakukan booking.</p>

        <form method="POST" action="{{ route('booking.check.submit') }}">
            @csrf
            <label for="phone">Nomor HP</label>
            <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required maxlength="20">
            @error('phone')
                <p role="alert">{{ $message }}</p>
            @enderror
            <button class="primary" type="submit">CARI BOOKING</button>
        </form>

        @if ($bookings->isNotEmpty())
            <div class="booking-results">
                @foreach ($bookings as $booking)
                    <article>
                        <h2>{{ $booking->booking_code }}</h2>
                        <p>{{ $booking->booking_date->format('d M Y') }} · {{ substr($booking->start_time, 0, 5) }}</p>
                        <p>{{ $booking->service->name }} dengan {{ $booking->barber->name }}</p>
                        <p>Status: {{ ucfirst($booking->status) }}</p>
                    </article>
                @endforeach
            </div>
        @endif
    </section>
@endsection
