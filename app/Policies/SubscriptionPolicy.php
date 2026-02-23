<?php

namespace App\Policies;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SubscriptionPolicy
{
    /*
     * Staff, Managers, and Admins can see the master list.
     * Members/Users are handled via query filtering in the controller.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role->name, ['ADMIN', 'GYM_MANAGER', 'STAFF']);
    }

    /*
     * A user can view a subscription if they own it,
     * or if they are Staff/Management verifying the record.
     */
    public function view(User $user, Subscription $subscription): bool
    {
        return $user->id === $subscription->user_id || in_array($user->role->name, ['GYM_MANAGER', 'STAFF']);
    }

    /*
     * Any authenticated user (Member/User) can create a subscription for themselves.
     * Only allow members and users to create subscriptions for themselves
     */
    public function create(User $user): bool
    {
        return $user->role->name === 'MEMBER' || $user->role->name === 'USER';
    }

    /*
     * Only the owner (Member) or the Gym Manager/Admin can cancel a subscription.
     */
    public function delete(User $user, Subscription $subscription): bool
    {
        return $user->id === $subscription->user_id || in_array($user->role->name, ['GYM_MANAGER', 'ADMIN']);
    }
}
