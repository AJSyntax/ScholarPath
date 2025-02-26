<?php

namespace Database\Seeders;

use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class StudentProfileSeeder extends Seeder
{
    public function run(): void
    {
        $scholarshipTypes = ['presidential', 'academic', 'ched'];
        $statuses = ['new', 'maintained', 'terminated'];
        
        // Get all users with role 'student'
        $students = User::where('role', 'student')->get();
        
        foreach ($students as $index => $student) {
            // Rotate through scholarship types and statuses
            $scholarshipType = $scholarshipTypes[$index % count($scholarshipTypes)];
            $status = $statuses[$index % count($statuses)];
            
            // Calculate GPA and least grade based on status
            $currentGpa = $status === 'terminated' ? rand(15, 20) / 10 : rand(25, 40) / 10;
            $leastGrade = $status === 'terminated' ? rand(10, 15) / 10 : rand(20, 35) / 10;
            
            StudentProfile::create([
                'user_id' => $student->id,
                'student_number' => '2024' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'name' => $student->name,
                'course' => 'BS Computer Science',
                'year_level' => rand(1, 4),
                'contact_number' => '09' . rand(100000000, 999999999),
                'address' => fake()->address(),
                'birth_date' => fake()->date(),
                'parent_name' => fake()->name(),
                'parent_contact' => '09' . rand(100000000, 999999999),
                'current_gpa' => $currentGpa,
                'least_grade' => $leastGrade,
                'status' => $status,
                'scholarship_type' => $scholarshipType
            ]);
        }
        
        // Create specific test cases for each scholarship type
        $testCases = [
            [
                'scholarship_type' => 'presidential',
                'status' => 'maintained',
                'current_gpa' => 3.5,
                'least_grade' => 3.0,
                'name' => 'Presidential Scholar'
            ],
            [
                'scholarship_type' => 'academic',
                'status' => 'maintained',
                'current_gpa' => 3.0,
                'least_grade' => 2.5,
                'name' => 'Academic Scholar'
            ],
            [
                'scholarship_type' => 'ched',
                'status' => 'maintained',
                'current_gpa' => 2.8,
                'least_grade' => 2.3,
                'name' => 'CHED Scholar'
            ],
            [
                'scholarship_type' => 'presidential',
                'status' => 'terminated',
                'current_gpa' => 2.0,
                'least_grade' => 1.5,
                'name' => 'Terminated Presidential'
            ],
            [
                'scholarship_type' => 'academic',
                'status' => 'new',
                'current_gpa' => null,
                'least_grade' => null,
                'name' => 'New Academic'
            ]
        ];
        
        foreach ($testCases as $case) {
            $user = User::create([
                'name' => $case['name'],
                'email' => strtolower(str_replace(' ', '.', $case['name'])) . '@example.com',
                'password' => bcrypt('password'),
                'role' => 'student'
            ]);
            
            StudentProfile::create([
                'user_id' => $user->id,
                'name' => $case['name'],
                'student_number' => '2024' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                'course' => 'BS Computer Science',
                'year_level' => rand(1, 4),
                'contact_number' => '09' . rand(100000000, 999999999),
                'address' => fake()->address(),
                'birth_date' => fake()->date(),
                'parent_name' => fake()->name(),
                'parent_contact' => '09' . rand(100000000, 999999999),
                'current_gpa' => $case['current_gpa'],
                'least_grade' => $case['least_grade'],
                'status' => $case['status'],
                'scholarship_type' => $case['scholarship_type']
            ]);
        }
    }
}
