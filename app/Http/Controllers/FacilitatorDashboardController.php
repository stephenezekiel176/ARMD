<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\User;
use App\Models\Enrollment;
use App\Models\Submission;

class FacilitatorDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get courses with proper scoping and pagination
        $courses = Course::where('facilitator_id', $user->id)
            ->where('department_id', $user->department_id)
            ->when(config('app.env') === 'production', function($query) {
                return $query->where('status', 'active');
            })
            ->with(['department', 'assessments']) // Eager load relationships
            ->latest()
            ->paginate(10);

        // Get personnel users belonging to the facilitator's department
        $personnel = User::where('role', 'personnel')
            ->where('department_id', $user->department_id)
            ->with(['enrollments.course', 'submissions' => function($q) {
                $q->latest()->take(5);
            }])
            ->latest()
            ->paginate(15);

        // Calculate department statistics
        $totalPersonnel = User::where('role', 'personnel')
            ->where('department_id', $user->department_id)
            ->count();

        $activeEnrollments = Enrollment::whereHas('course', function($query) use ($user) {
                $query->where('department_id', $user->department_id);
            })
            ->where('status', 'active')
            ->count();

        $completedCourses = Enrollment::whereHas('course', function($query) use ($user) {
                $query->where('department_id', $user->department_id);
            })
            ->where('status', 'completed')
            ->count();

        $totalSubmissions = Submission::whereHas('assessment', function($query) use ($user) {
                $query->whereHas('course', function($q) use ($user) {
                    $q->where('department_id', $user->department_id);
                });
            })
            ->count();

        $pendingGrading = Submission::whereNull('score')
            ->whereHas('assessment', function($query) use ($user) {
                $query->whereHas('course', function($q) use ($user) {
                    $q->where('department_id', $user->department_id);
                });
            })
            ->count();

        // Calculate average score for the department
        $averageScore = Submission::whereNotNull('score')
            ->whereHas('assessment', function($query) use ($user) {
                $query->whereHas('course', function($q) use ($user) {
                    $q->where('department_id', $user->department_id);
                });
            })
            ->avg('score');

        $statistics = [
            'total_personnel' => $totalPersonnel,
            'active_enrollments' => $activeEnrollments,
            'completed_courses' => $completedCourses,
            'total_submissions' => $totalSubmissions,
            'pending_grading' => $pendingGrading,
            'average_score' => round($averageScore ?? 0, 1),
            'completion_rate' => $activeEnrollments > 0 ? round(($completedCourses / ($activeEnrollments + $completedCourses)) * 100, 1) : 0,
        ];

        return view('facilitator.dashboard', compact('courses', 'personnel', 'statistics'));
    }
}
