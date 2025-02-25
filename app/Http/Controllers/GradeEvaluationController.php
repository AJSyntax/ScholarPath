<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\StudentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradeEvaluationController extends Controller
{
    public function index()
    {
        $scholars = User::whereHas('scholarshipApplications', function($query) {
            $query->where('status', 'active');
        })->get();

        return view('admin.grades.upload', compact('scholars'));
    }

    public function processGrades(Request $request)
    {
        $request->validate([
            'grades_file' => 'required|file|mimes:csv,txt'
        ]);

        try {
            DB::beginTransaction();

            $file = fopen($request->file('grades_file')->getPathname(), 'r');
            
            // Skip header row
            fgetcsv($file);
            
            $processedGrades = [];
            $processedStudents = [];
            $rowNumber = 2; // Start from row 2 since row 1 is header

            while (($row = fgetcsv($file)) !== false) {
                // Validate row structure
                if (count($row) < 7) { // Updated to include student_id
                    throw new \Exception("Invalid row format at row {$rowNumber}. All columns are required (Student ID, Subject, Description, Type, Units, Midterm, Finals).");
                }

                // Extract and validate data
                $studentId = $row[0];
                $subject = $row[1];
                $description = $row[2];
                $type = $row[3];
                $units = floatval($row[4]);
                $midterm = floatval($row[5]);
                $finals = floatval($row[6]);

                // Validate numeric values
                if (!is_numeric($units) || !is_numeric($midterm) || !is_numeric($finals)) {
                    throw new \Exception("Invalid numeric values at row {$rowNumber}. Units, Midterm, and Finals must be numbers.");
                }

                // Calculate average grade for the subject
                $averageGrade = ($midterm + $finals) / 2;

                // Initialize student data if not exists
                if (!isset($processedStudents[$studentId])) {
                    $processedStudents[$studentId] = [
                        'total' => 0,
                        'count' => 0,
                        'grades' => []
                    ];
                }

                // Update student's grade data
                $processedStudents[$studentId]['total'] += $averageGrade;
                $processedStudents[$studentId]['count']++;
                $processedStudents[$studentId]['grades'][] = $averageGrade;

                $processedGrades[] = [
                    'student_id' => $studentId,
                    'subject' => $subject,
                    'description' => $description,
                    'type' => $type,
                    'units' => $units,
                    'midterm' => $midterm,
                    'finals' => $finals,
                    'average' => $averageGrade
                ];

                $rowNumber++;
            }

            fclose($file);

            // Update student profiles and evaluate scholarship status
            foreach ($processedStudents as $studentId => $data) {
                $gpa = $data['total'] / $data['count'];
                $leastGrade = min($data['grades']);
                $formattedGpa = number_format($gpa, 2);

                // Get student's scholarship information
                $student = StudentProfile::where('user_id', $studentId)
                    ->with(['scholarshipApplications' => function($query) {
                        $query->with('scholarship')->where('status', 'active');
                    }])
                    ->first();

                if ($student && $student->scholarshipApplications) {
                    foreach ($student->scholarshipApplications as $application) {
                        $shouldTerminate = false;
                        $scholarship = $application->scholarship;

                        switch($scholarship->type) {
                            case 'presidential':
                                // Check GPA (1.0-1.4) and individual grades (1.0-2.0)
                                if ($formattedGpa > 1.4 || $leastGrade > 2.0) {
                                    $shouldTerminate = true;
                                }
                                break;

                            case 'academic':
                                // Check GPA (1.21-1.45) and individual grades (1.0-1.8)
                                if ($formattedGpa < 1.21 || $formattedGpa > 1.45 || $leastGrade > 1.8) {
                                    $shouldTerminate = true;
                                }
                                break;

                            case 'ched':
                                // No grade requirements for CHED scholarships (TDP/TES)
                                continue 2;
                        }

                        if ($shouldTerminate) {
                            $application->update(['status' => 'terminated']);
                        }
                    }
                }

                // Update student profile with new grades
                $student->update([
                    'current_gpa' => $formattedGpa,
                    'least_grade' => $leastGrade
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Grades processed successfully! Processed ' . count($processedStudents) . ' student records.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error processing grades: ' . $e->getMessage());
        }
    }
}
