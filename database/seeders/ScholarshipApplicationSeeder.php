<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Scholarship;
use App\Models\ScholarshipApplication;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ScholarshipApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $students = User::where('role', 'student')->get();
        $scholarships = Scholarship::all();
        $currentYear = Carbon::now()->year;
        $semesters = ['first', 'second', 'summer'];

        // Track used combinations to avoid duplicates
        $usedCombinations = [];

        // Scenario 1: Applications in various states
        $statuses = [
            'draft',
            'submitted',
            'under_review',
            'approved',
            'rejected',
            'waitlisted'
        ];

        // Create a collection of unused scholarships for each student
        $studentScholarships = [];
        foreach ($students as $student) {
            $studentScholarships[$student->id] = $scholarships->pluck('id')->toArray();
        }

        foreach ($students as $index => $student) {
            // Each student gets different number of applications
            $numberOfApplications = min(3, count($studentScholarships[$student->id]));
            
            // Get random scholarship IDs from the student's unused pool
            $scholarshipIds = array_rand(array_flip($studentScholarships[$student->id]), $numberOfApplications);
            if (!is_array($scholarshipIds)) {
                $scholarshipIds = [$scholarshipIds];
            }

            foreach ($scholarshipIds as $scholarshipId) {
                $status = $statuses[$index % count($statuses)];
                $semester = $semesters[array_rand($semesters)];
                
                // Check if this combination already exists
                $key = "{$student->id}-{$scholarshipId}-{$currentYear}-{$semester}";
                if (in_array($key, $usedCombinations)) {
                    continue;
                }
                $usedCombinations[] = $key;

                // Remove used scholarship from student's pool
                $studentScholarships[$student->id] = array_diff($studentScholarships[$student->id], [$scholarshipId]);

                $submittedAt = $status !== 'draft' ? fake()->dateTimeBetween('-6 months', 'now') : null;
                $reviewedAt = in_array($status, ['approved', 'rejected', 'waitlisted']) ? 
                    fake()->dateTimeBetween($submittedAt, 'now') : null;
                
                $hasOtherScholarship = fake()->boolean(30);

                ScholarshipApplication::create([
                    'user_id' => $student->id,
                    'scholarship_id' => $scholarshipId,
                    'status' => $status,
                    'submitted_at' => $submittedAt,
                    'current_gpa' => fake()->randomFloat(2, 75, 99),
                    'previous_gpa' => fake()->randomFloat(2, 75, 99),
                    'academic_year' => $currentYear,
                    'semester' => $semester,
                    'course' => fake()->randomElement([
                        'Bachelor of Science in Computer Science',
                        'Bachelor of Science in Information Technology',
                        'Bachelor of Science in Business Administration',
                        'Bachelor of Arts in Communication'
                    ]),
                    'year_level' => fake()->numberBetween(1, 4),
                    'has_other_scholarship' => $hasOtherScholarship,
                    'other_scholarship_details' => $hasOtherScholarship ? fake()->sentence() : null,
                    'statement_of_purpose' => fake()->paragraphs(3, true),
                    'extra_curricular_activities' => fake()->paragraphs(2, true),
                    'awards_honors' => fake()->boolean(70) ? fake()->paragraphs(1, true) : null,
                    'financial_statement' => fake()->paragraphs(2, true),
                    'reviewer_notes' => $reviewedAt ? fake()->paragraph() : null,
                    'reviewed_at' => $reviewedAt,
                    'reviewed_by' => $reviewedAt ? $admin->id : null,
                ]);
            }
        }

        // Scenario 2: Create some applications for previous semester
        foreach ($students->random(2) as $student) {
            if (empty($studentScholarships[$student->id])) {
                continue;
            }
            
            $scholarshipId = array_rand(array_flip($studentScholarships[$student->id]));
            $key = "{$student->id}-{$scholarshipId}-" . ($currentYear - 1) . "-second";
            if (in_array($key, $usedCombinations)) {
                continue;
            }
            $usedCombinations[] = $key;
            
            // Remove used scholarship from student's pool
            $studentScholarships[$student->id] = array_diff($studentScholarships[$student->id], [$scholarshipId]);

            ScholarshipApplication::create([
                'user_id' => $student->id,
                'scholarship_id' => $scholarshipId,
                'status' => 'approved',
                'submitted_at' => fake()->dateTimeBetween('-1 year', '-6 months'),
                'current_gpa' => fake()->randomFloat(2, 85, 99),
                'previous_gpa' => fake()->randomFloat(2, 85, 99),
                'academic_year' => $currentYear - 1,
                'semester' => 'second',
                'course' => 'Bachelor of Science in Computer Science',
                'year_level' => fake()->numberBetween(1, 3),
                'has_other_scholarship' => false,
                'statement_of_purpose' => fake()->paragraphs(3, true),
                'extra_curricular_activities' => fake()->paragraphs(2, true),
                'awards_honors' => fake()->paragraphs(1, true),
                'financial_statement' => fake()->paragraphs(2, true),
                'reviewer_notes' => fake()->paragraph(),
                'reviewed_at' => fake()->dateTimeBetween('-11 months', '-6 months'),
                'reviewed_by' => $admin->id,
            ]);
        }

        // Scenario 3: Create some draft applications that were never submitted
        foreach ($students->random(3) as $student) {
            if (empty($studentScholarships[$student->id])) {
                continue;
            }
            
            $scholarshipId = array_rand(array_flip($studentScholarships[$student->id]));
            $key = "{$student->id}-{$scholarshipId}-{$currentYear}-first";
            if (in_array($key, $usedCombinations)) {
                continue;
            }
            $usedCombinations[] = $key;
            
            // Remove used scholarship from student's pool
            $studentScholarships[$student->id] = array_diff($studentScholarships[$student->id], [$scholarshipId]);

            ScholarshipApplication::create([
                'user_id' => $student->id,
                'scholarship_id' => $scholarshipId,
                'status' => 'draft',
                'current_gpa' => fake()->randomFloat(2, 75, 95),
                'academic_year' => $currentYear,
                'semester' => 'first',
                'course' => fake()->randomElement([
                    'Bachelor of Science in Information Technology',
                    'Bachelor of Science in Business Administration'
                ]),
                'year_level' => fake()->numberBetween(1, 4),
                'has_other_scholarship' => false,
                'statement_of_purpose' => fake()->paragraph(),
                'financial_statement' => fake()->paragraph(),
            ]);
        }
    }
}
