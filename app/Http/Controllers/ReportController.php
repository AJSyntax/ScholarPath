<?php

namespace App\Http\Controllers;

use App\Models\Scholarship;
use App\Models\ScholarshipApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use League\Csv\Writer;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function scholarships(Request $request)
    {
        $scholars = ScholarshipApplication::with(['user', 'scholarship'])
            ->where('status', 'approved')
            ->whereHas('scholarship', function ($query) use ($request) {
                if ($request->filled('status')) {
                    $query->where('status', $request->status);
                }
            })
            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->where('created_at', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->where('created_at', '<=', $request->date_to);
            })
            ->latest()
            ->paginate(15);

        $courses = ScholarshipApplication::distinct()->pluck('course')->filter();

        return view('admin.reports.scholarships', compact('scholars', 'courses'));
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

    public function exportScholarships(Request $request)
    {
        $query = Scholarship::query();

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to);
        }

        $scholarships = $query->withCount('applications')->get();

        // Create CSV
        $csv = Writer::createFromString('');
        $csv->insertOne([
            'Title',
            'Description',
            'Amount',
            'Deadline',
            'Status',
            'Applications Count',
            'Created At'
        ]);

        foreach ($scholarships as $scholarship) {
            $csv->insertOne([
                $scholarship->title,
                $scholarship->description,
                $scholarship->amount,
                $scholarship->deadline,
                $scholarship->status,
                $scholarship->applications_count,
                $scholarship->created_at
            ]);
        }

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="scholarships-report.csv"',
        ];

        return Response::make($csv->toString(), 200, $headers);
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
