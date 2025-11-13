<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Message;
use App\Models\Assessment;

class PersonnelDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get enrollments with course data
        $enrollments = Enrollment::where('user_id', $user->id)
            ->with('course')
            ->latest()
            ->get();

        // Check for new messages from facilitator
        $hasNewMessages = Message::where(function($query) use ($user) {
                $query->where('recipient_id', $user->id)
                      ->orWhere('department_id', $user->department_id);
            })
            ->where('created_at', '>', $user->last_login_at ?? now()->subDays(7))
            ->exists();

        // Check for new assessments
        $hasNewAssessments = Assessment::whereHas('course', function($query) use ($user) {
                $query->where('department_id', $user->department_id);
            })
            ->where('created_at', '>', $user->last_login_at ?? now()->subDays(7))
            ->exists();

        return view('personnel.dashboard', compact('enrollments', 'hasNewMessages', 'hasNewAssessments'));
    }

    public function instructions()
    {
        $user = auth()->user();
        
        $messages = Message::with('sender')
            ->where(function($query) use ($user) {
                $query->where('recipient_id', $user->id)
                      ->orWhere('department_id', $user->department_id);
            })
            ->latest()
            ->get();

        return view('personnel.instructions', compact('messages'));
    }

    public function courses()
    {
        $user = auth()->user();
        
        $enrollments = Enrollment::where('user_id', $user->id)
            ->with('course')
            ->latest()
            ->paginate(12);

        return view('personnel.courses', compact('enrollments'));
    }
}
