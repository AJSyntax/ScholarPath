<?php

namespace App\Http\Controllers;

use App\Models\Scholarship;
use App\Models\ScholarshipApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function scholarships(Request $request)
    {
        $scholarTypes = ['academic', 'presidential', 'ched'];
        $type = $request->input('type', $scholarTypes[0]);
        $semester = $request->input('semester', 'First');
        $academicYear = Carbon::now()->format('Y') . '-' . (Carbon::now()->addYear()->format('Y'));

        // Get approved scholars for the specified type
        $scholars = ScholarshipApplication::with(['user', 'scholarship'])
            ->where('status', 'approved')
            ->whereHas('scholarship', function($query) use ($type) {
                $query->where('type', $type);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'scholars' => $scholars,
                'type' => $type,
                'semester' => $semester,
                'academicYear' => $academicYear
            ]);
        }

        return view('admin.reports.scholarships', compact('scholars', 'scholarTypes', 'type', 'semester', 'academicYear'));
    }

    public function exportScholarships(Request $request)
    {
        $type = $request->input('type', 'academic');
        $semester = $request->input('semester', 'First');
        $academicYear = Carbon::now()->format('Y') . '-' . (Carbon::now()->addYear()->format('Y'));

        // Get approved scholars for the specified type
        $scholars = ScholarshipApplication::with(['user', 'scholarship'])
            ->where('status', 'approved')
            ->whereHas('scholarship', function($query) use ($type) {
                $query->where('type', $type);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Create CSV content
        $headers = [
            'NO.',
            'ID #',
            'NAME OF SCHOLARS',
            'COURSE & YEAR',
            'GPA',
            'LEAST GRADE',
            'STATUS',
            'PRIVILEGES',
            'REMARKS'
        ];

        // Create CSV content
        $csvContent = implode(',', $headers) . "\n";

        foreach ($scholars as $index => $scholar) {
            $privileges = match($type) {
                'academic' => "Full Free Grant (Free Tuition, Miscellaneous and Laboratory Fees including LMS)",
                'presidential' => "Php 1,500 worth of Books per semester",
                default => "Standard scholarship benefits"
            };

            $row = [
                $index + 1,
                $scholar->user->student_id,
                strtoupper($scholar->user->name),
                $scholar->course . '-' . $scholar->year_level,
                number_format($scholar->current_gpa, 2),
                $scholar->lowest_grade,
                'SUMMA CUM LAUDE',
                $privileges,
                $scholar->created_at->format('Y') == date('Y') ? 'NEW' : ''
            ];

            // Escape fields that might contain commas and wrap in quotes
            $row = array_map(function($field) {
                return '"' . str_replace('"', '""', $field) . '"';
            }, $row);

            $csvContent .= implode(',', $row) . "\n";
        }

        // Add UTF-8 BOM to ensure Excel reads special characters correctly
        $csvContent = "\xEF\xBB\xBF" . $csvContent;

        // Generate filename
        $filename = strtolower($type) . '_scholars_' . date('Y-m-d') . '.csv';

        // Return the CSV file
        return Response::make($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ]);
    }

    public function previewScholarships(Request $request)
    {
        $type = $request->input('type', 'academic');
        $semester = $request->input('semester', 'First');
        $academicYear = Carbon::now()->format('Y') . '-' . (Carbon::now()->addYear()->format('Y'));

        // Get approved scholars for the specified type
        $scholars = ScholarshipApplication::with(['user', 'scholarship'])
            ->where('status', 'approved')
            ->whereHas('scholarship', function($query) use ($type) {
                $query->where('type', $type);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.reports.preview-scholars', compact('scholars', 'type', 'semester', 'academicYear'));
    }

    public function applications(Request $request)
    {
        $query = ScholarshipApplication::with(['scholarship', 'user']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('scholarship_id')) {
            $query->where('scholarship_id', $request->scholarship_id);
        }
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        $applications = $query->latest()->paginate(15);
        $scholarships = Scholarship::pluck('title', 'id'); // For filter dropdown

        return view('admin.reports.applications', compact('applications', 'scholarships'));
    }

    public function exportApplications(Request $request)
    {
        $query = ScholarshipApplication::with(['scholarship', 'user']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('scholarship_id')) {
            $query->where('scholarship_id', $request->scholarship_id);
        }
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        $applications = $query->get();

        // Create CSV
        $csv = Writer::createFromString('');
        $csv->insertOne([
            'Student Name',
            'Scholarship',
            'Academic Year',
            'GPA',
            'Family Income',
            'Status',
            'Submitted At'
        ]);

        foreach ($applications as $application) {
            $csv->insertOne([
                $application->user->name,
                $application->scholarship->title,
                $application->academic_year,
                $application->gpa,
                $application->family_income,
                $application->status,
                $application->created_at
            ]);
        }

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="applications-report.csv"',
        ];

        return Response::make($csv->toString(), 200, $headers);
    }
}
