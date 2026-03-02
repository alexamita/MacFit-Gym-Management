<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory()->create([
        //     'name' => 'Admin User',
        //     'email' => 'admin@example.com',
        //     'role_id' => 1,
        // ]);

        $this->call([
            GymSeeder::class,
            RoleSeeder::class,
            CategorySeeder::class,
            BundleSeeder::class,
            EquipmentSeeder::class ]);
    }

}
