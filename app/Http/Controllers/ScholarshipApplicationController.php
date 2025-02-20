<?php

namespace App\Http\Controllers;

use App\Models\Scholarship;
use App\Models\ScholarshipApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ScholarshipApplicationController extends Controller
{
    // Admin methods
    public function adminIndex()
    {
        $applications = ScholarshipApplication::with(['scholarship', 'user'])
            ->latest()
            ->paginate(15);

        return view('admin.scholarships.applications.index', compact('applications'));
    }

    public function adminShow(ScholarshipApplication $application)
    {
        $application->load(['scholarship', 'user']);
        return view('admin.scholarships.applications.show', compact('application'));
    }

    public function adminUpdate(Request $request, ScholarshipApplication $application)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_remarks' => 'nullable|string|max:1000'
        ]);

        $application->update($validated);

        // Send notification to student about status update
        $application->user->notify(new ApplicationStatusUpdated($application));

        return redirect()
            ->route('admin.scholarships.applications.show', $application)
            ->with('success', 'Application status updated successfully.');
    }

    // Student methods
    public function studentIndex()
    {
        $applications = ScholarshipApplication::where('user_id', auth()->id())
            ->with('scholarship')
            ->latest()
            ->paginate(10);

        return view('student.scholarships.applications.index', compact('applications'));
    }

    public function create(Scholarship $scholarship)
    {
        // Check if user has already applied
        $hasApplied = $scholarship->applications()
            ->where('user_id', auth()->id())
            ->exists();

        if ($hasApplied) {
            return redirect()
                ->route('student.scholarships.show', $scholarship)
                ->with('error', 'You have already applied for this scholarship.');
        }

        // Check if scholarship is still open
        if ($scholarship->status !== 'published' || $scholarship->deadline < now()) {
            return redirect()
                ->route('student.scholarships.index')
                ->with('error', 'This scholarship is no longer accepting applications.');
        }

        return view('student.scholarships.apply', compact('scholarship'));
    }

    public function store(Request $request, Scholarship $scholarship)
    {
        // Validate basic information
        $validated = $request->validate([
            'academic_year' => 'required|string',
            'gpa' => 'required|numeric|min:0|max:4',
            'family_income' => 'required|numeric|min:0',
            'essay' => 'required|string|min:500',
            'documents.*' => 'required|file|mimes:pdf,doc,docx|max:10240' // 10MB max
        ]);

        // Handle file uploads
        $documents = [];
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $key => $file) {
                $path = $file->store('scholarship-documents');
                $documents[$key] = $path;
            }
        }

        // Create application
        $application = $scholarship->applications()->create([
            'user_id' => auth()->id(),
            'academic_year' => $validated['academic_year'],
            'gpa' => $validated['gpa'],
            'family_income' => $validated['family_income'],
            'essay' => $validated['essay'],
            'documents' => $documents,
            'status' => 'pending'
        ]);

        // Notify admin about new application
        // Notify student about successful submission
        
        return redirect()
            ->route('student.scholarships.applications.show', $application)
            ->with('success', 'Your application has been submitted successfully.');
    }

    public function studentShow(ScholarshipApplication $application)
    {
        // Ensure student can only view their own applications
        if ($application->user_id !== auth()->id()) {
            abort(403);
        }

        $application->load('scholarship');
        return view('student.scholarships.applications.show', compact('application'));
    }
}
