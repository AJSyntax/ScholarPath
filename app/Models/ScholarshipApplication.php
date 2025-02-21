<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class ScholarshipApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'scholarship_id',
        'status',
        'submitted_at',
        'current_gpa',
        'previous_gpa',
        'academic_year',
        'semester',
        'course',
        'year_level',
        'has_other_scholarship',
        'other_scholarship_details',
        'statement_of_purpose',
        'extra_curricular_activities',
        'awards_honors',
        'financial_statement',
        'reviewer_notes',
        'reviewed_at',
        'reviewed_by'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'current_gpa' => 'decimal:2',
        'previous_gpa' => 'decimal:2',
        'has_other_scholarship' => 'boolean',
        'academic_year' => 'integer',
        'year_level' => 'integer'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function studentProfile()
    {
        return $this->hasOneThrough(StudentProfile::class, User::class, 'id', 'user_id', 'user_id');
    }

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Scopes
    public function scopePending(Builder $query): void
    {
        $query->whereIn('status', ['submitted', 'under_review']);
    }

    public function scopeApproved(Builder $query): void
    {
        $query->where('status', 'approved');
    }

    public function scopeRejected(Builder $query): void
    {
        $query->where('status', 'rejected');
    }

    public function scopeWaitlisted(Builder $query): void
    {
        $query->where('status', 'waitlisted');
    }

    public function scopeCurrentAcademicYear(Builder $query): void
    {
        $query->where('academic_year', $this->getCurrentAcademicYear());
    }

    public function scopeCurrentSemester(Builder $query): void
    {
        $query->where([
            'academic_year' => $this->getCurrentAcademicYear(),
            'semester' => $this->getCurrentSemester()
        ]);
    }

    // Helper Methods
    public function getCurrentAcademicYear(): int
    {
        $now = Carbon::now();
        return $now->month >= 6 ? $now->year : $now->year - 1;
    }

    public function getCurrentSemester(): string
    {
        $month = Carbon::now()->month;
        if ($month >= 6 && $month <= 10) {
            return 'first';
        } elseif ($month >= 11 || $month <= 3) {
            return 'second';
        } else {
            return 'summer';
        }
    }

    // Status helper methods
    public function isPending(): bool
    {
        return in_array($this->status, ['submitted', 'under_review']);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isWaitlisted(): bool
    {
        return $this->status === 'waitlisted';
    }

    public function canBeEdited(): bool
    {
        return $this->status === 'draft';
    }

    // Validation Rules
    public static function validationRules(): array
    {
        return [
            'current_gpa' => 'required|numeric|min:0|max:100',
            'previous_gpa' => 'nullable|numeric|min:0|max:100',
            'academic_year' => 'required|integer|min:2020',
            'semester' => 'required|in:first,second,summer',
            'course' => 'required|string|max:255',
            'year_level' => 'required|integer|min:1|max:5',
            'has_other_scholarship' => 'required|boolean',
            'other_scholarship_details' => 'required_if:has_other_scholarship,true',
            'statement_of_purpose' => 'required|string|min:100',
            'extra_curricular_activities' => 'nullable|string',
            'awards_honors' => 'nullable|string',
            'financial_statement' => 'required|string',
        ];
    }
}
