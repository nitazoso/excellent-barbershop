<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateManualBookingRequest;
use App\Http\Requests\Admin\FilterBookingRequest;
use App\Http\Requests\Admin\RejectBookingRequest;
use App\Models\Barber;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Schedule;
use App\Models\Service;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    /**
     * Tampilkan daftar booking dengan fitur filter.
     */
    public function index(FilterBookingRequest $request): View|JsonResponse
    {
        $filters = $request->validated();
        $query = $this->buildFilteredQuery($filters);
        $summaryFilters = $filters;
        unset($summaryFilters['status']);

        if ($request->expectsJson()) {
            $bookings = $query->paginate(20);
            $summary = $this->calculateSummary($this->buildFilteredQuery($summaryFilters));

            return response()->json([
                'success' => true,
                'summary' => $summary,
                'filters' => $filters,
                'data' => $bookings,
            ]);
        }

        $bookings = $query->paginate(20)->withQueryString();
        $barbers = Barber::query()->where('is_active', true)->orderBy('name')->get();
        $summary = $this->calculateSummary($this->buildFilteredQuery($summaryFilters));

        return view('pages.admin.bookings', compact('bookings', 'barbers', 'filters', 'summary'));
    }

    /**
     * Simpan booking yang dibuat langsung oleh admin.
     */
    public function storeManual(CreateManualBookingRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $booking = DB::transaction(function () use ($validated): Booking {
            $schedule = Schedule::query()
                ->whereKey($validated['schedule_id'])
                ->lockForUpdate()
                ->firstOrFail();
            $barber = Barber::query()->findOrFail($validated['barber_id']);
            $service = Service::query()->findOrFail($validated['service_id']);
            $scheduleHasActiveBooking = Booking::query()
                ->where('schedule_id', $schedule->id)
                ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED, Booking::STATUS_IN_PROGRESS])
                ->exists();
            $scheduleHasPassed = $schedule->date->lt(today())
                || ($schedule->date->isToday() && $schedule->start_time <= now()->format('H:i:s'));

            if (! $barber->is_active) {
                throw ValidationException::withMessages([
                    'barber_id' => 'Barber yang dipilih sedang tidak aktif.',
                ]);
            }

            if (! $service->is_active) {
                throw ValidationException::withMessages([
                    'service_id' => 'Layanan yang dipilih sedang tidak aktif.',
                ]);
            }

            if (
                ! $schedule->is_available
                || (int) $schedule->barber_id !== (int) $barber->id
                || $scheduleHasPassed
                || $scheduleHasActiveBooking
            ) {
                throw ValidationException::withMessages([
                    'schedule_id' => 'Jadwal sudah tidak tersedia. Silakan pilih jadwal lain.',
                ]);
            }

            $customer = Customer::query()->updateOrCreate(
                ['phone' => $validated['customer_phone']],
                [
                    'name' => $validated['customer_name'],
                    'membership_type' => $validated['membership_type'],
                ],
            );

            $schedule->update(['is_available' => false]);

            return Booking::query()->create([
                'booking_code' => $this->generateBookingCode(),
                'customer_id' => $customer->id,
                'barber_id' => $barber->id,
                'service_id' => $service->id,
                'schedule_id' => $schedule->id,
                'booking_date' => $schedule->date,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'status' => Booking::STATUS_PENDING,
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        return to_route('admin.bookings')->with('success', "Booking {$booking->booking_code} berhasil ditambahkan.");
    }

    /**
     * Konfirmasi booking (mengubah status dari pending ke confirmed).
     */
    public function confirm(Request $request, Booking $booking): RedirectResponse|JsonResponse
    {
        DB::transaction(function () use ($booking): void {
            $booking->update([
                'status' => Booking::STATUS_CONFIRMED,
                'rejection_reason' => null,
            ]);

            // Pastikan jadwal tetap berstatus tidak tersedia
            $booking->schedule?->update(['is_available' => false]);
        });

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
     * Konfirmasi semua antrean booking berstatus pending sekaligus (bulk confirmation).
     */
    public function bulkConfirm(Request $request): RedirectResponse|JsonResponse
    {
        $query = Booking::query()->where('status', Booking::STATUS_PENDING);

        if ($request->filled('booking_ids') && is_array($request->input('booking_ids'))) {
            $query->whereIn('id', $request->input('booking_ids'));
        }

        $count = 0;
        DB::transaction(function () use ($query, &$count): void {
            $pendingBookings = $query->with('schedule')->lockForUpdate()->get();
            $count = $pendingBookings->count();

            foreach ($pendingBookings as $booking) {
                $booking->update([
                    'status' => Booking::STATUS_CONFIRMED,
                    'rejection_reason' => null,
                ]);

                $booking->schedule?->update(['is_available' => false]);
            }
        });

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$count} antrean booking berhasil dikonfirmasi sekaligus.",
                'confirmed_count' => $count,
            ]);
        }

        return back()->with('success', "{$count} antrean booking berhasil dikonfirmasi sekaligus.");
    }

    /**
     * Pengelolaan status jadwal booking harian (misal: Selesai, Sedang Cukur, Mendatang, Dibatalkan).
     */
    public function updateStatus(Request $request, Booking $booking): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,confirmed,in_progress,completed,rejected,break'],
            'rejection_reason' => ['required_if:status,rejected', 'nullable', 'string', 'max:1000'],
        ]);

        $newStatus = $validated['status'];

        DB::transaction(function () use ($booking, $newStatus, $validated): void {
            $updateData = ['status' => $newStatus];

            if ($newStatus === Booking::STATUS_REJECTED) {
                $updateData['rejection_reason'] = $validated['rejection_reason'];
                $booking->schedule?->update(['is_available' => true]);
            } else {
                $updateData['rejection_reason'] = null;
                if (in_array($newStatus, [Booking::STATUS_CONFIRMED, Booking::STATUS_IN_PROGRESS, Booking::STATUS_COMPLETED])) {
                    $booking->schedule?->update(['is_available' => false]);
                }
            }

            $booking->update($updateData);
        });

        $booking->load(['customer', 'barber', 'service', 'schedule']);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Status booking #{$booking->booking_code} berhasil diperbarui menjadi {$booking->status_label}.",
                'data' => $booking,
            ]);
        }

        return back()->with('success', "Status booking #{$booking->booking_code} berhasil diperbarui.");
    }

    /**
     * Tolak booking (mengubah status ke rejected dan simpan alasan penolakan).
     */
    public function reject(RejectBookingRequest $request, Booking $booking): RedirectResponse|JsonResponse
    {
        DB::transaction(function () use ($request, $booking): void {
            $booking->update([
                'status' => Booking::STATUS_REJECTED,
                'rejection_reason' => $request->validated('rejection_reason'),
            ]);

            // Kembalikan ketersediaan jadwal agar dapat dipesan kembali oleh pelanggan lain
            $booking->schedule?->update(['is_available' => true]);
        });

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
        $summaryFilters = $filters;
        unset($summaryFilters['status']);
        $bookings = $this->buildFilteredQuery($filters)->get();
        $summary = $this->calculateSummary($this->buildFilteredQuery($summaryFilters));

        if ($request->expectsJson() || $request->ajax() || $request->query('format') === 'json') {
            return response()->json([
                'success' => true,
                'summary' => $summary,
                'filters' => $filters,
                'data' => $bookings,
            ]);
        }

        $barbers = Barber::query()->where('is_active', true)->orderBy('name')->get();

        return view('pages.admin.bookings', [
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

        $pdf = Pdf::loadView('pages.admin.bookings-pdf', compact('bookings', 'filters', 'summary', 'generatedAt'))
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
            ->when(! empty($filters['search']), function (Builder $query) use ($filters): void {
                $search = ltrim(trim((string) $filters['search']), '#');
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('booking_code', 'like', "%{$search}%")
                        ->orWhereHas('customer', function (Builder $customerQuery) use ($search): void {
                            $customerQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('phone', 'like', "%{$search}%");
                        });
                });
            })
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
     * @return array{total: int, pending: int, confirmed: int, in_progress: int, rejected: int, revenue: float}
     */
    protected function calculateSummary(Builder $query): array
    {
        $bookings = $query->get();

        return [
            'total' => $bookings->count(),
            'pending' => $bookings->where('status', Booking::STATUS_PENDING)->count(),
            'confirmed' => $bookings->where('status', Booking::STATUS_CONFIRMED)->count(),
            'in_progress' => $bookings->where('status', Booking::STATUS_IN_PROGRESS)->count(),
            'rejected' => $bookings->where('status', Booking::STATUS_REJECTED)->count(),
            'revenue' => (float) $bookings->where('status', Booking::STATUS_CONFIRMED)->sum(function (Booking $booking): float {
                return (float) ($booking->service?->price ?? 0);
            }),
        ];
    }

    private function generateBookingCode(): string
    {
        do {
            $bookingCode = 'EXC-'.Str::upper(Str::random(6));
        } while (Booking::query()->where('booking_code', $bookingCode)->exists());

        return $bookingCode;
    }
}
