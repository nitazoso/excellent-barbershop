<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $content .= "Jadwal Sesi: {$booking->booking_date->format('d M Y')} · ".substr($booking->start_time, 0, 5)." WIB\n";
        $content .= "Klien: {$booking->customer->name}\n";
        $content .= "Master Barber: {$booking->barber->name}\n";
        $content .= "Layanan: {$booking->service->name}\n";
        $duration = $booking->service->duration_minutes ?? 30;
        $html = '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Struk '.$booking->booking_code.'</title><style>'
            .'.receipt-card{max-width:640px;margin:0 auto;padding:24px;font-family:system-ui,sans-serif;color:#2a1c14;border:1px solid #ddd;border-radius:12px;background:#fff;}'
            .'.logo{font-weight:800;font-size:16px;letter-spacing:-.04em;text-align:center;margin-bottom:6px;}'
            .'.sub{text-align:center;font-size:10px;color:#665a50;margin:2px 0;}'
            .'.receipt-code{background:#faf9f6;border:1px dashed #ddd;border-radius:6px;padding:10px;text-align:center;margin:10px 0;}'
            .'.receipt-code strong{font-size:18px;font-weight:700;}'
            .'.receipt-detail{font-size:12px;color:#3a3229;line-height:1.65;}'
            .'.receipt-detail div{display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid #eee;}'
            .'.policy{font-size:11px;color:#665a50;padding-top:10px;border-top:1px dashed #ddd;margin-top:8px;}'
            .'.badge{display:inline-block;background:#c5a01c;color:#fff;font-size:9px;font-weight:700;padding:3px 8px;border-radius:4px;margin-bottom:12px;text-align:center;width:100%;box-sizing:border-box;}'
            .'.qr{width:120px;height:120px;background:#1e1e1c;color:#fff;border-radius:6px;display:grid;place-items:center;margin:10px auto;font-family:monospace;font-size:10px;letter-spacing:.1em;}'
            .'@media print{body{background:#fff;padding:20px;}.receipt-card{border:none;box-shadow:none;}}'
            .'</style></head><body><div class="receipt-card">'
            .'<div class="badge">SLOT DIAMANKAN</div>'
            .'<h2 style="font-weight:800;font-size:28px;letter-spacing:-.04em;margin:6px 0;text-align:center;">Booking Diterima — Menunggu Konfirmasi Admin</h2>'
            .'<p style="font-size:13px;color:#665a50;text-align:center;margin-bottom:20px;">Detail reservasi telah dikirimkan ke admin. Konfirmasi akan dilakukan oleh admin melalui WhatsApp.</p>'
            .'<div class="logo">EXCELLENT BARBERSHOP</div>'
            .'<div class="sub">CABANG UTAMA SANUR · SIGNATURE SUITE</div>'
            .'<div class="sub">Jl. Selameti No. 42, Sanur · Senopati</div>'
            .'<div class="receipt-code"><label>KODE RESERVASI</label><strong>'.$booking->booking_code.'</strong></div>'
            .'<div class="receipt-detail">'
            .'<div><strong>Jadwal Sesi</strong><span>'.$booking->booking_date->format('l, d M Y').' · '.substr($booking->start_time, 0, 5).' WIB</span></div>'
            .'<div><strong>Klien</strong><span>'.$booking->customer->name.'</span></div>'
            .'<div><strong>Master Barber</strong><span>'.$booking->barber->name.'</span></div>'
            .'<div><strong>Layanan</strong><span>'.$booking->service->name.'</span></div>'
            .'<div><strong>Estimasi</strong><span>'.$duration.' Menit </span></div>'
            .'</div>'
            .'<div class="policy"><strong>KEBIJAKAN KEDATANGAN</strong><p>Harap hadir 5 menit sebelum jadwal. Toleransi keterlambatan maksimal 15 menit sebelum slot otomatis dialokasikan.</p></div>'
            .'</div></body></html>';

        return response($html)
            ->header('Content-Type', 'text/html')
            ->header('Content-Disposition', 'attachment; filename="struk-'.$booking->booking_code.'.html"');
    }

    public function reschedule(string $booking_code): View
    {
        $booking = Booking::query()
            ->with(['customer', 'barber', 'service', 'schedule'])
            ->where('booking_code', $booking_code)
            ->firstOrFail();

        return view('pages.booking.reschedule', ['booking' => $booking]);
    }

    public function updateReschedule(Request $request, string $booking_code): RedirectResponse
    {
        $booking = Booking::query()
            ->with(['customer', 'barber', 'service', 'schedule'])
            ->where('booking_code', $booking_code)
            ->firstOrFail();

        $newScheduleId = $request->input('schedule_id');
        if (! $newScheduleId) {
            return back()->with('error', 'Pilih jadwal yang valid.');
        }

        $map = [
            1 => ['start_time' => '15:00', 'end_time' => '15:30'],
            2 => ['start_time' => '15:30', 'end_time' => '16:00'],
            3 => ['start_time' => '16:00', 'end_time' => '16:30'],
            4 => ['start_time' => '16:30', 'end_time' => '17:00'],
            5 => ['start_time' => '17:00', 'end_time' => '17:30'],
            6 => ['start_time' => '17:30', 'end_time' => '18:00'],
            7 => ['start_time' => '18:00', 'end_time' => '18:30'],
            8 => ['start_time' => '18:30', 'end_time' => '19:00'],
            9 => ['start_time' => '19:00', 'end_time' => '19:30'],
            10 => ['start_time' => '19:30', 'end_time' => '20:00'],
            11 => ['start_time' => '20:00', 'end_time' => '20:30'],
            12 => ['start_time' => '20:30', 'end_time' => '21:00'],
            13 => ['start_time' => '21:00', 'end_time' => '21:30'],
        ];

        if (! array_key_exists((int) $newScheduleId, $map)) {
            return back()->with('error', 'Jadwal yang dipilih tidak valid.');
        }

        $date = $request->input('date') ? Carbon::parse($request->input('date')) : $booking->booking_date;
        $timeSlot = $map[(int) $newScheduleId];

        $slotTime = $date->copy()->setTimeFromTimeString($timeSlot['start_time']);
        if ($date->format('Y-m-d') === today()->format('Y-m-d') && now()->gte($slotTime)) {
            return back()->with('error', 'Tidak dapat memilih jadwal yang sudah melewati waktu saat ini.');
        }

        $newSchedule = Schedule::query()
            ->where('barber_id', $booking->barber_id)
            ->whereDate('date', $date->format('Y-m-d'))
            ->where('start_time', $timeSlot['start_time'])
            ->first();

        if ($newSchedule && ! $newSchedule->is_available) {
            return back()->with('error', 'Jadwal yang dipilih tidak tersedia.');
        }

        if ($newSchedule) {
            $hasActiveBooking = Booking::query()
                ->where('schedule_id', $newSchedule->id)
                ->where('booking_code', '!=', $booking_code)
                ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
                ->exists();

            if ($hasActiveBooking) {
                return back()->with('error', 'Jadwal yang dipilih sudah ada booking yang sedang berlangsung.');
            }
        }

        $oldSchedule = $booking->schedule;

        DB::transaction(function () use ($oldSchedule, $newSchedule, $booking, $timeSlot, $date) {
            if ($oldSchedule && $oldSchedule->id !== ($newSchedule?->id)) {
                $oldSlotTime = $oldSchedule->date->copy()->setTimeFromTimeString($oldSchedule->start_time);
                $isPassed = now()->gte($oldSlotTime);
                $oldSchedule->update(['is_available' => ! $isPassed]);
            }
            if (! $newSchedule) {
                $newSchedule = Schedule::query()->create([
                    'barber_id' => $booking->barber_id,
                    'date' => $date->format('Y-m-d'),
                    'start_time' => $timeSlot['start_time'],
                    'end_time' => $timeSlot['end_time'],
                    'is_available' => false,
                ]);
            } else {
                $newSchedule->update(['is_available' => false]);
            }
            $booking->update([
                'schedule_id' => $newSchedule->id,
                'booking_date' => $date->format('Y-m-d'),
                'start_time' => $timeSlot['start_time'],
                'end_time' => $timeSlot['end_time'],
            ]);
        });

        $booking->load(['customer', 'barber', 'service', 'schedule']);
        $booking = $booking->fresh(['customer', 'barber', 'service', 'schedule']);

        return redirect()->route('booking.result', $booking->booking_code)->with('success', 'Jadwal booking berhasil diperbarui.');
    }

    public function cancel(string $booking_code): View
    {
        $booking = Booking::query()
            ->with(['customer', 'barber', 'service', 'schedule'])
            ->where('booking_code', $booking_code)
            ->firstOrFail();

        return view('pages.booking.cancel', ['booking' => $booking]);
    }

    public function processCancel(Request $request, string $booking_code): RedirectResponse
    {
        $booking = Booking::query()
            ->with(['customer', 'barber', 'service', 'schedule'])
            ->where('booking_code', $booking_code)
            ->firstOrFail();

        if (! $booking->isPending()) {
            return back()->with('error', 'Booking ini tidak lagi menunggu konfirmasi.');
        }

        DB::transaction(function () use ($booking, $request) {
            $booking->update([
                'status' => Booking::STATUS_REJECTED,
                'rejection_reason' => $request->input('notes') ?? null,
            ]);
            $schedule = $booking->schedule;
            $schedule->update(['is_available' => true]);
        });

        return redirect()->route('booking.result', $booking->booking_code)->with('success', 'Booking berhasil dibatalkan. DP akan hangus / tidak dapat dikembalikan.');
    }
}
