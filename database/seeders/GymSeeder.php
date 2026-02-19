<?php
// GymSeeder class for seeding the gyms table with predefined gym information, ensuring that each gym has a unique name, longitude, latitude, and description to provide meaningful locations for users to view and interact with in the gym management system
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                'longitude' => 36.821945,
                'latitude' => -1.292066,
                'description' => 'Full-service strength and conditioning gym with modern equipment and certified trainers.',
            ],
            [
                'name' => 'Pulse Performance Center',
                'longitude' => 36.817223,
                'latitude' => -1.286389,
                'description' => 'High-performance training facility focused on HIIT, CrossFit, and endurance conditioning.',
            ],
            [
                'name' => 'ZenCore Wellness Studio',
                'longitude' => 36.805378,
                'latitude' => -1.300512,
                'description' => 'Yoga, Pilates, and mobility-focused wellness studio promoting holistic fitness.',
            ],
            [
                'name' => 'MetroFit Downtown',
                'longitude' => 36.816670,
                'latitude' => -1.283330,
                'description' => '24/7 access gym offering cardio, strength training, and personal coaching.',
            ],
            [
                'name' => 'Elite Sports Conditioning Hub',
                'longitude' => 36.845210,
                'latitude' => -1.312145,
                'description' => 'Athlete development center specializing in sports performance and rehabilitation.',
            ],
        ];

        // Loop through gyms and seed the database, ensuring no duplicates based on name
        foreach ($gyms as $gym) {
            Gym::updateOrCreate(
                ['name' => $gym['name']], // prevents duplicate insert
                $gym
            );
        }
    }
}
