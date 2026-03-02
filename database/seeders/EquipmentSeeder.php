<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Equipment;

class EquipmentSeeder extends Seeder
{
    /**
     * Seed equipment inventory across multiple gyms.
     * Uses updateOrCreate so the seeder can be re-run safely.
     */
    public function run(): void
    {
        $equipmentData = [
            // Gym 1
            [
                'name' => 'Power Rack',
                'usage_notes' => 'Compound lifts: squat, bench, overhead press',
                'manufacturer_serial_no' => 'IFF-PR-100',
                'asset_code' => 'GYM1-PR-01',
                'value' => 185000.00,
                'status' => Equipment::STATUS_ACTIVE,
                'gym_id' => 1,
                'category_id' => 1,
            ],
            [
                'name' => 'Olympic Barbell 20kg',
                'usage_notes' => 'Strength training and Olympic lifts',
                'manufacturer_serial_no' => 'IFF-OB-20',
                'asset_code' => 'GYM1-OB-01',
                'value' => 35000.00,
                'status' => Equipment::STATUS_ACTIVE,
                'gym_id' => 1,
                'category_id' => 1,
            ],
            [
                'name' => 'Adjustable Bench',
                'usage_notes' => 'Incline/flat bench press and dumbbell work',
                'manufacturer_serial_no' => 'IFF-AB-220',
                'asset_code' => 'GYM1-AB-01',
                'value' => 42000.00,
                'status' => Equipment::STATUS_UNDER_MAINTENANCE,
                'gym_id' => 1,
                'category_id' => 1,
            ],

            // Gym 2
            [
                'name' => 'Assault Air Bike',
                'usage_notes' => 'HIIT conditioning and intervals',
                'manufacturer_serial_no' => 'PPC-AAB-01',
                'asset_code' => 'GYM2-AAB-01',
                'value' => 165000.00,
                'status' => Equipment::STATUS_ACTIVE,
                'gym_id' => 2,
                'category_id' => 3,
            ],
            [
                'name' => 'Concept2 RowErg',
                'usage_notes' => 'Endurance training and rowing intervals',
                'manufacturer_serial_no' => 'PPC-C2R-05',
                'asset_code' => 'GYM2-C2R-01',
                'value' => 155000.00,
                'status' => Equipment::STATUS_ACTIVE,
                'gym_id' => 2,
                'category_id' => 2,
            ],
            [
                'name' => 'Kettlebell Set (8–32kg)',
                'usage_notes' => 'Functional training: swings, cleans, presses',
                'manufacturer_serial_no' => 'PPC-KB-SET',
                'asset_code' => 'GYM2-KB-01',
                'value' => 98000.00,
                'status' => Equipment::STATUS_ACTIVE,
                'gym_id' => 2,
                'category_id' => 5,
            ],

            // Gym 3
            [
                'name' => 'Yoga Mats (Premium)',
                'usage_notes' => 'Yoga classes and floor exercises',
                'manufacturer_serial_no' => 'ZWS-YM-50',
                'asset_code' => 'GYM3-YM-01',
                'value' => 50000.00,
                'status' => Equipment::STATUS_ACTIVE,
                'gym_id' => 3,
                'category_id' => 4,
            ],
            [
                'name' => 'Pilates Reformer',
                'usage_notes' => 'Pilates reformer classes',
                'manufacturer_serial_no' => 'ZWS-PR-10',
                'asset_code' => 'GYM3-PR-01',
                'value' => 650000.00,
                'status' => Equipment::STATUS_ACTIVE,
                'gym_id' => 3,
                'category_id' => 9,
            ],
            [
                'name' => 'Foam Roller Set',
                'usage_notes' => 'Mobility, recovery, myofascial release',
                'manufacturer_serial_no' => 'ZWS-FR-SET',
                'asset_code' => 'GYM3-FR-01',
                'value' => 24000.00,
                'status' => Equipment::STATUS_ACTIVE,
                'gym_id' => 3,
                'category_id' => 12,
            ],

            // Gym 4
            [
                'name' => 'Treadmill (Commercial)',
                'usage_notes' => 'Cardio training and fat loss',
                'manufacturer_serial_no' => 'MFD-TM-3000',
                'asset_code' => 'GYM4-TM-01',
                'value' => 420000.00,
                'status' => Equipment::STATUS_ACTIVE,
                'gym_id' => 4,
                'category_id' => 2,
            ],
            [
                'name' => 'Elliptical Trainer',
                'usage_notes' => 'Low-impact cardio conditioning',
                'manufacturer_serial_no' => 'MFD-ET-1200',
                'asset_code' => 'GYM4-ET-01',
                'value' => 280000.00,
                'status' => Equipment::STATUS_FAULTY,
                'gym_id' => 4,
                'category_id' => 2,
            ],
            [
                'name' => 'Cable Crossover Machine',
                'usage_notes' => 'Strength training: chest fly, rows, triceps',
                'manufacturer_serial_no' => 'MFD-CC-900',
                'asset_code' => 'GYM4-CC-01',
                'value' => 310000.00,
                'status' => Equipment::STATUS_ACTIVE,
                'gym_id' => 4,
                'category_id' => 1,
            ],

            // Gym 5
            [
                'name' => 'Leg Press Machine',
                'usage_notes' => 'Lower-body strength development',
                'manufacturer_serial_no' => 'ESCH-LP-700',
                'asset_code' => 'GYM5-LP-01',
                'value' => 360000.00,
                'status' => Equipment::STATUS_ACTIVE,
                'gym_id' => 5,
                'category_id' => 1,
            ],
            [
                'name' => 'Physio Treatment Table',
                'usage_notes' => 'Rehab assessment and physiotherapy sessions',
                'manufacturer_serial_no' => 'ESCH-PT-20',
                'asset_code' => 'GYM5-PT-01',
                'value' => 120000.00,
                'status' => Equipment::STATUS_ACTIVE,
                'gym_id' => 5,
                'category_id' => 12,
            ],
            [
                'name' => 'Resistance Bands Set',
                'usage_notes' => 'Rehab, activation, mobility drills',
                'manufacturer_serial_no' => 'ESCH-RB-SET',
                'asset_code' => 'GYM5-RB-01',
                'value' => 18000.00,
                'status' => Equipment::STATUS_DECOMMISSIONED,
                'gym_id' => 5,
                'category_id' => 4,
            ],
        ];

        foreach ($equipmentData as $equipment) {
            Equipment::updateOrCreate(
                // Unique identifier for equipment record (globally unique)
                ['manufacturer_serial_no' => $equipment['manufacturer_serial_no']],

                // Fields to insert/update
                $equipment
            );
        }

        $this->command->info('Equipment seeded successfully.');
    }
}
