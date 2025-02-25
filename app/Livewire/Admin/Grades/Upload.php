<?php

namespace App\Livewire\Admin\Grades;

use Livewire\Component;
use App\Models\StudentProfile;

class Upload extends Component
{
    public $search = '';
    public $suggestions = [];

    public function updatedSearch()
    {
        if (strlen($this->search) >= 2) {
            $this->suggestions = StudentProfile::where('student_number', 'LIKE', "%{$this->search}%")
                ->orWhere('first_name', 'LIKE', "%{$this->search}%")
                ->orWhere('last_name', 'LIKE', "%{$this->search}%")
                ->limit(5)
                ->get()
                ->map(function ($student) {
                    return [
                        'id' => $student->id,
                        'student_number' => $student->student_number,
                        'name' => $student->first_name . ' ' . $student->last_name
                    ];
                })
                ->toArray();
        } else {
            $this->suggestions = [];
        }
    }

    public function selectStudent($studentId)
    {
        $student = StudentProfile::find($studentId);
        if ($student) {
            $this->search = $student->student_number . ' - ' . $student->first_name . ' ' . $student->last_name;
            $this->emit('studentSelected', $studentId);
        }
        $this->suggestions = [];
    }

    public function render()
    {
        return view('livewire.admin.grades.upload');
    }
}