<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Submission;
use Illuminate\Support\Facades\Storage;

class AdminPersonnelPerformanceController extends Controller
{
    public function index()
    {
        // Get all personnel with their assessment statistics
        $personnel = User::where('role', 'personnel')
            ->with(['department', 'submissions' => function($query) {
                $query->whereNotNull('score')->whereNotNull('graded_at');
            }])
            ->get()
            ->map(function($user) {
                $gradedSubmissions = $user->submissions;
                $totalAssessments = $gradedSubmissions->count();
                $averageScore = $totalAssessments > 0 ? round($gradedSubmissions->avg('score'), 2) : 0;
                
                // Get latest performance data from storage
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
                    // File doesn't exist or is corrupted
                }
                
                return [
                    'id' => $user->id,
                    'fullname' => $user->fullname,
                    'email' => $user->email,
                    'department' => $user->department->name ?? 'N/A',
                    'total_assessments' => $totalAssessments,
                    'average_score' => $averageScore,
                    'latest_weekly_avg' => !empty($weeklyAverages) ? end($weeklyAverages)['average'] : 0,
                    'latest_monthly_avg' => !empty($monthlyAverages) ? end($monthlyAverages)['average'] : 0,
                    'weekly_trend' => $this->calculateTrend($weeklyAverages),
                    'monthly_trend' => $this->calculateTrend($monthlyAverages),
                    'last_submission' => $gradedSubmissions->first() ? $gradedSubmissions->first()->graded_at : null,
                ];
            })
            ->sortByDesc('average_score')
            ->values();

        return view('admin.personnel-performance.index', compact('personnel'));
    }

    public function show(User $user)
    {
        if ($user->role !== 'personnel') {
            abort(404);
        }

        // Get detailed performance data
        $gradedSubmissions = Submission::where('user_id', $user->id)
            ->whereNotNull('score')
            ->whereNotNull('graded_at')
            ->with(['assessment', 'assessment.course', 'assessment.course.facilitator'])
            ->orderBy('graded_at', 'desc')
            ->get();

        // Get performance averages from storage
        $weeklyAverages = [];
        $monthlyAverages = [];
        $assessmentHistory = [];
        
        try {
            $folderPath = 'admin-notifications/personnel/' . $user->id;
            
            // Get averages
            $averagesFile = $folderPath . '/averages.json';
            if (Storage::exists($averagesFile)) {
                $averages = json_decode(Storage::get($averagesFile), true);
                $weeklyAverages = $averages['weekly'] ?? [];
                $monthlyAverages = $averages['monthly'] ?? [];
            }
            
            // Get individual assessment records
            $files = Storage::files($folderPath);
            foreach ($files as $file) {
                if (str_contains($file, 'assessment_') && str_ends_with($file, '.json')) {
                    $assessmentData = json_decode(Storage::get($file), true);
                    $assessmentHistory[] = $assessmentData;
                }
            }
            
        } catch (\Exception $e) {
            // Handle file errors
        }

        $totalAssessments = $gradedSubmissions->count();
        $averageScore = $totalAssessments > 0 ? round($gradedSubmissions->avg('score'), 2) : 0;

        return view('admin.personnel-performance.show', compact(
            'user',
            'gradedSubmissions',
            'totalAssessments',
            'averageScore',
            'weeklyAverages',
            'monthlyAverages',
            'assessmentHistory'
        ));
    }

    private function calculateTrend($averages)
    {
        if (count($averages) < 2) {
            return 'stable';
        }

        $recent = array_slice(array_values($averages), -2);
        $previous = $recent[0]['average'] ?? 0;
        $current = $recent[1]['average'] ?? 0;

        if ($current > $previous + 2) {
            return 'improving';
        } elseif ($current < $previous - 2) {
            return 'declining';
        } else {
            return 'stable';
        }
    }
}
