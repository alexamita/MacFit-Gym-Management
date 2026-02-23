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
            // We want to allow admins to do everything EXCEPT delete users, which is a very dangerous action that should be reserved for super admins or done manually in the database.
            if ($ability === 'delete') {
            return null;
        }
            // If the user is an admin, allow them to do everything else.
            if ($user->isAdmin) {
                return true;
            }
        });
    }
}
