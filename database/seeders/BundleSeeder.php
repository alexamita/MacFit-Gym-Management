<?php
// BundleSeeder class for seeding the bundles table with predefined fitness bundles for each gym, linking them to existing categories and gyms in the database, and ensuring that the start times are randomly assigned from a static list of times to create realistic bundle offerings for users to subscribe to in the gym management system

namespace Database\Seeders;

use App\Models\Bundle;
use App\Models\Category;
use App\Models\Gym;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BundleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // 1. Get all existing gyms and categories from DB to link bundles correctly
        $gyms = Gym::all();
        $categories = Category::all();

        // Safety check: Ensure categories exist before trying to link them
        if ($gyms->isEmpty() || $categories->isEmpty()) {
            $this->command->error("Gyms or Categories table is empty. Please seed them first!");
            return;
        }
        // Static start times
        $staticTimes = [
            '06:00', // Early Bird
            '08:30', // Morning Flow
            '12:00', // Lunch Blast
            '17:30', // After Work HIIT
            '19:00', // Evening Strength
            '20:30'  // Night Owl
        ];

        // 2. Define bundles with category names instead of IDs
        $bundles = [
            [
                'category' => 'Strength Training',
                'name' => 'Basic Powerlifting',
                'location' => 'Heavy Weight Zone',
                'session_duration' => 2,
                'description' => '2-hour daily access to heavy racks.'
            ],
            [
                'category' => 'Strength Training',
                'name' => 'PT 1-Hour Session',
                'location' => 'PT Clinic',
                'session_duration' => 1,
                'description' => 'One hour private coaching session.'
            ],
            [
                'category' => 'Cardio & Endurance',
                'name' => 'Endurance Runner Pass',
                'location' => 'Cardio Floor',
                'session_duration' => 3,
                'description' => '3-hour daily access to treadmill and HIIT zone.'
            ],
            [
                'category' => 'Yoga & Mobility',
                'name' => 'Zen Morning Flow',
                'location' => 'Zen Studio',
                'session_duration' => 1,
                'description' => '1-hour daily morning yoga.'
            ],
            [
                'category' => 'Yoga & Mobility',
                'name' => 'Extended Workshop',
                'location' => 'Zen Studio',
                'session_duration' => 4,
                'description' => 'Deep dive mobility workshop.'
            ],
            [
                'category' => 'HIIT & Circuit',
                'name' => '30-Day Shred Session',
                'location' => 'Functional Area',
                'session_duration' => 1,
                'description' => '1-hour high-intensity circuit.'
            ],
            [
                'category' => 'CrossFit & Functional',
                'name' => 'WOD Class',
                'location' => 'The Box',
                'session_duration' => 1,
                'description' => '1-hour CrossFit group workout.'
            ],
            [
                'category' => 'Combat Sports',
                'name' => 'Boxing Fundamentals',
                'location' => 'Combat Zone',
                'session_duration' => 2,
                'description' => '2-hour technique and sparring session.'
            ],
            [
                'category' => 'Recovery & Wellness',
                'name' => 'Spa & Relaxation',
                'location' => 'Wellness Center',
                'session_duration' => 2,
                'description' => '2-hour recovery zone access.'
            ],
            [
                'category' => 'Senior Fitness',
                'name' => 'Silver Strength',
                'location' => 'Studio B',
                'session_duration' => 1,
                'description' => '1-hour gentle movement session.'
            ],
        ];

        // 3. Loop through gyms and sample bundles data to seed
        foreach ($gyms as $gym) {
            foreach ($bundles as $bundle) {
                // Find the existing category ID by matching the name
                $category = $categories->where('name', $bundle['category'])->first();

                if ($category) {
                    // Pick a random time from our static list
                    $randomTime = $staticTimes[array_rand($staticTimes)];
                    Bundle::updateOrCreate(
                        [
                            'name' => $bundle['name'],
                            'gym_id' => $gym->id // Ensure uniqueness per gym and bundle name
                        ],
                        [
                            // Follows Controller logic for formatting and validation
                            'location'         => "{$gym->name}-{$bundle['location']}",
                            'start_time'       => Carbon::parse($randomTime),
                            'session_duration' => $bundle['session_duration'],
                            'description'      => $bundle['description'],
                            'category_id'      => $category->id, // Real ID from categories table
                            'gym_id'           => $gym->id,      // Real ID from gyms table
                        ]
                    );
                }
            }
        }
    }
}
