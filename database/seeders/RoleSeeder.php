<?php
// RoleSeeder class for seeding the roles table with predefined user roles for the gym management system, ensuring that each role has a unique name and description to define the permissions and access levels for different types of users in the application
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'ADMIN',
                'description' => 'Full system access. Manages gyms, users, equipment, and settings.'
            ],
            [
                'name' => 'GYM_MANAGER',
                'description' => 'Manages a specific gym including equipment, bundles, and staff.'
            ],
            [
                'name' => 'TRAINER',
                'description' => 'Conducts training sessions and manages assigned bundles.'
            ],
            [
                'name' => 'STAFF',
                'description' => 'Front desk and operational staff with limited system access.'
            ],
            [
                'name' => 'MEMBER',
                'description' => 'Gym member who can subscribe to bundles and manage their profile.'
            ],
            [
                'name' => 'USER',
                'description' => 'User Role.'
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']], // prevents duplicates
                $role
            );
        }
    }
}
