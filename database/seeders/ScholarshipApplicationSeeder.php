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
        $students = User::where('role', 'student')->get();
        $scholarships = Scholarship::all();
        $currentYear = Carbon::now()->year;
        $semesters = ['first', 'second', 'summer'];
        $statuses = ['submitted', 'under_review', 'approved', 'rejected', 'waitlisted'];

        // Initialize the used combinations array
        $usedCombinations = [];

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
                $hasOtherScholarship = fake()->boolean(30);
                
                // Check if this combination already exists
                $key = "{$student->id}-{$scholarshipId}-{$currentYear}-{$semester}";
                if (in_array($key, $usedCombinations)) {
                    continue;
                }
                $usedCombinations[] = $key;

                // Remove used scholarship from student's pool
                $studentScholarships[$student->id] = array_diff($studentScholarships[$student->id], [$scholarshipId]);

                ScholarshipApplication::create([
                    'user_id' => $student->id,
                    'scholarship_id' => $scholarshipId,
                    'status' => $status,
                    'submitted_at' => Carbon::now(),
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
                    'reviewer_notes' => in_array($status, ['approved', 'rejected', 'waitlisted']) ? fake()->paragraphs(1, true) : null,
                    'reviewed_at' => in_array($status, ['approved', 'rejected', 'waitlisted']) ? Carbon::now() : null,
                    'reviewed_by' => in_array($status, ['approved', 'rejected', 'waitlisted']) ? User::where('role', '!=', 'student')->inRandomOrder()->first()->id : null
                ]);
            }
        }
    }
}
