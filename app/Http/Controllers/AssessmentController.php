<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assessment;
use App\Models\Submission;
use App\Models\User;
use App\Events\AssessmentGraded;

class AssessmentController extends Controller
{
    public function index()
    {
        // Get all assessments created by this facilitator
        $assessments = Assessment::where('facilitator_id', auth()->id())
            ->with(['course', 'submissions'])
            ->withCount(['submissions' => function($query) {
                $query->whereNotNull('graded_at');
            }])
            ->latest()
            ->paginate(12);

        return view('facilitator.assessments.index', compact('assessments'));
    }

    public function create()
    {
        return view('facilitator.assessments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:quiz,assignment,practical,mixed',
            'course_id' => 'required|exists:courses,id',
            'department_id' => 'required|exists:departments,id',
            'instructions' => 'nullable|string',
            'due_date' => 'nullable|date',
            'questions' => 'required|array|min:1',
            'questions.*.text' => 'required|string',
            'questions.*.type' => 'required|in:text,multiple_choice,video,image,audio,file',
            'questions.*.points' => 'required|integer|min:1',
            'questions.*.options' => 'array',
            'questions.*.answer' => 'nullable|string',
        ]);

        $user = auth()->user();
        
        // Process questions data
        $questionsData = [];
        foreach ($request->questions as $question) {
            $questionsData[] = [
                'text' => $question['text'],
                'type' => $question['type'],
                'points' => $question['points'],
                'options' => $question['options'] ?? null,
                'answer' => $question['answer'] ?? null,
            ];
        }

        $assessment = Assessment::create([
            'title' => $request->title,
            'type' => $request->type,
            'course_id' => $request->course_id,
            'department_id' => $user->department_id,
            'facilitator_id' => $user->id,
            'instructions' => $request->instructions,
            'questions' => json_encode($questionsData),
            'due_date' => $request->due_date,
        ]);

        // Notify admin about new assessment creation
        $this->notifyAdminAssessmentCreated($assessment, $user);

