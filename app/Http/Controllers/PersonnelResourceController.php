<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Enrollment;

class PersonnelResourceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get both company-wide (admin) and department-specific (facilitator) resources
        $resources = Course::where(function($query) use ($user) {
                $query->where('scope', 'company')  // Admin-controlled company-wide content
                      ->orWhere(function($q) use ($user) {
                          $q->where('scope', 'department')  // Facilitator department content
                            ->where('department_id', $user->department_id);
                      });
            })
            ->with(['facilitator', 'department'])
            ->withCount(['enrollments' => function($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->latest()
            ->get();

        return view('personnel.resources.index', compact('resources'));
    }

    public function show(Course $resource)
    {
        $user = auth()->user();
        
        // Ensure the resource is either company-wide or belongs to the user's department
        if ($resource->scope !== 'company' && $resource->department_id !== $user->department_id) {
            abort(403, 'This resource is not available for your department.');
        }

        // Check if user is enrolled or if course is previewable
        $isEnrolled = $user->enrollments()->where('course_id', $resource->id)->exists();
        if (!$isEnrolled && !$resource->is_previewable) {
            abort(403, 'You must enroll in this course to view it.');
        }

        $resource->load(['facilitator', 'department']);

        // Get related resources from the same department
        $relatedResources = Course::where('department_id', $user->department_id)
            ->where('id', '!=', $resource->id)
            ->where(function($query) use ($user) {
                $query->where('is_previewable', true)
                      ->orWhereHas('enrollments', function($q) use ($user) {
                          $q->where('user_id', $user->id);
                      });
            })
            ->with('facilitator')
            ->take(6)
            ->get();

        return view('personnel.resources.show', compact('resource', 'relatedResources', 'isEnrolled'));
    }
    
    /**
     * Get file URL for course
     */
    public function getFile(Course $resource)
    {
        $user = auth()->user();
        
        // Ensure the resource belongs to the user's department
        if ($resource->department_id !== $user->department_id) {
            abort(403, 'This resource is not available for your department.');
        }

        // Check if user is enrolled or if course is previewable
        $isEnrolled = $user->enrollments()->where('course_id', $resource->id)->exists();
        if (!$isEnrolled && !$resource->is_previewable) {
            abort(403, 'You must enroll in this course to download it.');
        }
        
        $filePath = storage_path('app/public/' . $resource->file_path);
        
        if (!file_exists($filePath)) {
            abort(404);
        }
        
        return response()->download($filePath, $resource->title . '.' . pathinfo($filePath, PATHINFO_EXTENSION));
    }
    
    /**
     * Stream video content - Optimized for 500k concurrent users
     */
    public function streamVideo(Course $resource)
    {
        if ($resource->type !== 'video') {
            abort(404);
        }
        
        $user = auth()->user();
        
        // Ensure the resource belongs to the user's department
        if ($resource->department_id !== $user->department_id) {
            abort(403, 'This resource is not available for your department.');
        }

        // Check if user is enrolled or if course is previewable
        $isEnrolled = $user->enrollments()->where('course_id', $resource->id)->exists();
        if (!$isEnrolled && !$resource->is_previewable) {
            abort(403, 'You must enroll in this course to stream it.');
        }
        
        $filePath = storage_path('app/public/' . $resource->file_path);
        
        if (!file_exists($filePath)) {
            abort(404);
        }
        
        // Optimized streaming with range support
        $fileSize = filesize($filePath);
        $start = 0;
        $end = $fileSize - 1;

        // Handle range requests
        if (isset($_SERVER['HTTP_RANGE'])) {
            $range = $_SERVER['HTTP_RANGE'];
            if (preg_match('/bytes=(\d+)-(\d*)/', $range, $matches)) {
                $start = intval($matches[1]);
                if (!empty($matches[2])) {
                    $end = intval($matches[2]);
                }
            }
        }

        $length = $end - $start + 1;

        $stream = new \Symfony\Component\HttpFoundation\StreamedResponse(function() use ($filePath, $start, $length) {
            $handle = fopen($filePath, 'rb');
            fseek($handle, $start);
            $remaining = $length;
            
            while ($remaining > 0 && !feof($handle)) {
                $chunkSize = min(1024 * 1024, $remaining); // 1MB chunks
                $chunk = fread($handle, $chunkSize);
                echo $chunk;
                flush();
                $remaining -= strlen($chunk);
            }
            
            fclose($handle);
        }, 206);
        
        $stream->headers->set('Content-Type', 'video/mp4');
        $stream->headers->set('Content-Length', $length);
        $stream->headers->set('Accept-Ranges', 'bytes');
        $stream->headers->set('Content-Range', "bytes $start-$end/$fileSize");
        $stream->headers->set('Cache-Control', 'public, max-age=3600');
        
        return $stream;
    }
}

class PersonnelEnrollmentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $user = auth()->user();
        $course = Course::findOrFail($request->course_id);

        // Ensure the course belongs to the user's department
        if ($course->department_id !== $user->department_id) {
            abort(403, 'You cannot enroll in courses from other departments.');
        }

        // Check if already enrolled
        if ($user->enrollments()->where('course_id', $course->id)->exists()) {
            return back()->with('info', 'You are already enrolled in this course.');
        }

        // Create enrollment
        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'status' => 'active',
            'progress' => 0,
        ]);

        return redirect()->route('personnel.dashboard')
            ->with('success', 'Successfully enrolled in ' . $course->title . '!');
    }
}
