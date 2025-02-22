<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ScholarshipController as AdminScholarshipController;
use App\Http\Controllers\Admin\ScholarshipApplicationController as AdminScholarshipApplicationController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\ScholarshipController as StudentScholarshipController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\ScholarshipController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard Redirect
Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('student.dashboard');
})->middleware(['auth'])->name('dashboard');

// Common Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile'); // Added this line
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Scholarship Management
    Route::prefix('scholarships')->name('scholarships.')->group(function () {
        Route::get('/', [AdminScholarshipController::class, 'index'])->name('index');
        Route::get('/create', [AdminScholarshipController::class, 'create'])->name('create');
        Route::post('/', [AdminScholarshipController::class, 'store'])->name('store');
        
        // Application Management
        Route::get('/applications', [AdminScholarshipApplicationController::class, 'index'])->name('applications.index');
        Route::get('/applications/{application}', [AdminScholarshipApplicationController::class, 'show'])->name('applications.show');
        Route::patch('/applications/{application}/status', [AdminScholarshipApplicationController::class, 'updateStatus'])->name('applications.update-status');
        Route::get('/applications/{application}/document/{document}', [AdminScholarshipApplicationController::class, 'downloadDocument'])->name('applications.download-document');
        
        // Single Scholarship Routes
        Route::get('/{scholarship}', [AdminScholarshipController::class, 'show'])->name('show');
        Route::get('/{scholarship}/edit', [AdminScholarshipController::class, 'edit'])->name('edit');
        Route::put('/{scholarship}', [AdminScholarshipController::class, 'update'])->name('update');
        Route::delete('/{scholarship}', [AdminScholarshipController::class, 'destroy'])->name('destroy');
    });

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/scholarships', [ReportController::class, 'scholarships'])->name('scholarships');
        Route::get('/applications', [ReportController::class, 'applications'])->name('applications');
        Route::post('/scholarships/export', [ReportController::class, 'exportScholarships'])->name('scholarships.export');
        Route::post('/applications/export', [ReportController::class, 'exportApplications'])->name('applications.export');
    });
});

// Student Routes
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('student.dashboard');
    })->name('dashboard');

    // Scholarships
    Route::prefix('scholarships')->name('scholarships.')->group(function () {
        Route::get('/', [ScholarshipController::class, 'studentIndex'])->name('index');
        Route::get('/{scholarship}', [ScholarshipController::class, 'studentShow'])->name('show');
        
        // Applications
        Route::get('/applications', [ScholarshipApplicationController::class, 'studentIndex'])->name('applications');
        Route::get('/{scholarship}/apply', [ScholarshipApplicationController::class, 'create'])->name('apply');
        Route::post('/{scholarship}/apply', [ScholarshipApplicationController::class, 'store'])->name('submit');
        Route::get('/applications/{application}', [ScholarshipApplicationController::class, 'studentShow'])->name('applications.show');
    });
});

require __DIR__.'/auth.php';