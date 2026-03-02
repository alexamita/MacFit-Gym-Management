<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // This check runs BEFORE every other policy check
        Gate::before(function (User $user, string $ability) {
          // 1. Safety Brake: Prevent accidental deletions
        // This forces the app to look at the specific Policy for 'delete' logic.
            if ($ability === 'delete') {
            return null;}

            // 2. The Golden Key: Admins get a pass for everything else
            if ($user->isAdmin) {
                return true;
            }
        // 3. Fallthrough: Returning null allows regular Policy checks to run for other roles
        return null;
        });
    }
}
