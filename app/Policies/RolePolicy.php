<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;


class RolePolicy
{
    /**
     * viewAny, view:
     * Only Admin can see the list of available system roles. Handled by Admin bypass in AuthServiceProvider
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, Role $role): bool
    {
        return false;
    }

    /**
     * create, update, delete:
     * These are hard-locked. Roles are typically seeded via database migrations.
     * Even an Admin should rarely change these via an API for system stability.
     */
    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Role $role): bool
    {
        return false;
    }

    public function delete(User $user, Role $role): bool
    {
        return false;
    }

    public function __construct()
    {
        //
    }
}
