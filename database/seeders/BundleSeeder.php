<?php

namespace Database\Seeders;

use App\Models\Bundle;
use App\Models\Category;
use App\Models\Gym;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BundleSeeder extends Seeder
{
    public function run(): void
    {
        $gyms = Gym::all();
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $this->command->error('Seed categories first!');
            return;
        }

        $times = ['06:00:00', '08:30:00', '12:00:00', '17:30:00', '19:00:00'];

        // Global bundles (gym_id = NULL)
        $globalBundles = [
            [
                'category' => 'Yoga & Mobility',
                'name' => 'Global: Sunrise Yoga Flow',
                'gym_zone' => 'Studio',
                'start_time' => $this->randomTime($times),
                'session_duration' => 60,
                'price' => 800.00,
                'currency' => 'KES',
                'description' => 'A 60-minute guided yoga flow session.',
            ],
            [
                'category' => 'HIIT & Circuit',
                'name' => 'Global: HIIT Express',
                'gym_zone' => 'Functional Area',
                'start_time' => $this->randomTime($times),
                'session_duration' => 45,
                'price' => 900.00,
                'currency' => 'KES',
                'description' => 'High-intensity circuit session.',
            ],
        ];

        // Gym-specific bundles
        $gymBundles = [
            [
                'category' => 'Strength Training',
                'name' => 'PT 60-Minute Session',
                'gym_zone' => 'PT Corner',
                'start_time' => $this->randomTime($times),
                'session_duration' => 60,
                'price' => 3500.00,
                'currency' => 'KES',
                'description' => 'One-on-one personal training session.',
            ],
            [
                'category' => 'Yoga & Mobility',
                'name' => 'Yoga Pack (10 Classes)',
                'gym_zone' => 'Studio A',
                'start_time' => $this->randomTime($times),
                'session_duration' => 60,
                'price' => 7000.00,
                'currency' => 'KES',
                'description' => '10 flexible yoga sessions.',
            ],
        ];

        // Seed global bundles once
        foreach ($globalBundles as $data) {
            $category = $this->findCategory($categories, $data['category']);
            if (!$category) {
                $this->command->warn("Missing category: {$data['category']}");
                continue;
            }

            $slug = Str::slug($data['name']);

            Bundle::updateOrCreate(
                // global key
                ['gym_id' => null, 'slug' => $slug],
                [
                    'category_id' => $category->id,
                    'name' => $data['name'],
                    'slug' => $slug,
                    'description' => $data['description'],
                    'gym_zone' => $data['gym_zone'],
                    'start_time' => $data['start_time'],
                    'session_duration' => $data['session_duration'],
                    'price' => $data['price'],
                    'currency' => $data['currency'],
                ]
            );
        }

        if ($gyms->isEmpty()) {
            $this->command->warn('No gyms found. Only global bundles were created.');
            return;
        }

        // Seed gym bundles for each gym
        foreach ($gyms as $gym) {
            foreach ($gymBundles as $data) {
                $category = $this->findCategory($categories, $data['category']);
                if (!$category) {
                    $this->command->warn("Missing category: {$data['category']}");
                    continue;
                }

                $slug = Str::slug($data['name']);

                Bundle::updateOrCreate(
                    ['gym_id' => $gym->id, 'slug' => $slug],
                    [
                        'category_id' => $category->id,
                        'name' => $data['name'],
                        'slug' => $slug,
                        'description' => $data['description'],
                        'gym_zone' => $data['gym_zone'],
                        'start_time' => $data['start_time'],
                        'session_duration' => $data['session_duration'],
                        'price' => $data['price'],
                        'currency' => $data['currency'],
                    ]
                );
            }
        }

        $this->command->info('Bundles seeded successfully! (Global bundles have gym_id = NULL)');
    }

    private function findCategory($categories, string $name): ?Category
    {
        return $categories->firstWhere('name', $name);
    }

    private function randomTime(array $times): string
    {
        return $times[array_rand($times)];
    }
}
