<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Schedule;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index(): View
    {
        $bookings = Booking::query()
            ->with(['customer', 'barber', 'service', 'schedule'])
            ->latest('booking_date')
            ->latest('start_time')
            ->paginate(20);

        return view('pages.admin.bookings', compact('bookings'));
    }

    public function updateStatus(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:confirmed,cancelled'],
        ]);

        $wasUpdated = DB::transaction(function () use ($booking, $validated): bool {
            $lockedBooking = Booking::query()
                ->whereKey($booking->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedBooking->status !== 'pending') {
                return false;
            }

            $lockedBooking->update(['status' => $validated['status']]);

            if ($validated['status'] === 'cancelled') {
                $schedule = Schedule::query()
                    ->whereKey($lockedBooking->schedule_id)
                    ->lockForUpdate()
                    ->first();

                $hasActiveBooking = Booking::query()
                    ->where('schedule_id', $lockedBooking->schedule_id)
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->exists();

                if ($schedule && ! $hasActiveBooking) {
                    $schedule->update(['is_available' => true]);
                }
            }

            return true;
        });

        if (! $wasUpdated) {
            return redirect()->route('admin.dashboard')->with('error', 'Booking ini sudah tidak menunggu konfirmasi.');
        }

        $statusLabel = $validated['status'] === 'confirmed' ? 'dikonfirmasi' : 'ditolak';

        return redirect()->route('admin.dashboard')->with('success', "Booking {$booking->booking_code} berhasil {$statusLabel}.");
    }

    public function confirmPending(): RedirectResponse
    {
        $confirmedBookingsCount = Booking::query()
            ->where('status', 'pending')
            ->update(['status' => 'confirmed']);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', "{$confirmedBookingsCount} booking berhasil dikonfirmasi.");
    }
}
