<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /*
    * Only Admin and Gym Managers can see the full user directory.
    */
    public function viewAny(User $user, User $model)
    {
        return $user->role?->name === 'GYM_MANAGER'; // Admin handled by bypass
    }

    /*
    * Users view their own profile; Managers can view any profile.
    */
    public function view(User $user, User $model): bool
    {
        return $user->id === $model->id || $user->role?->name === 'GYM_MANAGER';
    }

    /*
     * Users update their own data. Managers can update others.
     */
    public function update(User $user, User $model): bool
    {
        return $user->id === $model->id || $user->role?->name === 'GYM_MANAGER';
    }

    /*
     * Self-deletion exceptions and management hierarchies.
     */
    public function delete(User $user, User $model): bool
    {
        // 1. Admin Logic (Handled when Gate::before returns null)
        if ($user->role?->name === 'ADMIN') {
            return $user->id !== $model->id; // Cannot delete self
        }
        // 2. Gym Manager Logic
        if ($user->role?->name === 'GYM_MANAGER') {
            // Can delete users, but not themselves or the Admin
            return $user->id !== $model->id && $model->role?->name !== 'ADMIN';
        }
        // 3. All other roles (Member, Staff, Trainer, User)
        // Can only delete their own account
        return $user->id === $model->id;
    }

    /*
     * Locked to Admins only.
     */
    public function changeRole(User $user, User $model): bool
    {
        return false; // Only Admin bypass will pass this
    }


    /*
    * Constructor method for the UserPolicy class, which can be used to initialize any necessary properties or dependencies for the policy
    */
    public function __construct()
    {
        //
    }
}
