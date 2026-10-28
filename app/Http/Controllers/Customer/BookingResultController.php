<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Contracts\View\View;

class BookingResultController extends Controller
{
    public function show(string $booking_code): View
    {
        $booking = Booking::query()
            ->with(['customer', 'barber', 'service', 'schedule'])
            ->where('booking_code', $booking_code)
            ->firstOrFail();

        return view('pages.booking.result', ['booking' => $booking]);
    }

    public function download(string $booking_code)
    {
        $booking = Booking::query()
            ->with(['customer', 'barber', 'service', 'schedule'])
            ->where('booking_code', $booking_code)
            ->firstOrFail();

        $content = "EXCELLENT BARBERSHOP\n";
        $content .= "CABANG UTAMA SANUR · SIGNATURE SUITE\n";
        $content .= "Jl. Selameti No. 42, Senur · Senopati\n\n";
        $content .= "KODE RESERVASI: {$booking->booking_code}\n";
        $content .= "Jadwal: {$booking->booking_date->format('d M Y')} · WIB\n";
        $content .= "Klien: {$booking->customer->name}\n";
        $content .= "Master Barber: {$booking->barber->name}\n";
        $content .= "Layanan: {$booking->service->name}\n";
        $duration = $booking->service->duration_minutes ?? 30;
        $html = '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Struk ' . $booking->booking_code . '</title><style>'
            . '.receipt-card{max-width:640px;margin:0 auto;padding:24px;font-family:system-ui,sans-serif;color:#2a1c14;border:1px solid #ddd;border-radius:12px;background:#fff;}'
            . '.logo{font-weight:800;font-size:16px;letter-spacing:-.04em;text-align:center;margin-bottom:6px;}'
            . '.sub{text-align:center;font-size:10px;color:#665a50;margin:2px 0;}'
            . '.receipt-code{background:#faf9f6;border:1px dashed #ddd;border-radius:6px;padding:10px;text-align:center;margin:10px 0;}'
            . '.receipt-code strong{font-size:18px;font-weight:700;}'
            . '.receipt-detail{font-size:12px;color:#3a3229;line-height:1.65;}'
            . '.receipt-detail div{display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid #eee;}'
            . '.policy{font-size:11px;color:#665a50;padding-top:10px;border-top:1px dashed #ddd;margin-top:8px;}'
            . '.badge{display:inline-block;background:#c5a01c;color:#fff;font-size:9px;font-weight:700;padding:3px 8px;border-radius:4px;margin-bottom:12px;text-align:center;width:100%;box-sizing:border-box;}'
            . '.qr{width:120px;height:120px;background:#1e1e1c;color:#fff;border-radius:6px;display:grid;place-items:center;margin:10px auto;font-family:monospace;font-size:10px;letter-spacing:.1em;}'
            . '@media print{body{background:#fff;padding:20px;}.receipt-card{border:none;box-shadow:none;}}'
            . '</style></head><body><div class="receipt-card">'
            . '<div class="badge">SLOT DIAMANKAN</div>'
            . '<h2 style="font-weight:800;font-size:28px;letter-spacing:-.04em;margin:6px 0;text-align:center;">Booking Terconfirm</h2>'
            . '<p style="font-size:13px;color:#665a50;text-align:center;margin-bottom:20px;">Detail reservasi telah dikirimkan melalui WhatsApp.</p>'
            . '<div class="logo">EXCELLENT BARBERSHOP</div>'
            . '<div class="sub">CABANG UTAMA SANUR · SIGNATURE SUITE</div>'
            . '<div class="sub">Jl. Selameti No. 42, Sanur · Senopati</div>'
            . '<div class="receipt-code"><label>KODE RESERVASI</label><strong>' . $booking->booking_code . '</strong></div>'
            . '<div class="receipt-detail">'
            . '<div><strong>Jadwal Sesi</strong><span>' . $booking->booking_date->format('l, d M Y') . ' · WIB</span></div>'
            . '<div><strong>Klien</strong><span>' . $booking->customer->name . '</span></div>'
            . '<div><strong>Master Barber</strong><span>' . $booking->barber->name . '</span></div>'
            . '<div><strong>Layanan</strong><span>' . $booking->service->name . '</span></div>'
            . '<div><strong>Estimasi</strong><span>' . $duration . ' Menit </span></div>'
            . '</div>'
            . '<div class="policy"><strong>KEBIJAKAN KEDATANGAN</strong><p>Harap hadir 5 menit sebelum jadwal. Toleransi keterlambatan maksimal 15 menit sebelum slot otomatis dialokasikan.</p></div>'
            . '</div></body></html>';

        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="struk-' . $booking->booking_code . '.html"');
    }
}
