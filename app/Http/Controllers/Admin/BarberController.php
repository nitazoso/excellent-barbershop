<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BarberController extends Controller
{
    /**
     * Tambah data barber baru (Quick Action Dashboard).
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $barber = Barber::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? 'Barber Professional',
            'is_active' => $request->boolean('is_active', true),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Barber {$barber->name} berhasil ditambahkan.",
                'data' => $barber,
            ]);
        }

        return back()->with('success', "Barber {$barber->name} berhasil ditambahkan.");
    }
}
