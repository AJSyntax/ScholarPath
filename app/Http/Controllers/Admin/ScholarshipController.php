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

    public function create()
    {
        return view('admin.scholarships.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|array',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive',
            'type' => 'required|in:academic,presidential,ched',
        ]);

        Scholarship::create($validated);

        return redirect()->route('admin.scholarships.index')
            ->with('success', 'Scholarship created successfully.');
    }

    public function show(Scholarship $scholarship)
    {
        return view('admin.scholarships.show', compact('scholarship'));
    }

    public function edit(Scholarship $scholarship)
    {
        return view('admin.scholarships.edit', compact('scholarship'));
    }

    public function update(Request $request, Scholarship $scholarship)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|array',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive',
            'type' => 'required|in:academic,presidential,ched',
        ]);

        $scholarship->update($validated);

        return redirect()->route('admin.scholarships.show', $scholarship)
            ->with('success', 'Scholarship updated successfully.');
    }

    public function destroy(Scholarship $scholarship)
    {
        $scholarship->delete();

        return redirect()->route('admin.scholarships.index')
            ->with('success', 'Scholarship deleted successfully.');
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
