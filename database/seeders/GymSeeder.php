<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gym;

class GymSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gyms = [
            [
                'name' => 'Iron Forge Fitness',
                'location' => 'Westlands, Nairobi',
                'phone_number' => '+254 712 345 678',
                'description' => 'Full-service strength and conditioning gym with modern equipment and certified trainers.'
            ],
            [
                'name' => 'Pulse Performance Center',
                'location' => 'Kilimani, Nairobi',
                'phone_number' => '+254 723 456 789',
                'description' => 'High-performance training facility focused on HIIT, CrossFit, and endurance conditioning.'
            ],
            [
                'name' => 'ZenCore Wellness Studio',
                'location' => 'Lavington, Nairobi',
                'phone_number' => '+254 734 567 890',
                'description' => 'Yoga, Pilates, and mobility-focused wellness studio promoting holistic fitness.'
            ],
            [
                'name' => 'MetroFit Downtown',
                'location' => 'CBD, Nairobi',
                'phone_number' => '+254 745 678 901',
                'description' => '24/7 access gym offering cardio, strength training, and personal coaching.',
            ],
            [
                'name' => 'Elite Sports Conditioning Hub',
                'location' => 'Karen, Nairobi',
                'phone_number' => '+254 756 789 012',
                'description' => 'Athlete development center specializing in sports performance and rehabilitation.'
            ],
        ];

        // Loop through gyms and seed the database, ensuring no duplicates based on name
        foreach ($gyms as $gym) {
            Gym::updateOrCreate(
                ['name' => $gym['name']], // prevents duplicate insert
                $gym
            );
        }

        $this->command->info('Gym seeded successfully.');
    }
}
