<?php

namespace App\Policies;

use App\Models\Gym;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GymPolicy
{
    /**
     * viewAny & view:
     * Everyone can see gym locations and details.
     */
    public function viewAny(? User $user): bool
    {
        return true;
    }

    public function view(User $user, Gym $gym): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        // Only the Admin (Role 1) should create new gym entities. Handled by Gate::before bypass.
        return false;
    }

    public function update(User $user, Gym $gym): bool
    {
        // Only Admin (bypass) and Gym Managers (Role 2) can edit gym details.
        return $user->role->name === 'GYM_MANAGER';
    }

    public function delete(User $user, Gym $gym): bool
    {
        // Strictly restricted to the Admin (bypass).
        return false;
    }
}
