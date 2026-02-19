<?php
// CategorySeeder class for seeding the categories table with predefined fitness categories for organizing and filtering bundles in the gym management system, ensuring that each category has a unique name and description to provide meaningful classifications for users when browsing available bundles
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Gym;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Strength Training',
                'description' => 'Focus on heavy lifting, powerlifting, and muscle hypertrophy.',
            ],
            [
                'name' => 'Cardio & Endurance',
                'description' => 'Running, cycling, and rowing programs for heart health.',
            ],
            [
                'name' => 'HIIT & Circuit',
                'description' => 'High-intensity interval training and metabolic conditioning.',
            ],
            [
                'name' => 'Yoga & Mobility',
                'description' => 'Flexibility, flow, and restorative movement sessions.',
            ],
            [
                'name' => 'CrossFit & Functional',
                'description' => 'Varied functional movements performed at high intensity.',
            ],
            [
                'name' => 'Combat Sports',
                'description' => 'Boxing, Kickboxing, Muay Thai, and MMA-style fitness.',
            ],
            [
                'name' => 'Personal Training',
                'description' => 'Exclusive 1-on-1 coaching and customized programming.',
            ],
            [
                'name' => 'Aquatics',
                'description' => 'Swimming laps, water aerobics, and pool-based therapy.',
            ],
            [
                'name' => 'Pilates & Core',
                'description' => 'Reformer and mat-based classes focusing on stability.',
            ],
            [
                'name' => 'Senior Fitness',
                'description' => 'Low-impact movement designed for longevity and joint health.',
            ],
            [
                'name' => 'Youth Athletics',
                'description' => 'Training programs for students and young athletes.',
            ],
            [
                'name' => 'Recovery & Wellness',
                'description' => 'Access to saunas, cold plunges, and massage therapy.',
            ],
            [
                'name' => 'Nutrition Coaching',
                'description' => 'Meal planning, macro tracking, and dietary consultations.',
            ],
            [
                'name' => 'Dance Fitness',
                'description' => 'High-energy classes like Zumba, Barre, or Hip-Hop Fit.',
            ],
            [
                'name' => 'Open Gym Access',
                'description' => 'General facility entry for non-class, self-guided workouts.',
            ],
        ];

        // Loop through categories and seed the database, ensuring no duplicates based on name
        foreach ($categories as $category) {
            Category::firstOrCreate([
                'name' => $category['name'],
                'description' => $category['description'],
            ]);
        }
    }
}
