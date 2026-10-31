@extends('layouts.app')

@section('title', 'Booking diterima — Excellent Barbershop')

@section('content')
<section class="booking-page booking-result">
    <span class="section-kicker">BOOKING</span>
    <h1>Terima kasih atas kunjungan Anda.</h1>

    <div class="booking-confirm-card">
        <span class="badge-gold">SLOT DIAMANKAN</span>
        <h2>Booking Diterima — Menunggu Konfirmasi Admin</h2>
        <p>Detail reservasi telah dikirimkan ke admin. Konfirmasi akan dilakukan oleh admin melalui WhatsApp ke
            <strong>{{ $booking->customer->phone }}</strong>.
        </p>

        <div class="receipt-inner">
            <div class="receipt-header">
                <div class="logo">EXCELLENT BARBERSHOP</div>
                <p>CABANG UTAMA SANUR · SIGNATURE SUITE</p>
                <p>Jl. Selameti No. 42, Senur · Senopati</p>
            </div>

            <div class="receipt-code">
                <label>KODE RESERVASI</label>
                <strong>{{ $booking->booking_code }}</strong>
                <button type="button"
                    onclick="navigator.clipboard.writeText('{{ $booking->booking_code }}'); this.innerText='Tersalin';">Salin</button>
            </div>

            <div class="receipt-detail">
                <div><strong>Jadwal Sesi</strong> <span>{{ $booking->booking_date->format('l, d M Y') }}</span>
                </div>
                <div><strong>Sesi</strong>
                    <span>{{ $booking->start_time >= '15:00' && $booking->start_time < '19:30' ? 'Siang' : ($booking->start_time >= '19:30' ? 'Malam' : 'Waktu Istirahat') }}</span>
                </div>
                <div><strong>Klien</strong> <span>{{ $booking->customer->name }}</span></div>
                <div><strong>Master Barber</strong> <span>{{ $booking->barber->name }}</span></div>
                <div><strong>Layanan</strong> <span>{{ $booking->service->name }}</span></div>
                <div><strong>Estimasi</strong> <span>{{ $booking->service->duration_minutes ?? 30 }} Menit </span></div>
            </div>

            <div class="receipt-policy">
                <strong>KEBIJAKAN KEDATANGAN</strong>
                <p>Harap hadir 5 menit sebelum jadwal. Toleransi keterlambatan maksimal 15 menit sebelum slot otomatis
                    dialokasikan.</p>
            </div>
        </div>

        <a class="primary" href="{{ url('/') }}">← Kembali ke Beranda</a>
    </div>
</section>
@endsection

@push('styles')
<style>
    .booking-confirm-card {
        max-width: 640px;
        margin: 0 auto;
        padding: 30px 24px;
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 12px;
        text-align: center;
    }

    .badge-gold {
        display: inline-block;
        background: #c5a01c;
        color: #fff;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: .08em;
        padding: 3px 8px;
        border-radius: 4px;
        margin-bottom: 12px;
    }

    .booking-confirm-card h2 {
        font: 800 28px var(--display);
        letter-spacing: -.04em;
        margin: 6px 0 6px;
    }

    .booking-confirm-card>p {
        color: var(--muted);
        font-size: 13px;
        margin-bottom: 20px;
    }

    .receipt-inner {
        border: 1px solid var(--line);
        border-radius: 10px;
        padding: 22px 18px;
        text-align: left;
        background: #faf9f6;
        margin-bottom: 18px;
    }

    .receipt-header {
        text-align: center;
        padding-bottom: 14px;
        border-bottom: 1px dashed var(--line);
        margin-bottom: 14px;
    }

    .logo {
        font-family: var(--display);
        font-weight: 800;
        font-size: 16px;
        letter-spacing: -.04em;
        color: #2a1c14;
    }

    .receipt-header p {
        font-size: 10px;
        color: #665a50;
        margin: 2px 0;
    }

    .receipt-code {
        text-align: center;
        padding: 10px;
        background: #fff;
        border: 1px dashed var(--line);
        border-radius: 6px;
        margin: 10px 0;
    }

    .receipt-code label {
        font-size: 9px;
        font-weight: 700;
        letter-spacing: .08em;
        color: #7b7168;
        display: block;
    }

    .receipt-code strong {
        font-family: var(--display);
        font-size: 18px;
        font-weight: 700;
        letter-spacing: .02em;
    }

    .receipt-code button {
        margin-top: 6px;
        font-size: 10px;
        padding: 2px 8px;
        border: 1px solid var(--gold);
        background: #fff;
        color: var(--gold);
        border-radius: 4px;
        cursor: pointer;
    }

    .receipt-qr {
        text-align: center;
        padding: 14px;
    }

    .qr-box {
        width: 120px;
        height: 120px;
        margin: 0 auto;
        background: #1e1e1c;
        border-radius: 6px;
        display: grid;
        place-items: center;
        color: #fff;
        font-weight: 700;
        font-family: monospace;
        letter-spacing: .1em;
        font-size: 10px;
    }

    .receipt-qr p {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .06em;
        color: #7b7168;
        margin-top: 6px;
    }

    .receipt-detail {
        font-size: 12px;
        color: #3a3229;
        line-height: 1.65;
    }

    .receipt-detail div {
        padding: 6px 0;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        gap: 12px;
    }

    .receipt-detail div:last-child {
        border-bottom: 0;
    }

    .receipt-detail strong {
        font-weight: 700;
        color: #3a3229;
    }

    .receipt-policy {
        font-size: 11px;
        color: #665a50;
        padding-top: 10px;
        border-top: 1px dashed var(--line);
        margin-top: 8px;
    }

    .receipt-policy strong {
        font-size: 10px;
        letter-spacing: .08em;
        color: #7b7168;
        display: block;
        margin-bottom: 3px;
    }

    .action-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: center;
        margin: 18px 0 14px;
    }

    .btn-dark {
        background: #2a1c14;
        color: #fff;
        text-decoration: none;
        padding: 9px 14px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .03em;
    }

    .btn-ghost {
        background: #fff;
        color: #3a3229;
        text-decoration: none;
        padding: 9px 14px;
        border: 1px solid var(--line);
        border-radius: 6px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .03em;
    }
</style>
@endpush