<?php

namespace App\Http\Controllers;

use App\Models\Scholarship;
use App\Models\ScholarshipApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ScholarshipController extends Controller
{
    public function index()
    {
        $scholarships = Scholarship::latest()->paginate(10);
        return view('admin.scholarships.index', compact('scholarships'));
    }

    public function create()
    {
        return view('admin.scholarships.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'requirements' => 'required|string',
            'deadline' => 'required|date|after:today',
            'max_applicants' => 'required|integer|min:1',
            'status' => 'required|in:draft,published,closed',
            'documents_required' => 'required|array',
            'documents_required.*' => 'string'
        ]);

        $scholarship = Scholarship::create($validated);

        return redirect()
            ->route('admin.scholarships.show', $scholarship)
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'requirements' => 'required|string',
            'deadline' => 'required|date',
            'max_applicants' => 'required|integer|min:1',
            'status' => 'required|in:draft,published,closed',
            'documents_required' => 'required|array',
            'documents_required.*' => 'string'
        ]);

        $scholarship->update($validated);

        return redirect()
            ->route('admin.scholarships.show', $scholarship)
            ->with('success', 'Scholarship updated successfully.');
    }

    public function destroy(Scholarship $scholarship)
    {
        $scholarship->delete();

        return redirect()
            ->route('admin.scholarships.index')
            ->with('success', 'Scholarship deleted successfully.');
    }

    // Student-specific methods
    public function studentIndex()
    {
        $scholarships = Scholarship::where('status', 'published')
            ->where('deadline', '>', now())
            ->latest()
            ->paginate(10);
            
        return view('student.scholarships.index', compact('scholarships'));
    }

    public function studentShow(Scholarship $scholarship)
    {
        $hasApplied = $scholarship->applications()
            ->where('user_id', auth()->id())
            ->exists();

        return view('student.scholarships.show', compact('scholarship', 'hasApplied'));
    }
}
