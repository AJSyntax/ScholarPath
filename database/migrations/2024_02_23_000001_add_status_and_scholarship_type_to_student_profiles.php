<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->enum('status', ['new', 'maintained', 'terminated'])->default('new')->after('current_gpa');
            $table->enum('scholarship_type', ['academic', 'athletic', 'cultural', 'presidential', 'ched'])->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropColumn(['status', 'scholarship_type']);
        });
    }
};