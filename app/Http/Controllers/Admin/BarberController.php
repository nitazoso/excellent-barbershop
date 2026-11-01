<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BarberController extends Controller
{
    /**
     * Tampilkan halaman Kelola Master Barber.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $query = Barber::query()->orderBy('chair_number');

        if ($status === 'aktif') {
            $query->where('is_active', true);
        } elseif ($status === 'nonaktif') {
            $query->where(function ($q) {
                $q->where('is_active', false)
                    ->orWhere('availability_status', Barber::STATUS_CUTI)
                    ->orWhere('availability_status', Barber::STATUS_NONAKTIF);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('specialization', 'like', "%{$search}%")
                    ->orWhere('skills_tags', 'like', "%{$search}%")
                    ->orWhere('role_title', 'like', "%{$search}%");
            });
        }

        $barbers = $query->get();

        // Data statistik dari seluruh barber
        $allBarbers = Barber::query()->orderBy('chair_number')->get();
        $totalChairs = $allBarbers->count();
        $activeCount = $allBarbers->where('is_active', true)->where('availability_status', '!=', Barber::STATUS_CUTI)->count();
        $inactiveCount = $totalChairs - $activeCount;

        // Kapasitas reservasi per hari
        $scheduledSum = $allBarbers->sum('scheduled_sessions_count');
        $capacityPerDay = $scheduledSum > 0 ? ($scheduledSum + 2) : ($activeCount * 7);

        // Rating
        $reviewedBarbers = $allBarbers->where('rating', '>', 0);
        $avgRating = $reviewedBarbers->count() > 0 ? round($reviewedBarbers->avg('rating'), 2) : 4.95;

        // Persentase status operasional
        $pct = $totalChairs > 0 ? round(($activeCount / $totalChairs) * 100) : 100;
        $statusLabel = $pct >= 75 ? "Optimal ({$pct}%)" : "Terbatas ({$pct}%)";

        $stats = [
            'total_chairs' => $totalChairs,
            'capacity_per_day' => $capacityPerDay,
            'avg_rating' => number_format($avgRating, 2),
            'status_label' => $statusLabel,
            'active_percentage' => $pct,
        ];

        $counts = [
            'all' => $totalChairs,
            'aktif' => $activeCount,
            'nonaktif' => $inactiveCount,
        ];

        return view('pages.admin.barbers.index', compact(
            'barbers',
            'stats',
            'counts',
            'status',
            'search'
        ));
    }

    /**
     * Tambah data barber baru.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'chair_number' => ['nullable', 'integer', 'min:1'],
            'role_title' => ['nullable', 'string', 'max:255'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'skills_tags' => ['nullable', 'string', 'max:1000'],
            'availability_status' => ['nullable', 'string', 'in:online,cuti,nonaktif'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'review_count' => ['nullable', 'integer', 'min:0'],
            'scheduled_sessions_count' => ['nullable', 'integer', 'min:0'],
            'accept_public_booking' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string', 'max:1000'],
            'warning_note' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $nextChair = Barber::query()->max('chair_number') + 1;

        $barber = Barber::create([
            'name' => $validated['name'],
            'chair_number' => $validated['chair_number'] ?? $nextChair,
            'role_title' => $validated['role_title'] ?? 'Barber Stylist',
            'specialization' => $validated['specialization'] ?? 'Professional Grooming & Styling',
            'skills_tags' => $validated['skills_tags'] ?? 'Classic Cut, Modern Fade',
            'availability_status' => $validated['availability_status'] ?? Barber::STATUS_ONLINE,
            'rating' => $validated['rating'] ?? 5.0,
            'review_count' => $validated['review_count'] ?? 0,
            'scheduled_sessions_count' => $validated['scheduled_sessions_count'] ?? 0,
            'accept_public_booking' => $request->has('accept_public_booking') ? $request->boolean('accept_public_booking') : true,
            'description' => $validated['description'] ?? 'Barber Professional',
            'warning_note' => $validated['warning_note'] ?? null,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Barber {$barber->name} berhasil ditambahkan.",
                'data' => $barber,
            ]);
        }

        return redirect()->route('admin.barbers.index')
            ->with('success', "Barber {$barber->name} berhasil ditambahkan.");
    }

    /**
     * Tampilkan form edit barber.
     */
    public function edit(Barber $barber): View|JsonResponse
    {
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $barber,
            ]);
        }

        return view('pages.admin.barbers.edit', compact('barber'));
    }

    /**
     * Perbarui data barber.
     */
    public function update(Request $request, Barber $barber): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'chair_number' => ['nullable', 'integer', 'min:1'],
            'role_title' => ['nullable', 'string', 'max:255'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'skills_tags' => ['nullable', 'string', 'max:1000'],
            'availability_status' => ['nullable', 'string', 'in:online,cuti,nonaktif'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
            'review_count' => ['nullable', 'integer', 'min:0'],
            'scheduled_sessions_count' => ['nullable', 'integer', 'min:0'],
            'accept_public_booking' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string', 'max:1000'],
            'warning_note' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $barber->update([
            'name' => $validated['name'],
            'chair_number' => $validated['chair_number'] ?? $barber->chair_number,
            'role_title' => $validated['role_title'] ?? $barber->role_title,
            'specialization' => $validated['specialization'] ?? $barber->specialization,
            'skills_tags' => $validated['skills_tags'] ?? $barber->skills_tags,
            'availability_status' => $validated['availability_status'] ?? $barber->availability_status,
            'rating' => $validated['rating'] ?? $barber->rating,
            'review_count' => $validated['review_count'] ?? $barber->review_count,
            'scheduled_sessions_count' => $validated['scheduled_sessions_count'] ?? $barber->scheduled_sessions_count,
            'accept_public_booking' => $request->has('accept_public_booking') ? $request->boolean('accept_public_booking') : $barber->accept_public_booking,
            'description' => $validated['description'] ?? $barber->description,
            'warning_note' => $validated['warning_note'] ?? $barber->warning_note,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : $barber->is_active,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Data barber {$barber->name} berhasil diperbarui.",
                'data' => $barber->fresh(),
            ]);
        }

        return redirect()->route('admin.barbers.index')
            ->with('success', "Data barber {$barber->name} berhasil diperbarui.");
    }

    /**
     * Hapus data barber.
     */
    public function destroy(Barber $barber): RedirectResponse|JsonResponse
    {
        $name = $barber->name;
        $barber->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Barber {$name} berhasil dihapus.",
            ]);
        }

        return redirect()->route('admin.barbers.index')
            ->with('success', "Barber {$name} berhasil dihapus.");
    }

    /**
     * Toggle status booking publik barber.
     */
    public function togglePublicBooking(Barber $barber): RedirectResponse|JsonResponse
    {
        $barber->update([
            'accept_public_booking' => ! $barber->accept_public_booking,
        ]);

        $statusText = $barber->accept_public_booking ? 'aktif (menerima booking publik)' : 'dinonaktifkan dari form reservasi';

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'accept_public_booking' => $barber->accept_public_booking,
                'message' => "Status booking publik {$barber->name} sekarang {$statusText}.",
            ]);
        }

        return back()->with('success', "Status booking publik {$barber->name} berhasil diperbarui.");
    }

    /**
     * Toggle status aktif barber (atau aktifkan kembali barber yang cuti/nonaktif).
     */
    public function toggleActive(Barber $barber): RedirectResponse|JsonResponse
    {
        $newActive = ! $barber->is_active;
        $newStatus = $newActive ? Barber::STATUS_ONLINE : Barber::STATUS_NONAKTIF;

        $barber->update([
            'is_active' => $newActive,
            'availability_status' => $newStatus,
            'accept_public_booking' => $newActive,
        ]);

        $msg = $newActive ? "Barber {$barber->name} berhasil diaktifkan kembali." : "Barber {$barber->name} berhasil dinonaktifkan.";

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => $barber->is_active,
                'availability_status' => $barber->availability_status,
                'message' => $msg,
            ]);
        }

        return back()->with('success', $msg);
    }
}
