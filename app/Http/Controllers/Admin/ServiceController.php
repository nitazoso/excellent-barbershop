<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    /**
     * Tampilkan halaman Kelola Katalog Layanan.
     */
    public function index(Request $request): View
    {
        $category = $request->query('category');
        $status = $request->query('status');
        $search = $request->query('search');

        $query = Service::query()->orderBy('id');

        if ($category && in_array($category, [Service::CAT_HAIRCUT, Service::CAT_BEARD, Service::CAT_TREATMENT])) {
            $query->where('category', $category);
        }

        if ($status === 'aktif') {
            $query->where('is_active', true);
        } elseif ($status === 'nonaktif') {
            $query->where('is_active', false);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $services = $query->get();

        // Data statistik dari seluruh katalog layanan
        $allServices = Service::query()->get();
        $totalServices = $allServices->count();
        $activeCount = $allServices->where('is_active', true)->count();
        $inactiveCount = $allServices->where('is_active', false)->count();

        $activeCategoriesCount = $allServices->pluck('category')->unique()->count();
        $avgDuration = $allServices->count() > 0 ? round($allServices->avg('duration_minutes')) : 45;
        $minDuration = $allServices->count() > 0 ? $allServices->min('duration_minutes') : 30;
        $maxDuration = $allServices->count() > 0 ? $allServices->max('duration_minutes') : 70;

        $stats = [
            'total' => $totalServices,
            'active_categories_count' => $activeCategoriesCount ?: 3,
            'active_count' => $activeCount,
            'inactive_count' => $inactiveCount,
            'avg_duration' => $avgDuration,
            'min_duration' => $minDuration,
            'max_duration' => $maxDuration,
            'last_sync' => now()->format('H.i'),
        ];

        // Hitungan per kategori untuk filter pills
        $categoryCounts = [
            'all' => $totalServices,
            Service::CAT_HAIRCUT => $allServices->where('category', Service::CAT_HAIRCUT)->count(),
            Service::CAT_BEARD => $allServices->where('category', Service::CAT_BEARD)->count(),
            Service::CAT_TREATMENT => $allServices->where('category', Service::CAT_TREATMENT)->count(),
        ];

        return view('pages.admin.services.index', compact(
            'services',
            'stats',
            'categoryCounts',
            'category',
            'status',
            'search'
        ));
    }

    /**
     * Tambah layanan baru.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'in:haircut_styling,beard_shaving,treatment_others'],
            'badge' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'show_in_widget' => ['nullable', 'boolean'],
        ]);

        $service = Service::create([
            'name' => $validated['name'],
            'category' => $validated['category'] ?? Service::CAT_HAIRCUT,
            'badge' => $validated['badge'] ?? null,
            'description' => $validated['description'] ?? '',
            'price' => $validated['price'],
            'duration_minutes' => $validated['duration_minutes'],
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
            'is_featured' => $request->has('is_featured') ? $request->boolean('is_featured') : false,
            'show_in_widget' => $request->has('show_in_widget') ? $request->boolean('show_in_widget') : true,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Layanan {$service->name} berhasil ditambahkan.",
                'data' => $service,
            ]);
        }

        return redirect()->route('admin.services.index')
            ->with('success', "Layanan {$service->name} berhasil ditambahkan.");
    }

    /**
     * Tampilkan form edit layanan.
     */
    public function edit(Service $service): View|JsonResponse
    {
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $service,
            ]);
        }

        return view('pages.admin.services.edit', compact('service'));
    }

    /**
     * Perbarui data layanan.
     */
    public function update(Request $request, Service $service): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'in:haircut_styling,beard_shaving,treatment_others'],
            'badge' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'show_in_widget' => ['nullable', 'boolean'],
        ]);

        $service->update([
            'name' => $validated['name'],
            'category' => $validated['category'] ?? $service->category,
            'badge' => $validated['badge'] ?? $service->badge,
            'description' => $validated['description'] ?? $service->description,
            'price' => $validated['price'],
            'duration_minutes' => $validated['duration_minutes'],
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : $service->is_active,
            'is_featured' => $request->has('is_featured') ? $request->boolean('is_featured') : $service->is_featured,
            'show_in_widget' => $request->has('show_in_widget') ? $request->boolean('show_in_widget') : $service->show_in_widget,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Layanan {$service->name} berhasil diperbarui.",
                'data' => $service->fresh(),
            ]);
        }

        return redirect()->route('admin.services.index')
            ->with('success', "Layanan {$service->name} berhasil diperbarui.");
    }

    /**
     * Hapus layanan.
     */
    public function destroy(Service $service): RedirectResponse|JsonResponse
    {
        $name = $service->name;
        $service->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Layanan {$name} berhasil dihapus.",
            ]);
        }

        return redirect()->route('admin.services.index')
            ->with('success', "Layanan {$name} berhasil dihapus.");
    }

    /**
     * Toggle status aktif layanan.
     */
    public function toggleActive(Service $service): RedirectResponse|JsonResponse
    {
        $service->update([
            'is_active' => ! $service->is_active,
        ]);

        $statusText = $service->is_active ? 'diaktifkan' : 'dinonaktifkan';

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => $service->is_active,
                'message' => "Layanan {$service->name} berhasil {$statusText}.",
            ]);
        }

        return back()->with('success', "Layanan {$service->name} berhasil {$statusText}.");
    }

    /**
     * Toggle status widget layanan.
     */
    public function toggleWidget(Service $service): RedirectResponse|JsonResponse
    {
        $service->update([
            'show_in_widget' => ! $service->show_in_widget,
        ]);

        $statusText = $service->show_in_widget ? 'ditampilkan di widget reservasi' : 'disembunyikan dari widget reservasi';

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'show_in_widget' => $service->show_in_widget,
                'message' => "Layanan {$service->name} sekarang {$statusText}.",
            ]);
        }

        return back()->with('success', "Status reservasi widget {$service->name} berhasil diperbarui.");
    }
}
