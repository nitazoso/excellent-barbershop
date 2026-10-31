@extends('layouts.app')

@section('title', 'Konfirmasi Pesanan — Excellent Barbershop')

@section('content')
<section class="booking-page">
    <span class="section-kicker">BOOKING ONLINE — LANGKAH 3</span>
    <h1>Konfirmasi Pesanan</h1>
    <p style="color:#666;font-size:14px;margin-top:-8px;margin-bottom:16px;">Pesanan dikirimkan ke admin dan akan dikonfirmasi apabila status sudah lengkap. Admin akan menghubungi melalui WhatsApp Anda.</p>

    <form method="POST" action="{{ route('booking.store') }}" id="confirm-form">
        @csrf
        <input type="hidden" name="barber_id" value="{{ $barber->id }}">
        <input type="hidden" name="service_id" value="{{ $service->id ?? 1 }}">
        <input type="hidden" name="schedule_id" value="{{ $schedule_id }}">
        <input type="hidden" name="date" value="{{ $date }}">

        <div class="info-card" style="background:#fff;border:1px solid var(--line);border-radius:10px;padding:18px;margin:14px 0;">
            <h3 style="font-size:14px;font-weight:700;letter-spacing:.02em;margin:0 0 10px;">Informasi Pelanggan</h3>
            <label for="name" style="display:block;font-size:11px;font-weight:700;letter-spacing:.04em;margin:8px 0 4px;color:#3a3229;">Nama Lengkap <span style="color:var(--gold);">*</span></label>
            <input id="name" name="name" type="text" required maxlength="255" placeholder="Raka Pratama" style="width:100%;padding:10px 12px;border:1px solid var(--line);border-radius:6px;font-family:inherit;font-size:14px;background:#fdfcfb;">

            <label for="phone" style="display:block;font-size:11px;font-weight:700;letter-spacing:.04em;margin:8px 0 4px;color:#3a3229;">Nomor WhatsApp Aktif <span style="color:var(--gold);">*</span></label>
            <input id="phone" name="phone" type="tel" required maxlength="20" placeholder="+62 812-3456-7890" style="width:100%;padding:10px 12px;border:1px solid var(--line);border-radius:6px;font-family:inherit;font-size:14px;background:#fdfcfb;">

            <label for="notes" style="display:block;font-size:11px;font-weight:700;letter-spacing:.04em;margin:8px 0 4px;color:#3a3229;">Catatan (opsional)</label>
            <textarea id="notes" name="notes" rows="3" placeholder="Permintaan khusus, alergi, atau preferensi." style="width:100%;padding:10px 12px;border:1px solid var(--line);border-radius:6px;font-family:inherit;font-size:14px;background:#fdfcfb;resize:vertical;"></textarea>
        </div>

        <div class="summary-card" style="background:#fff;border:1px solid var(--line);border-radius:10px;padding:18px;margin:14px 0;">
            <h3 style="font-size:14px;font-weight:700;letter-spacing:.02em;margin:0 0 10px;">Ringkasan Reservasi</h3>
            <div><strong>Barber</strong> <span>{{ $barber->name }}</span></div>
            <div><strong>Jadwal</strong> <span>{{ $date }}, {{ $timeStr }} WIB</span></div>
            <div><strong>Layanan</strong> <span>{{ $service->name ?? 'Skin Fade, Classic Scissor & Beard · 30 Menit' }}</span></div>

            <div class="policy" style="font-size:11px;line-height:1.65;color:#665a50;border-top:1px dashed var(--line);margin-top:12px;padding-top:10px;">
                <p><strong>Kebijakan Kedatangan & Pembatalan</strong></p>
                <p>• Harap tiba di cabang 10 menit sebelum sesi dimulai.</p>
                <p>• Tidak dapat dibatalkan maksimal 15 menit sebelum jadwal.</p>
                <p>• Lewat dari batas waktu, reservasi dialihkan pada antrean berikutnya.</p>
                <p>• Penjadwulan ulang gratis dapat dilakukan maksimal 1 jam sebelum waktu appointment melalui WhatsApp.</p>
            </div>
        </div>

        <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;margin-top:22px;padding-top:14px;border-top:1px solid var(--line);">
            <a href="{{ route('booking.schedule', ['barber_id' => $barber->id, 'date' => $date]) }}" style="padding:10px 20px;font-size:11px;font-weight:700;letter-spacing:.03em;border-radius:6px;text-decoration:none;border:1px solid var(--gold);color:var(--gold);background:#fff;">← Kembali</a>
            <button type="submit" class="primary" style="padding:10px 20px;font-size:11px;font-weight:700;letter-spacing:.03em;border-radius:6px;border:1px solid var(--gold);background:var(--gold);color:#fff;cursor:pointer;">Konfirmasi & Tiket →</button>
        </div>
    </form>
</section>
@endsection
