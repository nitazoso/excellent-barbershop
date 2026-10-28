<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class AdminPolicy
{
    /**
     * Determine whether the user can view the admin list.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['owner', 'admin'], true) && $user->is_active;
    }

    /**
     * Determine whether the user can create an admin.
     */
    public function create(User $user): Response
    {
        if ($user->role !== 'owner' || ! $user->is_active) {
            return Response::deny('Hanya owner yang berhak menambah admin baru.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can toggle the active status of the target admin.
     */
    public function toggleStatus(User $user, User $target): Response
    {
        if ($user->role !== 'owner' || ! $user->is_active) {
            return Response::deny('Hanya owner yang berhak mengubah status admin.');
        }

        if ($target->id === $user->id) {
            return Response::deny('Admin tidak boleh menonaktifkan akunnya sendiri.');
        }

        if ($target->role === 'owner') {
            return Response::deny('Akun owner tidak dapat dinonaktifkan.');
        }

        // When deactivating, ensure the system never has 0 active admins/owners
        if ($target->is_active) {
            $activeAdminCount = User::query()
                ->whereNotNull('role')
                ->where('is_active', true)
                ->count();

            if ($activeAdminCount <= 1) {
                return Response::deny('Sistem tidak boleh berada pada kondisi tanpa admin aktif.');
            }
        }

        return Response::allow();
    }
}
