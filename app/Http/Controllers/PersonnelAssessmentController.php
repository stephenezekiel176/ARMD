<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assessment;
use App\Models\Submission;
use Illuminate\Support\Facades\Storage;

class PersonnelAssessmentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get assessments for courses the user is enrolled in
        $assessments = Assessment::whereHas('course', function($query) use ($user) {
                $query->where('department_id', $user->department_id);
            })
            ->whereHas('course.enrollments', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->with(['course', 'course.facilitator'])
            ->latest()
            ->get();

        return view('personnel.assessments.index', compact('assessments'));
    }

    public function show(Assessment $assessment)
    {
        $user = auth()->user();
        
        // Ensure the assessment belongs to user's department and enrolled course
        if (!$assessment->course->enrollments()->where('user_id', $user->id)->exists()) {
            abort(403, 'You can only view assessments for courses you are enrolled in.');
        }

        $assessment->load(['course', 'course.facilitator']);

        return view('personnel.assessments.show', compact('assessment'));
    }

    public function store(Request $request, Assessment $assessment)
    {
        $user = auth()->user();
        
        // Ensure the assessment belongs to user's department and enrolled course
        if (!$assessment->course->enrollments()->where('user_id', $user->id)->exists()) {
            abort(403, 'You can only submit assessments for courses you are enrolled in.');
        }

        // Check if already submitted
        if ($user->submissions()->where('assessment_id', $assessment->id)->exists()) {
            return back()->with('info', 'You have already submitted this assessment.');
        }

        $questions = json_decode($assessment->questions, true);
        $answers = [];
        $filePaths = [];

        // Process each question answer
        foreach ($questions as $index => $question) {
            if (isset($request->answers[$index])) {
                // Text or multiple choice answers
                $answers[$index] = $request->answers[$index];
            } elseif (isset($request->files[$index])) {
                // File upload answers
                $file = $request->files[$index];
                $filename = uniqid() . '_' . $file->getClientOriginalName();
                
                // Validate file based on question type
                $this->validateFile($file, $question['type']);
                
                // Store file
                $path = $file->storeAs('assessment-submissions', $filename, 'public');
                $filePaths[$index] = $path;
                $answers[$index] = 'file:' . $path;
            } else {
                // No answer provided
                $answers[$index] = null;
            }
        }

        // Create submission
        $submission = Submission::create([
            'assessment_id' => $assessment->id,
            'user_id' => $user->id,
            'answer' => json_encode([
                'answers' => $answers,
                'file_paths' => $filePaths
            ]),
            'score' => null,
        ]);

        // Award points for submitting
        $user->awardPoints(20);

        return redirect()->route('personnel.assessments.index')
            ->with('success', 'Assessment submitted successfully! Your facilitator will review it soon.');
    }

    public function results()
    {
        $user = auth()->user();
        
        // Get all graded submissions for the user
        $gradedSubmissions = Submission::where('user_id', $user->id)
            ->whereNotNull('score')
            ->whereNotNull('graded_at')
            ->with(['assessment', 'assessment.course', 'assessment.course.facilitator'])
            ->orderBy('graded_at', 'desc')
            ->get();

        // Calculate statistics
        $totalAssessments = $gradedSubmissions->count();
        $averageScore = $totalAssessments > 0 ? round($gradedSubmissions->avg('score'), 2) : 0;
        
        // Calculate weekly and monthly averages from stored data
        $weeklyAverages = [];
        $monthlyAverages = [];
        
        try {
            $averagesFile = 'admin-notifications/personnel/' . $user->id . '/averages.json';
            if (Storage::exists($averagesFile)) {
                $averages = json_decode(Storage::get($averagesFile), true);
                $weeklyAverages = $averages['weekly'] ?? [];
                $monthlyAverages = $averages['monthly'] ?? [];
            }
        } catch (\Exception $e) {
            // If file doesn't exist or is corrupted, use empty arrays
        }

        return view('personnel.assessments.results', compact(
            'gradedSubmissions', 
            'totalAssessments', 
            'averageScore', 
            'weeklyAverages', 
            'monthlyAverages'
        ));
    }

    private function validateFile($file, $questionType)
    {
        $maxSize = match($questionType) {
            'video' => 102400, // 100MB in KB
            'audio' => 51200,  // 50MB in KB
            'image' => 10240,  // 10MB in KB
            'file' => 25600,   // 25MB in KB
            default => 10240,
        };

        $allowedTypes = match($questionType) {
            'video' => ['video/mp4', 'video/avi', 'video/mov'],
            'audio' => ['audio/mp3', 'audio/wav', 'audio/m4a'],
            'image' => ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'],
            'file' => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            default => [],
        };

        if ($file->getSize() > $maxSize * 1024) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'files' => "File size exceeds maximum allowed size for {$questionType} uploads."
            ]);
        }

        if (!in_array($file->getMimeType(), $allowedTypes)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'files' => "File type not allowed for {$questionType} uploads."
            ]);
        }
    }
}
