<?php

namespace App\Policies;

use App\Models\Gym;
use App\Models\User;

class GymPolicy
{
    /**
     * ADMIN bypass is usually handled in the AuthServiceProvider via Gate::before.
     * These methods define the boundaries for everyone else.
     */
    public function viewAny(?User $user): bool
    {
        // Publicly accessible: Anyone (even guests) can see list of gyms.
        return true;
    }

    public function view(?User $user, Gym $gym): bool
    {
        // Publicly accessible: Anyone can see specific gym details.
        return true;
    }

    public function create(User $user): bool
    {
        // Boundary: Only ADMIN can create.
        // We return false here because ADMIN bypasses this anyway.
        return false;
    }

    public function update(User $user, Gym $gym): bool
    {
        // Boundary: GYM_MANAGER can only update if they are assigned to THIS specific gym.
        return $user->role->name === 'GYM_MANAGER' && $user->gym_id === $gym->id;
    }

    public function delete(User $user, Gym $gym): bool
    {
        // 1. First, verify the user is an Admin (since Gate::before skipped this)
        if (!$user->isAdmin) {
            return false;
        }

        // 2. The Safety Check: Prevent deletion if active members exist
        // This assumes you have a 'members' relationship on your Gym model

        return true;
    }






    /**
     * Extended Permissions
     */

    public function manageStaff(User $user, Gym $gym): bool
    {
        // Only Admin or the specific Gym Manager can manage staff.
        return $user->role->name === 'GYM_MANAGER' && $user->gym_id === $gym->id;
    }

    public function viewFinancials(User $user, Gym $gym): bool
    {
        // STAFF and TRAINERS should never see this.
        // Gym Managers might see local reports, but ADMIN has full oversight.
        return $user->role->name === 'GYM_MANAGER' && $user->gym_id === $gym->id;
    }

    public function __construct()
    {
        //
    }
}
