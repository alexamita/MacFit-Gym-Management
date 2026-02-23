<?php

namespace App\Policies;

use App\Models\Equipment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EquipmentPolicy
{
    /**
     * viewAny & view:
     * Everyone (Members, Users, Staff, etc.) can see what equipment the gym has.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Equipment $equipment): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        // Only Gym Managers can add new machines to the system.
        return $user->role->name === 'GYM_MANAGER';
    }

    public function update(User $user, Equipment $equipment): bool
    {
        // Managers, Trainers, and Staff can update status (e.g., marking a treadmill as "Out of Order").
        return in_array($user->role->name, ['GYM_MANAGER', 'STAFF', 'TRAINER']);
    }

    public function delete(User $user, Equipment $equipment): bool
    {
        // Only the Admin (via bypass) or the Gym Manager can remove equipment records.
        return $user->role->name === 'GYM_MANAGER';
    }
}