        return redirect()->route('facilitator.assessments.index')->with('success', 'Assessment created successfully and admin notified.');
    }

    public function show(Assessment $assessment)
    {
        // Ensure the assessment belongs to the facilitator
        if ($assessment->facilitator_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this assessment.');
        }

        $assessment->load(['course', 'submissions' => function($query) {
            $query->with('user')->latest();
        }]);

        return view('facilitator.assessments.show', compact('assessment'));
    }

    public function edit(Assessment $assessment)
    {
        // Ensure the assessment belongs to the facilitator
        if ($assessment->facilitator_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this assessment.');
        }

        $assessment->load('course');

        return view('facilitator.assessments.edit', compact('assessment'));
    }

    public function update(Request $request, Assessment $assessment)
    {
        // Ensure the assessment belongs to the facilitator
        if ($assessment->facilitator_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this assessment.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:quiz,assignment',
            'course_id' => 'required|exists:courses,id',
            'questions' => 'required|json',
            'due_date' => 'nullable|date',
        ]);

        $assessment->update([
            'title' => $request->title,
            'type' => $request->type,
            'course_id' => $request->course_id,
            'questions' => $request->questions,
            'due_date' => $request->due_date,
        ]);

        return redirect()->route('facilitator.assessments.index')->with('success', 'Assessment updated.');
    }

    public function destroy(Assessment $assessment)
    {
        // Ensure the assessment belongs to the facilitator
        if ($assessment->facilitator_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this assessment.');
        }

        $assessment->delete();

        return redirect()->route('facilitator.assessments.index')->with('success', 'Assessment deleted.');
    }

    public function submissions()
    {
        // Get all submissions for assessments created by this facilitator
        $submissions = Submission::whereHas('assessment', function($query) {
            $query->where('facilitator_id', auth()->id());
        })
        ->with(['assessment', 'user'])
        ->latest()
        ->paginate(10);

        return view('facilitator.assessments.submissions', compact('submissions'));
    }

    // Grade a submission (Facilitator only)
    public function grade(Request $request, Submission $submission)
    {
        $request->validate([
            'score' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $submission->score = $request->score;
        $submission->graded_at = now();
        $submission->feedback = $request->feedback;
        $submission->save();

        // Calculate percentage and store detailed grading info
        $percentage = $request->score;
        $gradingData = [
            'submission_id' => $submission->id,
            'assessment_title' => $submission->assessment->title,
            'personnel_name' => $submission->user->fullname,
            'personnel_email' => $submission->user->email,
            'personnel_id' => $submission->user->id,
            'facilitator_name' => auth()->user()->fullname,
            'facilitator_id' => auth()->id(),
            'score' => $request->score,
            'percentage' => $percentage . '%',
            'feedback' => $request->feedback,
            'graded_at' => now()->toISOString(),
            'submitted_at' => $submission->created_at->toISOString(),
            'assessment_type' => $submission->assessment->type,
            'course_title' => $submission->assessment->course->title,
        ];

        // Store grading info in personnel's admin folder
        $this->storeGradingInPersonnelFolder($submission->user, $gradingData);

        // Award points to the user equal to the score (as requested)
        $submission->user->awardPoints((int)$submission->score);

        // Notify admin about graded submission
        $this->notifyAdminSubmissionGraded($submission);

        // Broadcast event to the user
        event(new AssessmentGraded($submission));

        return back()->with('success', 'Submission graded with ' . $percentage . '% and stored in personnel records.');
    }

    /**
     * Store grading information in personnel's admin folder
     */
    private function storeGradingInPersonnelFolder($user, $gradingData)
    {
        $folderPath = 'admin-notifications/personnel/' . $user->id;
        $filename = 'assessment_' . $gradingData['submission_id'] . '_' . now()->format('Y-m-d_H-i-s') . '.json';
        
        $this->storeAdminNotification($folderPath, $filename, $gradingData);

        // Update personnel's running averages
        $this->updatePersonnelAverages($user, $gradingData);
    }

    /**
     * Update personnel's weekly and monthly averages
     */
    private function updatePersonnelAverages($user, $gradingData)
    {
        $folderPath = 'admin-notifications/personnel/' . $user->id;
        $averagesFile = $folderPath . '/averages.json';
        
        // Load existing averages or create new
        $averages = [];
        if (Storage::exists($averagesFile)) {
            $averages = json_decode(Storage::get($averagesFile), true);
        }

        $currentDate = now();
        $weekKey = $currentDate->format('Y-W'); // Year-Week
        $monthKey = $currentDate->format('Y-m'); // Year-Month

        // Initialize arrays if needed
        if (!isset($averages['weekly'])) $averages['weekly'] = [];
        if (!isset($averages['monthly'])) $averages['monthly'] = [];

        // Get all assessments for this user to calculate averages
        $allAssessments = Submission::where('user_id', $user->id)
            ->whereNotNull('score')
            ->where('graded_at', '>=', $currentDate->copy()->startOfWeek())
            ->where('graded_at', '<=', $currentDate->copy()->endOfWeek())
            ->pluck('score')
            ->toArray();

        // Calculate weekly average
        if (!empty($allAssessments)) {
            $averages['weekly'][$weekKey] = [
                'week' => $weekKey,
                'average' => round(array_sum($allAssessments) / count($allAssessments), 2),
                'total_assessments' => count($allAssessments),
                'period' => $currentDate->copy()->startOfWeek()->format('M j') . ' - ' . 
                           $currentDate->copy()->endOfWeek()->format('M j, Y'),
            ];
        }

        // Calculate monthly average
        $monthlyAssessments = Submission::where('user_id', $user->id)
            ->whereNotNull('score')
            ->where('graded_at', '>=', $currentDate->copy()->startOfMonth())
            ->where('graded_at', '<=', $currentDate->copy()->endOfMonth())
            ->pluck('score')
            ->toArray();

        if (!empty($monthlyAssessments)) {
            $averages['monthly'][$monthKey] = [
                'month' => $monthKey,
                'average' => round(array_sum($monthlyAssessments) / count($monthlyAssessments), 2),
                'total_assessments' => count($monthlyAssessments),
                'period' => $currentDate->format('F Y'),
            ];
        }

        // Save updated averages
        Storage::put($averagesFile, json_encode($averages, JSON_PRETTY_PRINT));
    }

    /**
     * Notify admin about new assessment creation
     */
    private function notifyAdminAssessmentCreated(Assessment $assessment, User $facilitator)
    {
        // Create admin notification
        $adminNotification = [
            'type' => 'assessment_created',
            'title' => 'New Assessment Created',
            'message' => "{$facilitator->fullname} created a new assessment: {$assessment->title}",
            'data' => [
                'assessment_id' => $assessment->id,
                'facilitator_id' => $facilitator->id,
                'facilitator_name' => $facilitator->fullname,
                'assessment_title' => $assessment->title,
                'department' => $facilitator->department->name ?? 'Unknown',
                'course' => $assessment->course->title ?? 'Unknown',
                'created_at' => $assessment->created_at->toISOString(),
            ],
        ];

        // Store notification in admin notifications table or file system
        $this->storeAdminNotification($adminNotification, 'assessments/' . $facilitator->id);
    }

    /**
     * Notify admin about submission grading
     */
    private function notifyAdminSubmissionGraded(Submission $submission)
    {
        $adminNotification = [
            'type' => 'submission_graded',
            'title' => 'Assessment Submission Graded',
            'message' => "{$submission->user->fullname}'s submission for '{$submission->assessment->title}' has been graded with score: {$submission->score}",
            'data' => [
                'submission_id' => $submission->id,
                'assessment_id' => $submission->assessment->id,
                'user_id' => $submission->user->id,
                'user_name' => $submission->user->fullname,
                'assessment_title' => $submission->assessment->title,
                'score' => $submission->score,
                'graded_at' => $submission->graded_at->toISOString(),
                'facilitator_id' => $submission->assessment->facilitator_id,
            ],
        ];

        // Store notification in user folder
        $this->storeAdminNotification($adminNotification, 'users/' . $submission->user->id);
    }

    /**
     * Store admin notification in file system
     */
    private function storeAdminNotification(array $notification, string $folder)
    {
        try {
            $storagePath = storage_path('app/admin-notifications/' . $folder);
            
            // Create directory if it doesn't exist
            if (!is_dir($storagePath)) {
                mkdir($storagePath, 0755, true);
            }

            // Create notification file with timestamp
            $filename = $notification['type'] . '_' . time() . '_' . uniqid() . '.json';
            $filepath = $storagePath . '/' . $filename;
            
            // Add metadata
            $notification['timestamp'] = now()->toISOString();
            $notification['id'] = uniqid();
            
            file_put_contents($filepath, json_encode($notification, JSON_PRETTY_PRINT));
            
        } catch (\Exception $e) {
            // Log error but don't fail the main operation
            \Log::error('Failed to store admin notification: ' . $e->getMessage());
        }
    }
}
