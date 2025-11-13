<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Services\ImageOptimizationService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FacilitatorCourseController extends Controller
{
    protected ImageOptimizationService $imageService;
    
    public function __construct(ImageOptimizationService $imageService)
    {
        $this->imageService = $imageService;
    }
    
    /**
     * Display a listing of facilitator's courses
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $format = $request->get('format');
        
        $query = Course::where('facilitator_id', $user->id)
            ->where('department_id', $user->department_id)
            ->with(['department', 'enrollments' => function($query) {
                $query->select('course_id', 'status');
            }])
            ->withCount(['enrollments' => function($query) {
                $query->where('status', 'active');
            }]);
            
        // Filter by format if specified
        if ($format) {
            $query->where('type', $format);
        }
        
        $courses = $query->latest()->paginate(12);
        
        // Add format info for view
        $formatLabels = [
            'video' => 'Video Courses',
            'ebook' => 'E-Book Courses', 
            'audio' => 'Audio Courses',
            'image' => 'Image Courses',
            'podcast' => 'Podcast Courses'
        ];
        
        $currentFormat = $format ? ($formatLabels[$format] ?? 'Unknown Format') : null;
            
        return view('facilitator.courses.index', compact('courses', 'format', 'currentFormat'));
    }
    
    /**
     * Show the form for creating a new course
     */
    public function create()
    {
        return view('facilitator.courses.create');
    }
    
    /**
     * Store a newly created course
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,ebook,audio,image,podcast',
            'description' => 'required|string',
            'duration' => 'required|integer|min:1',
            'is_previewable' => 'boolean',
            'file' => [
                'required',
                'file',
                'max:102400', // 100MB max
                function ($attribute, $value, $fail) use ($request) {
                    $type = $request->get('type');
                    $allowedExtensions = [];
                    
                    switch ($type) {
                        case 'video':
                            $allowedExtensions = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm'];
                            break;
                        case 'ebook':
                            $allowedExtensions = ['pdf', 'epub', 'mobi', 'txt'];
                            break;
                        case 'audio':
                            $allowedExtensions = ['mp3', 'wav', 'm4a', 'aac', 'ogg'];
                            break;
                        case 'image':
                            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                            break;
                        case 'podcast':
                            $allowedExtensions = ['mp3', 'wav', 'm4a', 'aac'];
                            break;
                    }
                    
                    if (!in_array(strtolower($value->getClientOriginalExtension()), $allowedExtensions)) {
                        $fail("The {$attribute} must be a valid file type for {$type} courses. Allowed types: " . implode(', ', $allowedExtensions));
                    }
                },
            ],
        ]);
        
        $user = auth()->user();
        
        // Handle file upload
        $file = $request->file('file');
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        
        // Define directory based on course type
        $directory = "courses/{$request->type}s";
        
        // Store file
        $filePath = $file->storeAs($directory, $fileName, 'public');
        
        // Create course
        $course = Course::create([
            'title' => $request->title,
            'type' => $request->type,
            'file_path' => $filePath,
            'description' => $request->description,
            'department_id' => $user->department_id,
            'facilitator_id' => $user->id,
            'duration' => $request->duration,
            'is_previewable' => $request->boolean('is_previewable', false),
        ]);
        
        return redirect()
            ->route('facilitator.courses.index')
            ->with('success', 'Course created successfully!');
    }
    
    /**
     * Display the specified course
     */
    public function show(Course $course)
    {
        // Ensure facilitator can only view their own courses
        if ($course->facilitator_id !== auth()->id() || 
            $course->department_id !== auth()->user()->department_id) {
            abort(403);
        }
        
        $course->load([
            'department',
            'enrollments.user',
            'assessments'
        ]);
        
        return view('facilitator.courses.show', compact('course'));
    }
    
    /**
     * Show the form for editing the specified course
     */
    public function edit(Course $course)
    {
        // Ensure facilitator can only edit their own courses
        if ($course->facilitator_id !== auth()->id() || 
            $course->department_id !== auth()->user()->department_id) {
            abort(403);
        }
        
        return view('facilitator.courses.edit', compact('course'));
    }
    
    /**
     * Update the specified course
     */
    public function update(Request $request, Course $course)
    {
        // Ensure facilitator can only update their own courses
        if ($course->facilitator_id !== auth()->id() || 
            $course->department_id !== auth()->user()->department_id) {
            abort(403);
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,ebook',
            'description' => 'required|string',
            'duration' => 'required|integer|min:1',
            'is_previewable' => 'boolean',
            'file' => 'nullable|file|max:102400', // 100MB max
        ]);
        
        $data = $request->except('file');
        
        // Handle file update if provided
        if ($request->hasFile('file')) {
            // Delete old file
            Storage::disk('public')->delete($course->file_path);
            
            // Upload new file
            $file = $request->file('file');
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $directory = "courses/{$request->type}s";
            $filePath = $file->storeAs($directory, $fileName, 'public');
            
            $data['file_path'] = $filePath;
        }
        
        $data['is_previewable'] = $request->boolean('is_previewable', false);
        
        $course->update($data);
        
        return redirect()
            ->route('facilitator.courses.index')
            ->with('success', 'Course updated successfully!');
    }
    
    /**
     * Remove the specified course
     */
    public function destroy(Course $course)
    {
        // Ensure facilitator can only delete their own courses
        if ($course->facilitator_id !== auth()->id() || 
            $course->department_id !== auth()->user()->department_id) {
            abort(403);
        }
        
        // Check if course has active enrollments
        if ($course->enrollments()->where('status', 'active')->exists()) {
            return back()->with('error', 'Cannot delete course with active enrollments.');
        }
        
        // Delete associated file
        Storage::disk('public')->delete($course->file_path);
        
        // Delete course
        $course->delete();
        
        return redirect()
            ->route('facilitator.courses.index')
            ->with('success', 'Course deleted successfully!');
    }
    
    /**
     * Get file URL for course
     */
    public function getFile(Course $course)
    {
        // Ensure user has access to this course
        $user = auth()->user();
        
        if ($user->role === 'facilitator' && $course->facilitator_id !== $user->id) {
            abort(403);
        }
        
        if ($user->role === 'personnel') {
            $isEnrolled = $course->enrollments()
                ->where('user_id', $user->id)
                ->where('status', 'active')
                ->exists();
                
            if (!$isEnrolled && !$course->is_previewable) {
                abort(403);
            }
        }
        
        $filePath = Storage::disk('public')->path($course->file_path);
        
        if (!file_exists($filePath)) {
            abort(404);
        }
        
        return response()->download($filePath, $course->title . '.' . pathinfo($filePath, PATHINFO_EXTENSION));
    }
    
    /**
     * Stream video content
     */
    public function streamVideo(Course $course)
    {
        if ($course->type !== 'video') {
            abort(404);
        }
        
        // Ensure user has access
        $user = auth()->user();
        
        if ($user->role === 'personnel') {
            $isEnrolled = $course->enrollments()
                ->where('user_id', $user->id)
                ->where('status', 'active')
                ->exists();
                
            if (!$isEnrolled && !$course->is_previewable) {
                abort(403);
            }
        }
        
        $filePath = Storage::disk('public')->path($course->file_path);
        
        if (!file_exists($filePath)) {
            abort(404);
        }
        
        $stream = new \Symfony\Component\HttpFoundation\StreamedResponse(function() use ($filePath) {
            readfile($filePath);
        });
        
        $stream->headers->set('Content-Type', 'video/mp4');
        $stream->headers->set('Content-Length', filesize($filePath));
        $stream->headers->set('Accept-Ranges', 'bytes');
        
        return $stream;
    }
}
