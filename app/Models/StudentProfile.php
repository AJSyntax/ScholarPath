<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_number',
        'course',
        'year_level',
        'contact_number',
        'address',
        'birth_date',
        'parent_name',
        'parent_contact',
        'current_gpa',
        'least_grade',
        'status',
        'scholarship_type'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'current_gpa' => 'decimal:2',
        'least_grade' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scholarshipApplications()
    {
        return $this->hasMany(ScholarshipApplication::class, 'user_id', 'user_id');
    }
}
