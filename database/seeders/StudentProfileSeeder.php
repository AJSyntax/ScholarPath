<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\StudentProfile;
use Illuminate\Database\Seeder;

class StudentProfileSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            'Bachelor of Science in Computer Science',
            'Bachelor of Science in Information Technology',
            'Bachelor of Science in Business Administration',
            'Bachelor of Arts in Communication',
        ];

        $students = User::where('role', 'student')->get();

        foreach ($students as $index => $student) {
            StudentProfile::create([
                'user_id' => $student->id,
                'student_number' => '2024' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'name' => $student->name,
                'course' => $courses[array_rand($courses)],
                'year_level' => rand(1, 4),
                'contact_number' => '+63' . rand(900, 999) . rand(100, 999) . rand(1000, 9999),
                'address' => fake()->address(),
                'birth_date' => fake()->dateTimeBetween('-25 years', '-18 years'),
                'current_gpa' => rand(75, 99) + (rand(0, 99) / 100),
                'least_grade' => rand(75, 85) + (rand(0, 99) / 100),
            ]);
        }
    }
}
