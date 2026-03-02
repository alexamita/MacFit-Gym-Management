<?php

namespace Database\Seeders;

use App\Models\Gym;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $roles = [
    [
        'name' => 'Administrator',
        'slug' => 'admin',
        'description' => 'Administrator with full access to the system.'
    ],
    [
        'name' => 'Gym Manager',
        'slug' => 'gym_manager',
        'description' => 'Manages a specific gym including equipment, bundles, and staff.'
    ],
    [
        'name' => 'Trainer',
        'slug' => 'trainer',
        'description' => 'Conducts training sessions and manages assigned bundles.'
    ],
    [
        'name' => 'Staff',
        'slug' => 'staff',
        'description' => 'Front desk and operational staff with limited system access.'
    ],
    [
        'name' => 'User',
        'slug' => 'user',
        'description' => 'Regular user with basic access.'
    ],
        ];

    foreach ($roles as $role) {
        Role::updateOrCreate(
        ['slug' => $role['slug']], // identity
        [
            'name' => $role['name'],
            'description' => $role['description'],
        ]);
    }
    }
}
