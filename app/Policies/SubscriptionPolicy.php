<?php

namespace App\Policies;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SubscriptionPolicy
{
    /**
     * admin, gym_manager, staff and owner can view.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role?->slug, ['admin', 'gym_manager', 'staff'], true);
    }

    /**
     * admin, gym_manager, staff and owner can view.
     */
    public function view(User $user, Subscription $subscription): bool
    {
        return (int) $user->id === (int) $subscription->user_id
            || in_array($user->role?->slug, ['admin', 'gym_manager', 'staff'], true);
    }

    /**
     * Any authenticated user can create their own subscription.
     * (Still enforced user_id in controller.)
     */
    public function create(User $user): bool
    {
        // return true;
        return $user->role?->slug === 'user';
    }

    /**
     * Only the owner can delete/cancel.
     */
    public function delete(User $user, Subscription $subscription): bool
    {
        return (int) $user->id === (int) $subscription->user_id;
    }

    public function __construct()
    {
        //
    }
}
