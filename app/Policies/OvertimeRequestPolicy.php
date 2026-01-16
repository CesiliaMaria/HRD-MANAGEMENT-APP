<?php

namespace App\Policies;

use App\Models\OvertimeRequest;
use App\Models\User;

class OvertimeRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Semua user bisa lihat (tapi filtered di controller)
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, OvertimeRequest $overtimeRequest): bool
    {
        // Admin bisa lihat semua, user biasa hanya miliknya
        return $user->role_id === 1 || $overtimeRequest->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Semua user bisa buat pengajuan
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, OvertimeRequest $overtimeRequest): bool
    {
        // Hanya bisa update jika masih pending dan milik sendiri
        return $overtimeRequest->user_id === $user->id && $overtimeRequest->status === 'pending';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, OvertimeRequest $overtimeRequest): bool
    {
        // Hanya bisa delete jika masih pending dan milik sendiri
        return $overtimeRequest->user_id === $user->id && $overtimeRequest->status === 'pending';
    }

    /**
     * Determine whether the user can approve/reject the model.
     */
    public function approve(User $user): bool
    {
        return $user->role_id === 1; // Hanya admin
    }
}
