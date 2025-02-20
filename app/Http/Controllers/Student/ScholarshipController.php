<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Scholarship;
use App\Models\ScholarshipApplication;
use Illuminate\Http\Request;

class ScholarshipController extends Controller
{
    public function index()
    {
        $scholarships = Scholarship::where('status', 'active')->get();
        $applications = auth()->user()->scholarshipApplications()->with('scholarship')->get();
        
        return view('student.scholarships.index', compact('scholarships', 'applications'));
    }

    public function create()
    {
        $scholarships = Scholarship::where('status', 'active')->get();
        return view('student.scholarships.create', compact('scholarships'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'scholarship_id' => 'required|exists:scholarships,id',
            'gpa' => 'required|numeric|min:0|max:100',
            'lowest_grade' => 'required|numeric|min:0|max:100',
            'academic_year' => 'required|string',
            'semester' => 'required|in:1st,2nd,summer',
            'course' => 'required|string',
            'year_level' => 'required|integer|min:1|max:5',
        ]);

        $validated['user_id'] = auth()->id();
        
        ScholarshipApplication::create($validated);

        return redirect()->route('student.scholarships.index')
            ->with('success', 'Application submitted successfully!');
    }
}
