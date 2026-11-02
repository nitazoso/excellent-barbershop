<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BarbershopSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Update pengaturan studio / jam operasional (Quick Action Dashboard).
     */
    public function update(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'opening_time' => ['required', 'string'],
            'closing_time' => ['required', 'string'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $setting = BarbershopSetting::query()->first();
        if (! $setting) {
            $setting = new BarbershopSetting;
        }

        $setting->fill([
            'name' => $validated['name'] ?? ($setting->name ?: 'Excellent Barbershop'),
            'opening_time' => $validated['opening_time'],
            'closing_time' => $validated['closing_time'],
            'phone' => $validated['phone'] ?? $setting->phone,
            'address' => $validated['address'] ?? $setting->address,
        ]);
        $setting->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pengaturan jam operasional studio berhasil disimpan.',
                'data' => $setting,
            ]);
        }

        return back()->with('success', 'Pengaturan jam operasional studio berhasil disimpan.');
    }
}
