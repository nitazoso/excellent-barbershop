@extends('layouts.app')

@section('title', 'Batalkan Reservasi — Excellent Barbershop')

@section('content')
<section class="booking-page">
    <div style="max-width:640px;margin:0 auto;padding:30px 24px;background:#fff;border:1px solid var(--line);border-radius:16px;box-shadow:0 2px 14px rgba(0,0,0,.06);">
        <h2 style="font-family:var(--display);font-size:22px;font-weight:800;letter-spacing:-.02em;color:#3a3229;margin:0 0 18px;display:flex;align-items:center;gap:10px;">
            <span style="background:#c0392b;color:#fff;width:36px;height:36px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:16px;font-weight:700;">✓</span>
            Batalkan Reservasi
        </h2>

        <div style="background:#faf9f6;border:1px solid var(--line);border-radius:10px;padding:16px;margin-bottom:18px;">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px;">
                <div style="width:40px;height:40px;border-radius:50%;background:#3a3229;color:#fff;display:flex;align-items:center;justify-content:center;font-family:var(--display);font-size:16px;font-weight:700;">FR</div>
                <div>
                    <strong style="font-size:15px;font-weight:700;color:#3a3229;">Raka Pratama</strong>
                    <span style="font-size:11px;color:#7b7168;margin-left:8px;">#EB-8951</span>
                </div>
            </div>
            <div style="font-size:12px;color:#3a3229;line-height:1.6;">
                <div>Jadwal: <strong>{{ $booking->booking_date->format('l, d M Y') }}</strong> · {{ $booking->start_time }}</div>
                <div>Layanan: <strong>{{ $booking->service->name }}</strong> dengan <strong>{{ $booking->barber->name }}</strong></div>
            </div>
        </div>

        <form method="POST" action="{{ route('booking.cancel.process', $booking->booking_code) }}">
            @csrf
            <label for="notes" style="display:block;font-size:11px;font-weight:700;letter-spacing:.04em;margin:8px 0 6px;color:#3a3229;">PESAN ALASAN PEMBATALAN (OPSIONAL)</label>
            <textarea id="notes" name="notes" rows="3" placeholder="Silakan tulis alasan pembatalan..." style="width:100%;padding:10px 12px;border:1px solid var(--line);border-radius:8px;font-family:inherit;font-size:14px;background:#fdfcfb;resize:vertical;">{{ old('notes', $booking->notes ?? '') }}</textarea>

            <div style="background:#fff8e1;border:1px solid #ecc94b;border-radius:8px;padding:14px 16px;margin:18px 0;">
                <strong style="font-size:13px;color:#b7791f;display:block;margin-bottom:6px;">⚠ Harap diperhatikan, jika bookingan di batalkan maka uang DP akan hangus / tidak bisa di kembalikan.</strong>
                <p style="font-size:12px;color:#665a50;margin:0;line-height:1.55;">Harap pastikan Anda sudah yakin sebelum mengirim pembatalan. Admin akan segera memproses pembatalan setelah konfirmasi diterima.</p>
            </div>

            <button type="submit" style="width:100%;padding:14px;font-size:13px;font-weight:700;letter-spacing:.04em;border-radius:8px;border:none;background:#c0392b;color:#fff;cursor:pointer;margin-bottom:8px;">▶ Kirim Pembatalan</button>
            <a href="{{ route('booking.result', $booking->booking_code) }}" style="display:block;text-align:center;padding:10px;font-size:11px;font-weight:700;color:#3a3229;text-decoration:none;background:#f4f1ec;border-radius:8px;">Batal</a>
        </form>
    </div>
</section>
@endsection