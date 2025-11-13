<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Enrollment;

class ResourceController extends Controller
{
    public function index()
    {
        $resources = Course::with(['department','facilitator'])
            ->withCount(['enrollments' => function($query) {
                if (auth()->check()) {
                    $query->where('user_id', auth()->id());
                }
            }])
            ->latest()
            ->paginate(12);

        return view('resources.index', compact('resources'));
    }
    
    public function types()
    {
        return view('resource-types.index');
    }

    // Show course details
    public function show(Course $resource)
    {
        $resource->load(['department', 'facilitator']);
        
        // Check if user is enrolled or if course is previewable
        $isEnrolled = false;
        $canAccess = false;
        
        if (auth()->check()) {
            $isEnrolled = auth()->user()->enrollments()->where('course_id', $resource->id)->exists();
            $canAccess = $isEnrolled || $resource->is_previewable;
        } else {
            $canAccess = $resource->is_previewable;
        }
        
        if (!$canAccess) {
            return redirect()->route('resources.index')
                ->with('error', 'You need to enroll in this course to view it. Please login or enroll first.');
        }
        
        return view('resources.show', compact('resource', 'isEnrolled'));
    }

    // Enroll the authenticated user into a course
    public function enroll(Request $request, Course $resource)
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to enroll in courses.');
        }
        
        $user = auth()->user();
        
        // Check if user is already enrolled
        if ($user->enrollments()->where('course_id', $resource->id)->exists()) {
            return redirect()->back()->with('info', 'You are already enrolled in this course.');
        }
        
        // Create enrollment
        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $resource->id,
            'status' => 'active',
            'progress' => 0,
            'enrolled_at' => now(),
        ]);

        // Award points for enrolling
        if (method_exists($user, 'awardPoints')) {
            $user->awardPoints(10);
        }

        return redirect()->back()->with('success', 'Successfully enrolled in ' . $resource->title . '!');
    }
    
    /**
     * Get file URL for course
     */
    public function getFile(Course $resource)
    {
        // Check if user is enrolled or if course is previewable
        $canAccess = false;
        
        if (auth()->check()) {
            $isEnrolled = auth()->user()->enrollments()->where('course_id', $resource->id)->exists();
            $canAccess = $isEnrolled || $resource->is_previewable;
        } else {
            $canAccess = $resource->is_previewable;
        }
        
        if (!$canAccess) {
            abort(403, 'You need to enroll in this course to access the file.');
        }
        
        $filePath = storage_path('app/public/' . $resource->file_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }
        
        return response()->download($filePath, $resource->title . '.' . pathinfo($filePath, PATHINFO_EXTENSION));
    }
}
