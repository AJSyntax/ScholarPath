<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scholarship;
use App\Models\ScholarshipApplication;
use Illuminate\Http\Request;

class ScholarshipController extends Controller
{
    public function index()
    {
        $scholarships = Scholarship::all();
        return view('admin.scholarships.index', compact('scholarships'));
    }

    public function applications()
    {
        $applications = ScholarshipApplication::with(['user', 'scholarship'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.scholarships.applications', compact('applications'));
    }

    public function updateStatus(Request $request, ScholarshipApplication $application)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $application->update($validated);

        return back()->with('success', 'Application status updated successfully!');
    }

    public function reports()
    {
        $applications = ScholarshipApplication::with(['user', 'scholarship'])
            ->where('status', 'approved')
            ->get();

        return view('admin.scholarships.reports', compact('applications'));
    }

    public function generateReport(Request $request)
    {
        $validated = $request->validate([
            'academic_year' => 'required',
            'semester' => 'required|in:1st,2nd,summer',
            'type' => 'required|in:academic,presidential,ched'
        ]);

        $applications = ScholarshipApplication::with(['user', 'scholarship'])
            ->where('status', 'approved')
            ->where('academic_year', $validated['academic_year'])
            ->where('semester', $validated['semester'])
            ->whereHas('scholarship', function($query) use ($validated) {
                $query->where('type', $validated['type']);
            })
            ->get();

        return view('admin.scholarships.report-preview', compact('applications', 'validated'));
    }
}
