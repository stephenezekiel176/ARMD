<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class FacilitatorProgressController extends Controller
{
    public function index(User $personnel)
    {
        // Ensure the personnel belongs to the facilitator's department
        if ($personnel->department_id !== auth()->user()->department_id || $personnel->role !== 'personnel') {
            abort(403, 'Unauthorized access to personnel from different department.');
        }

        $personnel->load([
            'enrollments.course',
            'submissions.assessment',
            'submissions' => function($query) {
                $query->latest();
            }
        ]);

        // Calculate additional metrics
        $totalAssessments = $personnel->submissions->count();
        $gradedAssessments = $personnel->submissions->whereNotNull('score')->count();
        $averageScore = $gradedAssessments > 0 ? $personnel->submissions->whereNotNull('score')->avg('score') : 0;
        $completionRate = $personnel->enrollments->count() > 0 
            ? ($personnel->enrollments->where('status', 'completed')->count() / $personnel->enrollments->count()) * 100 
            : 0;

        return view('facilitator.personnel.progress', compact('personnel', 'totalAssessments', 'gradedAssessments', 'averageScore', 'completionRate'));
    }
}
