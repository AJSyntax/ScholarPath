<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'requirements',
        'discount_percentage',
        'status', // active, inactive
        'type', // academic, presidential, ched
    ];

    protected $casts = [
        'requirements' => 'array'
    ];

    public function applications()
    {
        return $this->hasMany(ScholarshipApplication::class);
    }
}
