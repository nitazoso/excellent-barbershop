<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use App\Models\BarbershopSetting;
use App\Models\Booking;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman Dashboard Admin dengan ringkasan operasional harian.
     */
    public function index(Request $request): View|JsonResponse
    {
        $today = Carbon::today();
        $selectedBarberId = $request->query('barber_id');

        // Ringkasan Statistik Operasional Harian
        $todayBookingsQuery = Booking::query()->whereDate('booking_date', $today);

        $totalToday = (clone $todayBookingsQuery)->count();
        $pendingCount = Booking::query()->where('status', Booking::STATUS_PENDING)->count();
        $completedToday = (clone $todayBookingsQuery)->where('status', Booking::STATUS_COMPLETED)->count();
        $rejectedToday = (clone $todayBookingsQuery)->where('status', Booking::STATUS_REJECTED)->count();
        $inProgressToday = (clone $todayBookingsQuery)->where('status', Booking::STATUS_IN_PROGRESS)->count();
        $confirmedToday = (clone $todayBookingsQuery)->where('status', Booking::STATUS_CONFIRMED)->count();

        $stats = [
            'total_today' => $totalToday,
            'pending_review' => $pendingCount,
            'in_progress_today' => $inProgressToday,
            'completed_today' => $completedToday,
            'rejected_today' => $rejectedToday,
            'confirmed_today' => $confirmedToday,
            'formatted_total' => sprintf('%02d', $totalToday),
            'formatted_pending' => sprintf('%02d', $pendingCount),
            'formatted_completed' => sprintf('%02d', $completedToday),
            'formatted_rejected' => sprintf('%02d', $rejectedToday),
            'total_change' => '+12.5%',
            'pending_label' => 'Perlu Tindakan',
            'completed_change' => '+8.4%',
            'rejected_change' => '-2.1%',
        ];

        // Daftar Antrean yang Perlu Ditinjau Segera (Status Pending)
        $pendingBookings = Booking::query()
            ->with(['customer', 'barber', 'service', 'schedule'])
            ->where('status', Booking::STATUS_PENDING)
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->get();

        // Jadwal Booking Hari Ini dengan Filter Kursi/Barber
        $todaySchedules = Booking::query()
            ->with(['customer', 'barber', 'service', 'schedule'])
            ->whereDate('booking_date', $today)
            ->when(! empty($selectedBarberId), function ($query) use ($selectedBarberId): void {
                $query->where('barber_id', $selectedBarberId);
            })
            ->orderBy('start_time')
            ->get();

        // Data Master untuk Filter & Modal Quick Actions
        $barbers = Barber::query()->where('is_active', true)->orderBy('id')->get();
        $services = Service::query()->where('is_active', true)->orderBy('name')->get();
        $setting = BarbershopSetting::query()->firstOrCreate(
            [],
            [
                'name' => 'Excellent Barbershop',
                'opening_time' => '10:00:00',
                'closing_time' => '21:00:00',
                'phone' => '+62 21 5550 1928',
                'address' => 'Jl. Senopati No. 42B, Kebayoran Baru, Jakarta Selatan',
            ]
        );

        if ($request->expectsJson() || $request->query('format') === 'json') {
            return response()->json([
                'success' => true,
                'stats' => $stats,
                'pending_bookings' => $pendingBookings,
                'today_schedules' => $todaySchedules,
                'barbers' => $barbers,
                'selected_barber_id' => $selectedBarberId,
                'setting' => $setting,
                'timestamp' => now()->toIso8601String(),
            ]);
        }

        return view('pages.admin.dashboard', compact(
            'stats',
            'pendingBookings',
            'todaySchedules',
            'barbers',
            'services',
            'selectedBarberId',
            'setting'
        ));
    }

    /**
     * API Endpoint untuk mengambil ringkasan statistik dan antrean dashboard secara real-time.
     */
    public function stats(Request $request): JsonResponse
    {
        $today = Carbon::today();
        $selectedBarberId = $request->query('barber_id');

        $todayBookingsQuery = Booking::query()->whereDate('booking_date', $today);

        $totalToday = (clone $todayBookingsQuery)->count();
        $pendingCount = Booking::query()->where('status', Booking::STATUS_PENDING)->count();
        $completedToday = (clone $todayBookingsQuery)->where('status', Booking::STATUS_COMPLETED)->count();
        $rejectedToday = (clone $todayBookingsQuery)->where('status', Booking::STATUS_REJECTED)->count();
        $inProgressToday = (clone $todayBookingsQuery)->where('status', Booking::STATUS_IN_PROGRESS)->count();
        $confirmedToday = (clone $todayBookingsQuery)->where('status', Booking::STATUS_CONFIRMED)->count();

        $stats = [
            'total_today' => $totalToday,
            'pending_review' => $pendingCount,
            'in_progress_today' => $inProgressToday,
            'completed_today' => $completedToday,
            'rejected_today' => $rejectedToday,
            'confirmed_today' => $confirmedToday,
            'formatted_total' => sprintf('%02d', $totalToday),
            'formatted_pending' => sprintf('%02d', $pendingCount),
            'formatted_completed' => sprintf('%02d', $completedToday),
            'formatted_rejected' => sprintf('%02d', $rejectedToday),
            'total_change' => '+12.5%',
            'pending_label' => 'Perlu Tindakan',
            'completed_change' => '+8.4%',
            'rejected_change' => '-2.1%',
        ];

        $pendingBookings = Booking::query()
            ->with(['customer', 'barber', 'service', 'schedule'])
            ->where('status', Booking::STATUS_PENDING)
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->get();

        $todaySchedules = Booking::query()
            ->with(['customer', 'barber', 'service', 'schedule'])
            ->whereDate('booking_date', $today)
            ->when(! empty($selectedBarberId), function ($query) use ($selectedBarberId): void {
                $query->where('barber_id', $selectedBarberId);
            })
            ->orderBy('start_time')
            ->get();

        return response()->json([
            'success' => true,
            'stats' => $stats,
            'pending_count' => $pendingBookings->count(),
            'pending_bookings' => $pendingBookings,
            'today_schedules' => $todaySchedules,
            'timestamp' => now()->toIso8601String(),
            'formatted_time' => now()->format('H:i:s'),
        ]);
    }
}
