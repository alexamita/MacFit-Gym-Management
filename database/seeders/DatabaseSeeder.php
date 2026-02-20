<?php
// DatabaseSeeder class for seeding the application's database with initial data for roles, categories, gyms, bundles, and equipment to set up the gym management system with predefined data for testing and development purposes, ensuring that the database is populated with relevant information for users to interact with when using the application
namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
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

        $this->call(RoleSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(GymSeeder::class);
        $this->call(BundleSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(EquipmentSeeder::class);
    }
}
