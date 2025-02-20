<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScholarshipApplication;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $activeScholars = ScholarshipApplication::where('status', 'approved')->count();
        $pendingApplications = ScholarshipApplication::where('status', 'pending')->count();

        $applications = ScholarshipApplication::query()
            ->when($request->search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('student_number', 'like', "%{$search}%");
                });
            })
            ->when($request->scholarship_type, function ($query, $type) {
                $query->whereHas('scholarship', function ($q) use ($type) {
                    $q->where('type', $type);
                });
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->with(['user', 'scholarship'])
            ->latest()
            ->paginate(10);

        return view('admin.dashboard', compact('activeScholars', 'pendingApplications', 'applications'));
    }
}
