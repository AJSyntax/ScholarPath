<?php

namespace Database\Seeders;

use App\Models\Scholarship;
use Illuminate\Database\Seeder;

class ScholarshipSeeder extends Seeder
{
    public function run(): void
    {
        $scholarships = [
            [
                'name' => 'Presidential Scholarship',
                'description' => 'Full scholarship with monthly allowance for top performing students.',
                'requirements' => json_encode([
                    'Minimum GPA of 95%',
                    'No grade below 90%',
                    'Must be in top 10 of entrance exam',
                ]),
                'discount_percentage' => 100,
                'status' => 'active',
                'type' => 'presidential',
            ],
            [
                'name' => 'Academic Scholarship - Category 1',
                'description' => 'For students with exceptional academic performance.',
                'requirements' => json_encode([
                    'GPA between 98-100',
                    'Good moral character',
                ]),
                'discount_percentage' => 75,
                'status' => 'active',
                'type' => 'academic',
            ],
            [
                'name' => 'CHED Scholarship',
                'description' => 'Government-sponsored scholarship program.',
                'requirements' => json_encode([
                    'Must pass CHED evaluation',
                    'Filipino citizen',
                    'Good academic standing',
                ]),
                'discount_percentage' => 50,
                'status' => 'active',
                'type' => 'ched',
            ],
        ];

        foreach ($scholarships as $scholarship) {
            Scholarship::create($scholarship);
        }
    }
}
