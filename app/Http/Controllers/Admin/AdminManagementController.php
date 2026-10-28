<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAdminRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdminManagementController extends Controller
{
    /**
     * Display a listing of the administrators.
     */
    public function index(): View
    {
        Gate::authorize('viewAny', User::class);

        $admins = User::query()
            ->whereNotNull('role')
            ->with('creator')
            ->orderByRaw("CASE WHEN role = 'owner' THEN 1 ELSE 2 END")
            ->orderByDesc('created_at')
            ->get();

        return view('pages.admin.admins.index', compact('admins'));
    }

    /**
     * Store a newly created administrator in storage.
     */
    public function store(StoreAdminRequest $request): RedirectResponse
    {
        $email = strtolower(trim($request->validated('email')));

        User::create([
            'email' => $email,
            'role' => 'admin',
            'is_active' => true,
            'created_by' => $request->user()->id,
        ]);

        return redirect()
            ->route('admin.admins.index')
            ->with('success', "Admin baru dengan email {$email} berhasil ditambahkan.");
    }

    /**
     * Toggle the active status of the specified administrator.
     */
    public function toggleStatus(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('toggleStatus', $user);

        $user->is_active = ! $user->is_active;
        $user->save();

        $statusLabel = $user->is_active ? 'diaktifkan kembali' : 'dinonaktifkan';

        return redirect()
            ->route('admin.admins.index')
            ->with('success', "Akun admin {$user->email} berhasil {$statusLabel}.");
    }
}
