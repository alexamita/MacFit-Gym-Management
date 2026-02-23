<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
{
    /**
     * viewAny & view:
     * All roles can view categories to see what types of workouts are available.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Category $category): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        // Only Admin (via bypass) and Gym Managers can create categories.
        return $user->role->name === 'GYM_MANAGER';
    }

    public function update(User $user, Category $category): bool
    {
      // We allow Gym Managers and Staff to edit category descriptions (e.g., updating a room location).
        return in_array($user->role->name, ['GYM_MANAGER', 'STAFF']);
    }

    public function delete(User $user, Category $category): bool
    {
        // Deleting a category can break multiple Bundles. Restricted strictly to the Admin (handled by bypass).
        return false;
    }
}
