<?php

namespace App\Livewire\Admin\Grades;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\StudentProfile;
use League\Csv\Reader;
use League\Csv\Statement;

class Upload extends Component
{
    use WithFileUploads;

    public $search = '';
    public $suggestions = [];
    public $selectedStudentId = null;
    public $gradesFile;
    public $processing = false;
    public $message = '';
    public $messageType = '';

    public function updatedSearch()
    {
        if (strlen($this->search) >= 2) {
            $this->suggestions = StudentProfile::with('user')
                ->where(function($query) {
                    $query->where('student_number', 'LIKE', "%{$this->search}%")
                        ->orWhereHas('user', function($q) {
                            $q->where('name', 'LIKE', "%{$this->search}%");
                        });
                })
                ->limit(5)
                ->get()
                ->map(function ($student) {
                    return [
                        'id' => $student->id,
                        'student_number' => $student->student_number,
                        'name' => $student->user->name,
                        'course' => $student->course,
                        'year_level' => $student->year_level,
                        'scholarship_type' => $student->scholarship_type,
                        'status' => $student->status
                    ];
                })
                ->toArray();
        } else {
            $this->suggestions = [];
        }
    }

    public function selectStudent($studentId)
    {
        $student = StudentProfile::with('user')->find($studentId);
        if ($student) {
            $this->selectedStudentId = $studentId;
            $this->search = "{$student->student_number} - {$student->user->name}";
            $this->dispatch('studentSelected', $studentId);
        }
        $this->suggestions = [];
    }

    public function uploadGrades()
    {
        $this->validate([
            'gradesFile' => 'required|file|mimes:csv,txt|max:1024',
            'selectedStudentId' => 'required'
        ]);

        try {
            $this->processing = true;

            // Read the CSV file
            $csv = Reader::createFromPath($this->gradesFile->getRealPath());
            $csv->setHeaderOffset(0);
            
            // Try to detect the delimiter
            $content = file_get_contents($this->gradesFile->getRealPath());
            $firstLine = strtok($content, "\n");
            
            if (strpos($firstLine, "\t") !== false) {
                $csv->setDelimiter("\t");
            } else if (strpos($firstLine, ",") !== false) {
                $csv->setDelimiter(",");
            } else {
                throw new \Exception("Could not detect file delimiter. Please use tab or comma-separated format.");
            }
            
            // Validate headers
            $headers = $this->validateCsvHeaders($csv);
            
            // Get records using the original header array
            $records = $csv->getRecords();
            $grades = [];
            $totalGradePoints = 0;
            $totalUnits = 0;

            // Process each grade record
            foreach ($records as $record) {
                $units = floatval($record['Units']);
                $midterm = floatval($record['Midterm']);
                $finals = floatval($record['Finals']);
                
                // Validate numeric values
                if (!is_numeric($units) || !is_numeric($midterm) || !is_numeric($finals)) {
                    throw new \Exception('Units, Midterm, and Finals must be numeric values');
                }

                // Validate grade range (1.0 is highest, 5.0 is lowest)
                if ($finals < 1.0 || $finals > 5.0) {
                    throw new \Exception("Invalid grade {$finals} in Finals column for subject {$record['Subject']}. Grades must be between 1.0 (highest) and 5.0 (lowest)");
                }
                
                // Store finals grade for GPA calculation
                $grades[] = [
                    'subject' => $record['Subject'],
                    'description' => $record['Description'] ?? '',
                    'type' => $record['Type'] ?? '',
                    'units' => $units,
                    'midterm' => $midterm,
                    'finals' => $finals
                ];

                $totalGradePoints += $finals; // Use finals grade directly
                $totalUnits++; // Count each subject as 1 for average calculation
            }

            if (empty($grades)) {
                throw new \Exception('No valid grades found in the CSV file');
            }

            // Calculate GPA as average of finals grades (like Excel's AVERAGE)
            $gpa = $totalGradePoints / $totalUnits;
            
            // Find highest numerical grade (worst grade) from finals column (5.0 is worst, 1.0 is best)
            $leastGrade = max(array_column($grades, 'finals'));

            // Validate grade range
            if ($leastGrade < 1.0 || $leastGrade > 5.0) {
                throw new \Exception('Invalid grade found. Grades must be between 1.0 (highest) and 5.0 (lowest)');
            }

            // Update student profile
            $student = StudentProfile::find($this->selectedStudentId);
            $student->current_gpa = number_format($gpa, 2);
            $student->least_grade = number_format($leastGrade, 2);
            
            // Update status based on scholarship type and lowest grade
            $student->status = $this->determineScholarshipStatus($student->scholarship_type, $leastGrade, $gpa);
            
            $student->save();

            $this->message = "Grades analyzed successfully. GPA: {$student->current_gpa}, Least Grade: {$student->least_grade}, Status: {$student->status}";
            $this->messageType = 'success';

        } catch (\Exception $e) {
            $this->message = "Error processing grades: " . $e->getMessage();
            $this->messageType = 'error';
        } finally {
            $this->processing = false;
            $this->gradesFile = null;
        }
    }

    private function validateCsvHeaders($csv)
    {
        $headers = $csv->getHeader();
        
        if (empty($headers)) {
            throw new \Exception('No headers found in CSV file. First row must contain column names.');
        }

        // Debug information
        $originalHeaders = implode(', ', $headers);
        
        // Clean up headers (trim whitespace and standardize case)
        $cleanHeaders = array_map(function($header) {
            return strtolower(trim($header));
        }, $headers);
        
        // Required column names (case insensitive)
        $requiredColumns = ['subject', 'units', 'midterm', 'finals'];
        
        // Check for each required column
        $missingColumns = [];
        foreach ($requiredColumns as $required) {
            if (!in_array($required, $cleanHeaders)) {
                $missingColumns[] = ucfirst($required);
            }
        }
        
        if (!empty($missingColumns)) {
            throw new \Exception(
                "Missing required columns: " . implode(', ', $missingColumns) . 
                "\nFound columns: " . $originalHeaders . 
                "\nProcessed headers: " . implode(', ', $cleanHeaders)
            );
        }
        
        // Return the original headers array (preserves numeric indexes)
        return $headers;
    }

    private function determineScholarshipStatus($scholarshipType, $lowestGrade, $gpa)
    {
        // Default status
        $status = 'maintained';

        // Check for termination conditions based on scholarship type
        switch (strtolower($scholarshipType)) {
            case 'presidential':
                if ($lowestGrade < 2.0 || $gpa < 2.5) {
                    $status = 'terminated';
                }
                break;
            case 'academic':
                if ($lowestGrade < 1.75 || $gpa < 2.25) {
                    $status = 'terminated';
                }
                break;
            case 'athletic':
            case 'cultural':
            case 'ched':
                if ($lowestGrade < 1.5 || $gpa < 2.0) {
                    $status = 'terminated';
                }
                break;
            default:
                if ($lowestGrade < 1.5 || $gpa < 2.0) {
                    $status = 'terminated';
                }
        }

        return $status;
    }

    public function render()
    {
        return view('livewire.admin.grades.upload');
    }
}