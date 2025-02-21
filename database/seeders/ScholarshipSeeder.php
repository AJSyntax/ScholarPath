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
                    'Must maintain excellent academic standing',
                    'Leadership involvement required'
                ]),
                'discount_percentage' => 100,
                'status' => 'active',
                'type' => 'presidential'
            ],
            [
                'name' => 'Academic Excellence Scholarship',
                'description' => 'For students with exceptional academic performance.',
                'requirements' => json_encode([
                    'GPA of 90 or higher',
                    'No grade below 85',
                    'Good moral character',
                    'Active participation in academic activities'
                ]),
                'discount_percentage' => 75,
                'status' => 'active',
                'type' => 'academic'
            ],
            [
                'name' => 'CHED Merit Scholarship',
                'description' => 'Government-sponsored scholarship program for deserving students.',
                'requirements' => json_encode([
                    'Must pass CHED evaluation',
                    'Filipino citizen',
                    'Good academic standing',
                    'Family income within poverty threshold',
                    'Complete academic requirements'
                ]),
                'discount_percentage' => 50,
                'status' => 'active',
                'type' => 'ched'
            ],
        ];

        foreach ($scholarships as $scholarship) {
            Scholarship::create($scholarship);
        }
    }
}
