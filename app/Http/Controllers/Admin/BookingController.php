<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FilterBookingRequest;
use App\Http\Requests\Admin\RejectBookingRequest;
use App\Models\Barber;
use App\Models\Booking;
use App\Models\Schedule;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Tampilkan daftar booking dengan fitur filter.
     */
    public function index(FilterBookingRequest $request): View|JsonResponse
    {
        $filters = $request->validated();
        $query = $this->buildFilteredQuery($filters);

        if ($request->expectsJson()) {
            $bookings = $query->paginate(20);
            $summary = $this->calculateSummary($this->buildFilteredQuery($filters));

            return response()->json([
                'success' => true,
                'summary' => $summary,
                'filters' => $filters,
                'data' => $bookings,
            ]);
        }

        $bookings = $query->paginate(20)->withQueryString();
        $barbers = Barber::query()->orderBy('name')->get();
        $summary = $this->calculateSummary($this->buildFilteredQuery($filters));

        return view('pages.admin.bookings.index', compact('bookings', 'barbers', 'filters', 'summary'));
    }

    /**
     * Konfirmasi booking (mengubah status dari pending ke confirmed).
     */
    public function confirm(Request $request, Booking $booking): RedirectResponse|JsonResponse
    {
        $wasUpdated = DB::transaction(function () use ($booking): bool {
            $lockedBooking = Booking::query()->whereKey($booking->id)->lockForUpdate()->firstOrFail();

            if (! $lockedBooking->isPending()) {
                return false;
            }

            $lockedBooking->update([
                'status' => Booking::STATUS_CONFIRMED,
                'rejection_reason' => null,
            ]);

            Schedule::query()->whereKey($lockedBooking->schedule_id)->lockForUpdate()->update(['is_available' => false]);

            return true;
        });

        if (! $wasUpdated) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Booking ini sudah tidak menunggu konfirmasi.'], 409);
            }

            return back()->with('error', 'Booking ini sudah tidak menunggu konfirmasi.');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dikonfirmasi.',
                'data' => $booking->fresh(['customer', 'barber', 'service', 'schedule']),
            ]);
        }

        return back()->with('success', "Booking {$booking->booking_code} berhasil dikonfirmasi.");
    }

    /**
     * Tolak booking (mengubah status ke rejected dan simpan alasan penolakan).
     */
    public function reject(RejectBookingRequest $request, Booking $booking): RedirectResponse|JsonResponse
    {
        $wasUpdated = DB::transaction(function () use ($request, $booking): bool {
            $lockedBooking = Booking::query()->whereKey($booking->id)->lockForUpdate()->firstOrFail();

            if (! $lockedBooking->isPending()) {
                return false;
            }

            $lockedBooking->update([
                'status' => Booking::STATUS_REJECTED,
                'rejection_reason' => $request->validated('rejection_reason'),
            ]);

            $schedule = Schedule::query()->whereKey($lockedBooking->schedule_id)->lockForUpdate()->first();
            $hasActiveBooking = Booking::query()
                ->where('schedule_id', $lockedBooking->schedule_id)
                ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED])
                ->exists();

            if ($schedule && ! $hasActiveBooking) {
                $schedule->update(['is_available' => true]);
            }

            return true;
        });

        if (! $wasUpdated) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Booking ini sudah tidak menunggu konfirmasi.'], 409);
            }

            return back()->with('error', 'Booking ini sudah tidak menunggu konfirmasi.');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil ditolak.',
                'data' => $booking->fresh(['customer', 'barber', 'service', 'schedule']),
            ]);
        }

        return back()->with('success', "Booking {$booking->booking_code} berhasil ditolak.");
    }

    /**
     * Ambil rekap data booking dengan filter (API / endpoint rekap).
     */
    public function report(FilterBookingRequest $request): JsonResponse|View
    {
        $filters = $request->validated();
        $bookings = $this->buildFilteredQuery($filters)->get();
        $summary = $this->calculateSummary($this->buildFilteredQuery($filters));

        if ($request->expectsJson() || $request->ajax() || $request->query('format') === 'json') {
            return response()->json([
                'success' => true,
                'summary' => $summary,
                'filters' => $filters,
                'data' => $bookings,
            ]);
        }

        $barbers = Barber::query()->orderBy('name')->get();

        return view('pages.admin.bookings.index', [
            'bookings' => $this->buildFilteredQuery($filters)->paginate(20)->withQueryString(),
            'barbers' => $barbers,
            'filters' => $filters,
            'summary' => $summary,
        ]);
    }

    /**
     * Ekspor rekap data booking ke PDF menggunakan barryvdh/laravel-dompdf.
     */
    public function exportPdf(FilterBookingRequest $request): Response|RedirectResponse
    {
        if (! class_exists(Pdf::class)) {
            return back()->with('error', 'Package barryvdh/laravel-dompdf belum terinstall. Silakan jalankan `composer require barryvdh/laravel-dompdf` di terminal.');
        }

        $filters = $request->validated();
        $bookings = $this->buildFilteredQuery($filters)->get();
        $summary = $this->calculateSummary($this->buildFilteredQuery($filters));
        $generatedAt = now();

        $pdf = Pdf::loadView('pages.admin.bookings.bookings-pdf', compact('bookings', 'filters', 'summary', 'generatedAt'))
            ->setPaper('a4', 'landscape');

        $filename = 'laporan-booking-'.now()->format('Ymd_His').'.pdf';

        if ($request->boolean('stream')) {
            return $pdf->stream($filename);
        }

        return $pdf->download($filename);
    }

    /**
     * Membangun query pencarian data booking dengan filter.
     *
     * @param  array<string, mixed>  $filters
     * @return Builder<Booking>
     */
    protected function buildFilteredQuery(array $filters): Builder
    {
        return Booking::query()
            ->with(['customer', 'barber', 'service', 'schedule'])
            ->when(! empty($filters['start_date']), function (Builder $query) use ($filters): void {
                $query->whereDate('booking_date', '>=', $filters['start_date']);
            })
            ->when(! empty($filters['end_date']), function (Builder $query) use ($filters): void {
                $query->whereDate('booking_date', '<=', $filters['end_date']);
            })
            ->when(! empty($filters['status']), function (Builder $query) use ($filters): void {
                $query->where('status', $filters['status']);
            })
            ->when(! empty($filters['barber_id']), function (Builder $query) use ($filters): void {
                $query->where('barber_id', $filters['barber_id']);
            })
            ->latest('booking_date')
            ->latest('start_time');
    }

    /**
     * Menghitung ringkasan statistik rekap data booking.
     *
     * @param  Builder<Booking>  $query
     * @return array{total: int, pending: int, confirmed: int, rejected: int, revenue: float}
     */
    protected function calculateSummary(Builder $query): array
    {
        $bookings = $query->get();

        return [
            'total' => $bookings->count(),
            'pending' => $bookings->where('status', Booking::STATUS_PENDING)->count(),
            'confirmed' => $bookings->where('status', Booking::STATUS_CONFIRMED)->count(),
            'rejected' => $bookings->where('status', Booking::STATUS_REJECTED)->count(),
            'revenue' => (float) $bookings->where('status', Booking::STATUS_CONFIRMED)->sum(function (Booking $booking): float {
                return (float) ($booking->service?->price ?? 0);
            }),
        ];
    }

    public function confirmPending(): RedirectResponse
    {
        $confirmedBookingsCount = Booking::query()
            ->where('status', Booking::STATUS_PENDING)
            ->update(['status' => Booking::STATUS_CONFIRMED]);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', "{$confirmedBookingsCount} booking berhasil dikonfirmasi.");
    }
}
