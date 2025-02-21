<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScholarshipApplication;
use App\Models\StudentProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScholarshipReportController extends Controller
{
    public function index(Request $request)
    {
        $query = ScholarshipApplication::with(['user', 'scholarship', 'studentProfile'])
            ->join('student_profiles', 'scholarship_applications.user_id', '=', 'student_profiles.user_id')
            ->select('scholarship_applications.*');

        // Apply filters
        if ($request->filled('course')) {
            $query->where('student_profiles.course', $request->course);
        }

        if ($request->filled('year_level')) {
            $query->where('student_profiles.year_level', $request->year_level);
        }

        if ($request->filled('semester')) {
            $query->where('scholarship_applications.semester', $request->semester);
        }

        // Get unique courses for filter dropdown
        $courses = StudentProfile::distinct()->pluck('course');

        // Get paginated results
        $scholars = $query->paginate(10);

        return view('admin.reports.scholarships', compact('scholars', 'courses'));
    }
}