<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('scholarship_applications', function (Blueprint $table) {
            // Drop existing columns that we'll modify
            $table->dropColumn([
                'status', 
                'gpa', 
                'lowest_grade', 
                'academic_year', 
                'semester',
                'course',
                'year_level'
            ]);

            // Add new status enum
            $table->enum('status', ['draft', 'submitted', 'under_review', 'approved', 'rejected', 'waitlisted'])->default('draft');
            
            // Add new columns
            $table->timestamp('submitted_at')->nullable();
            $table->decimal('current_gpa', 4, 2)->nullable();
            $table->decimal('previous_gpa', 4, 2)->nullable();
            $table->integer('academic_year');
            $table->enum('semester', ['first', 'second', 'summer'])->default('first');
            $table->string('course');
            $table->integer('year_level');
            $table->boolean('has_other_scholarship')->default(false);
            $table->string('other_scholarship_details')->nullable();
            $table->text('statement_of_purpose')->nullable();
            $table->text('extra_curricular_activities')->nullable();
            $table->text('awards_honors')->nullable();
            $table->text('financial_statement')->nullable();
            $table->text('reviewer_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');

            // Add unique constraint with shorter name
            $table->unique(['user_id', 'scholarship_id', 'academic_year', 'semester'], 'scholarship_app_unique');
        });
    }

    public function down(): void
    {
        Schema::table('scholarship_applications', function (Blueprint $table) {
            // Remove unique constraint
            $table->dropUnique('scholarship_app_unique');

            // Remove new columns
            $table->dropColumn([
                'submitted_at',
                'current_gpa',
                'previous_gpa',
                'has_other_scholarship',
                'other_scholarship_details',
                'statement_of_purpose',
                'extra_curricular_activities',
                'awards_honors',
                'financial_statement',
                'reviewer_notes',
                'reviewed_at',
                'reviewed_by'
            ]);

            // Restore original columns
            $table->dropColumn(['status', 'course', 'year_level', 'academic_year', 'semester']);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->decimal('gpa', 4, 2);
            $table->decimal('lowest_grade', 4, 2);
            $table->string('academic_year');
            $table->enum('semester', ['1st', '2nd', 'summer']);
            $table->string('course');
            $table->integer('year_level');
        });
    }
};
