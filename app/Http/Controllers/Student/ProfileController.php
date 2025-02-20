<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentProfile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        $profile = auth()->user()->studentProfile ?? new StudentProfile();
        return view('student.profile.show', compact('profile'));
    }

    public function edit()
    {
        $profile = auth()->user()->studentProfile ?? new StudentProfile();
        return view('student.profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'student_number' => 'required|string|unique:student_profiles,student_number,' . auth()->user()->studentProfile?->id,
            'course' => 'required|string',
            'year_level' => 'required|integer|min:1|max:5',
            'contact_number' => 'required|string',
            'address' => 'required|string',
            'birth_date' => 'required|date',
            'parent_name' => 'required|string',
            'parent_contact' => 'required|string',
            'current_gpa' => 'nullable|numeric|min:0|max:100',
        ]);

        $profile = auth()->user()->studentProfile ?? new StudentProfile(['user_id' => auth()->id()]);
        $profile->fill($validated);
        $profile->save();

        return redirect()->route('student.profile.show')
            ->with('success', 'Profile updated successfully!');
    }
}
