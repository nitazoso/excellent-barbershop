@extends('layouts.app')

@section('title', 'Booking — Excellent Barbershop')

@section('content')
<section class="booking-page">
    <span class="section-kicker">BOOKING ONLINE</span>
    <h1>Pilih Kapster Favorit</h1>

    <div class="barber-selection">
        @forelse ($barbers as $barber)
        <a href="{{ route('booking.schedule', ['barber_id' => $barber->id]) }}" class="barber-card selectable">
            <img src="{{ $barber->photo }}" alt="{{ $barber->name }}" loading="lazy">
            <div>
                <h3>{{ $barber->name }}</h3>
                <p class="rating">★ 4.9 <small>(850+ ulasan)</small></p>
                <p>{{ $barber->description ?? 'Spesialisasi: Skin Fade, Classic Scissor & Beard' }} · 10+ thn exp</p>
            </div>
            <div class="status-badge">Tersedia Hari Ini</div>
        </a>
        @empty
        <p>Belum ada barber yang tersedia.</p>
        @endforelse
    </div>

    <div style="margin-top: 24px; text-align: center;">
        <a href="{{ url('/') }}" class="btn-ghost">← Kembali ke Beranda</a>
    </div>
</section>
@endsection

@push('styles')
<style>
    .barber-selection { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 14px; margin: 24px 0; }
    .barber-card.selectable { display: block; cursor: pointer; border: 2px solid var(--line); border-radius: 10px; overflow: hidden; text-align: left; background: #fff; transition: all .15s; text-decoration: none; color: inherit; }
    .barber-card.selectable:hover { border-color: var(--gold); box-shadow: 0 6px 18px rgba(0,0,0,.06); }
    .barber-card.selectable img { width: 100%; aspect-ratio: 4/5; object-fit: cover; display: block; border-radius: 8px 8px 0 0; }
    .barber-card.selectable h3 { font-size: 16px; font-weight: 700; letter-spacing: -.02em; margin: 10px 14px 4px; }
    .barber-card.selectable p { font-size: 12px; color: var(--muted); margin: 0 14px 6px; line-height: 1.45; }
    .barber-card.selectable p.rating { color: #bfa04a; font-weight: 700; font-size: 13px; }
    .status-badge { font-size: 10px; font-weight: 700; color: #5a8a4a; letter-spacing: .04em; margin: 8px 14px 0; }
    .btn-ghost { display: inline-block; padding: 10px 20px; font-size: 11px; font-weight: 700; letter-spacing: .03em; border-radius: 6px; text-decoration: none; border: 1px solid var(--line); color: #3a3229; background: #fff; }
</style>
@endpush
