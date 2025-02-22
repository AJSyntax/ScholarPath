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
        return view('admin.grades.upload');
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
            
            $processedStudents = [];

            while (($row = fgetcsv($file)) !== false) {
                $studentId = $row[2]; // ID Number column
                $finalGrade = floatval($row[8]); // Finals column

                if (!isset($processedStudents[$studentId])) {
                    $processedStudents[$studentId] = [
                        'grades' => [],
                        'total' => 0,
                        'count' => 0
                    ];
                }

                $processedStudents[$studentId]['grades'][] = $finalGrade;
                $processedStudents[$studentId]['total'] += $finalGrade;
                $processedStudents[$studentId]['count']++;
            }

            fclose($file);

            // Update student profiles with calculated GPA and least grade
            foreach ($processedStudents as $studentId => $data) {
                $gpa = $data['total'] / $data['count'];
                $leastGrade = min($data['grades']);

                StudentProfile::where('user_id', $studentId)
                    ->update([
                        'current_gpa' => number_format($gpa, 2),
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
