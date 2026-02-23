<?php

namespace App\Policies;

use App\Models\Bundle;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BundlePolicy
{
    /**
     * viewAny & view:
     * Everyone can see what bundles/sessions are available to join.
     */

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Bundle $bundle): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        // Only Admin (bypass) and Gym Managers can create new bundles.
        return $user->role->name === 'GYM_MANAGER';
    }

    public function update(User $user, Bundle $bundle): bool
    {
        // Managers can update any bundle.Trainers can update bundles (e.g., changing session details).
        return in_array($user->role->name, ['GYM_MANAGER', 'TRAINER']);
    }

    public function delete(User $user, Bundle $bundle): bool
    {
        // Restricted to Managers and Admins.
        return $user->role->name === 'GYM_MANAGER';
    }
}
