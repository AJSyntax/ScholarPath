<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScholarshipApplication;
use Illuminate\Http\Request;

class ScholarshipApplicationController extends Controller
{
    public function index()
    {
        $applications = ScholarshipApplication::with(['user', 'scholarship'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.scholarships.applications.index', compact('applications'));
    }

    public function show(ScholarshipApplication $application)
    {
        return view('admin.scholarships.applications.show', compact('application'));
    }

    public function updateStatus(Request $request, ScholarshipApplication $application)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,approved,rejected'],
            'remarks' => ['nullable', 'string', 'max:500'],
        ]);

        $application->update($validated);

        return redirect()->back()->with('success', 'Application status updated successfully.');
    }

    public function downloadDocument(ScholarshipApplication $application, $document)
    {
        // Implement document download logic here
        return response()->download(storage_path("app/documents/{$document}"));
    }
}