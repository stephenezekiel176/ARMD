<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Submission;
use App\Models\Message;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Get departments with comprehensive data
        $departments = Department::with(['users' => function($query) {
                $query->select('id', 'department_id', 'role', 'fullname', 'email', 'position');
            }])
            ->withCount(['users', 'courses'])
            ->latest()
            ->get();

        // Get facilitators with their department and course statistics
        $facilitators = User::where('role', 'facilitator')
            ->with(['department', 'courses' => function($query) {
                $query->withCount('enrollments');
            }])
            ->withCount(['courses'])
            ->latest()
            ->get();

        // Get personnel count for each facilitator's department
        foreach ($facilitators as $facilitator) {
            $facilitator->department_personnel_count = $facilitator->department 
                ? $facilitator->department->users()->where('role', 'personnel')->count() 
                : 0;
        }

        // Get personnel statistics
        $personnel = User::where('role', 'personnel')
            ->with(['department', 'enrollments' => function($query) {
                $query->with('course');
            }])
            ->withCount(['enrollments', 'submissions'])
            ->latest()
            ->take(10)
            ->get();

        // Calculate overall statistics
        $totalDepartments = Department::count();
        $totalFacilitators = User::where('role', 'facilitator')->count();
        $totalPersonnel = User::where('role', 'personnel')->count();
        $totalCourses = Course::count();
        $totalEnrollments = Enrollment::count();
        $totalSubmissions = Submission::count();

        // Calculate completion rates
        $completedCourses = Enrollment::where('status', 'completed')->count();
        $completionRate = $totalEnrollments > 0 ? round(($completedCourses / $totalEnrollments) * 100, 1) : 0;

        // Recent activity
        $recentEnrollments = Enrollment::with(['user', 'course'])
            ->latest()
            ->take(5)
            ->get();

        $recentSubmissions = Submission::with(['user', 'assessment.course'])
            ->latest()
            ->take(5)
            ->get();

        // Get recent messages (with error handling)
        try {
            $recentMessages = Message::with(['sender', 'recipient'])
                ->latest()
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            // If there's an issue with messages, set empty collection
            $recentMessages = collect([]);
        }

        return view('admin.dashboard', compact(
            'departments', 
            'facilitators', 
            'personnel',
            'totalDepartments',
            'totalFacilitators', 
            'totalPersonnel',
            'totalCourses',
            'totalEnrollments',
            'totalSubmissions',
            'completionRate',
            'recentEnrollments',
            'recentSubmissions',
            'recentMessages'
        ));
    }
}
