<?php
// EquipmentSeeder class for seeding the equipment table with predefined gym equipment for each gym, linking them to existing gyms in the database, and ensuring that the equipment has realistic usage descriptions, model numbers, values, and statuses to create a comprehensive inventory of equipment for users to view and interact with in the gym management system
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Equipment;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Predefined equipment data for each gym with attributes
        $equipmentData = [
            // Gym 1
            [
                'name' => 'Power Rack',
                'usage' => 'Compound lifts: squat, bench, overhead press',
                'model_no' => 'IFF-PR-100',
                'value' => 185000.00,
                'status' => 'ACTIVE',
                'gym_id' => 1,
                'created_at' => '2026-02-18 16:46:02',
                'updated_at' => '2026-02-18 16:46:02',
            ],
            [
                'name' => 'Olympic Barbell 20kg',
                'usage' => 'Strength training and Olympic lifts',
                'model_no' => 'IFF-OB-20',
                'value' => 35000.00,
                'status' => 'ACTIVE',
                'gym_id' => 1,
                'created_at' => '2026-02-18 16:46:02',
                'updated_at' => '2026-02-18 16:46:02',
            ],
            [
                'name' => 'Adjustable Bench',
                'usage' => 'Incline/flat bench press and dumbbell work',
                'model_no' => 'IFF-AB-220',
                'value' => 42000.00,
                'status' => 'UNDER_MAINTENANCE',
                'gym_id' => 1,
                'created_at' => '2026-02-18 16:46:02',
                'updated_at' => '2026-02-18 16:46:02',
            ],

            // Gym 2
            [
                'name' => 'Assault Air Bike',
                'usage' => 'HIIT conditioning and intervals',
                'model_no' => 'PPC-AAB-01',
                'value' => 165000.00,
                'status' => 'ACTIVE',
                'gym_id' => 2,
                'created_at' => '2026-02-18 16:46:02',
                'updated_at' => '2026-02-18 16:46:02',
            ],
            [
                'name' => 'Concept2 RowErg',
                'usage' => 'Endurance training and rowing intervals',
                'model_no' => 'PPC-C2R-05',
                'value' => 155000.00,
                'status' => 'ACTIVE',
                'gym_id' => 2,
                'created_at' => '2026-02-18 16:46:02',
                'updated_at' => '2026-02-18 16:46:02',
            ],
            [
                'name' => 'Kettlebell Set (8–32kg)',
                'usage' => 'Functional training: swings, cleans, presses',
                'model_no' => 'PPC-KB-SET',
                'value' => 98000.00,
                'status' => 'ACTIVE',
                'gym_id' => 2,
                'created_at' => '2026-02-18 16:46:02',
                'updated_at' => '2026-02-18 16:46:02',
            ],

            // Gym 3
            [
                'name' => 'Yoga Mats (Premium)',
                'usage' => 'Yoga classes and floor exercises',
                'model_no' => 'ZWS-YM-50',
                'value' => 50000.00,
                'status' => 'ACTIVE',
                'gym_id' => 3,
                'created_at' => '2026-02-18 16:46:02',
                'updated_at' => '2026-02-18 16:46:02',
            ],
            [
                'name' => 'Pilates Reformer',
                'usage' => 'Pilates reformer classes',
                'model_no' => 'ZWS-PR-10',
                'value' => 650000.00,
                'status' => 'ACTIVE',
                'gym_id' => 3,
                'created_at' => '2026-02-18 16:46:02',
                'updated_at' => '2026-02-18 16:46:02',
            ],
            [
                'name' => 'Foam Roller Set',
                'usage' => 'Mobility, recovery, myofascial release',
                'model_no' => 'ZWS-FR-SET',
                'value' => 24000.00,
                'status' => 'ACTIVE',
                'gym_id' => 3,
                'created_at' => '2026-02-18 16:46:02',
                'updated_at' => '2026-02-18 16:46:02',
            ],

            // Gym 4
            [
                'name' => 'Treadmill (Commercial)',
                'usage' => 'Cardio training and fat loss',
                'model_no' => 'MFD-TM-3000',
                'value' => 420000.00,
                'status' => 'ACTIVE',
                'gym_id' => 4,
                'created_at' => '2026-02-18 16:46:02',
                'updated_at' => '2026-02-18 16:46:02',
            ],
            [
                'name' => 'Elliptical Trainer',
                'usage' => 'Low-impact cardio conditioning',
                'model_no' => 'MFD-ET-1200',
                'value' => 280000.00,
                'status' => 'FAULTY',
                'gym_id' => 4,
                'created_at' => '2026-02-18 16:46:02',
                'updated_at' => '2026-02-18 16:46:02',
            ],
            [
                'name' => 'Cable Crossover Machine',
                'usage' => 'Strength training: chest fly, rows, triceps',
                'model_no' => 'MFD-CC-900',
                'value' => 310000.00,
                'status' => 'ACTIVE',
                'gym_id' => 4,
                'created_at' => '2026-02-18 16:46:02',
                'updated_at' => '2026-02-18 16:46:02',
            ],

            // Gym 5
            [
                'name' => 'Leg Press Machine',
                'usage' => 'Lower-body strength development',
                'model_no' => 'ESCH-LP-700',
                'value' => 360000.00,
                'status' => 'ACTIVE',
                'gym_id' => 5,
                'created_at' => '2026-02-18 16:46:02',
                'updated_at' => '2026-02-18 16:46:02',
            ],
            [
                'name' => 'Physio Treatment Table',
                'usage' => 'Rehab assessment and physiotherapy sessions',
                'model_no' => 'ESCH-PT-20',
                'value' => 120000.00,
                'status' => 'ACTIVE',
                'gym_id' => 5,
                'created_at' => '2026-02-18 16:46:02',
                'updated_at' => '2026-02-18 16:46:02',
            ],
            [
                'name' => 'Resistance Bands Set',
                'usage' => 'Rehab, activation, mobility drills',
                'model_no' => 'ESCH-RB-SET',
                'value' => 18000.00,
                'status' => 'DECOMMISSIONED',
                'gym_id' => 5,
                'created_at' => '2026-02-18 16:46:02',
                'updated_at' => '2026-02-18 16:46:02',
            ],
        ];

        // Insert equipment data into the database
        foreach ($equipmentData as $equipment) {
            Equipment::create($equipment);
        }
    }
}
