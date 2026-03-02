<?php

namespace App\Providers;

use App\Models\Bundle;
use App\Models\Category;
use App\Models\Equipment;
use App\Models\Gym;
use App\Models\Role;
use App\Models\Subscription;
use App\Models\User;
use App\Policies\BundlePolicy;
use App\Policies\CategoryPolicy;
use App\Policies\EquipmentPolicy;
use App\Policies\GymPolicy;
use App\Policies\RolePolicy;
use App\Policies\SubscriptionPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    protected $policies = [
        // Register your model-policy mappings here
        User::class => UserPolicy::class,
        Category::class => CategoryPolicy::class,
        Bundle::class => BundlePolicy::class,
        Equipment::class => EquipmentPolicy::class,
        Gym::class => GymPolicy::class,
        Role::class => RolePolicy::class,
        Subscription::class => SubscriptionPolicy::class,
    ];

    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
         // Ensure that policies are registered during application bootstrapping
        $this->registerPolicies();
    }
}
