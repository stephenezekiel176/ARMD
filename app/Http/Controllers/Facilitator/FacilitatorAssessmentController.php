<?php

namespace App\Http\Controllers\Facilitator;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FacilitatorAssessmentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Assessment::class, 'assessment');
    }

    public function index()
    {
        $assessments = Assessment::where('facilitator_id', Auth::id())
            ->with(['course', 'department'])
            ->latest()
            ->paginate(10);

        return view('facilitator.assessments.index', compact('assessments'));
    }

    public function create()
    {
        $courses = Course::where('department_id', Auth::user()->department_id)->get();
        return view('facilitator.assessments.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['quiz', 'exam', 'assignment'])],
            'course_id' => ['required', 'exists:courses,id'],
            'questions' => ['required', 'array'],
            'questions.*.question' => ['required', 'string'],
            'questions.*.type' => ['required', Rule::in(['multiple_choice', 'essay', 'file_upload'])],
            'questions.*.options' => ['required_if:questions.*.type,multiple_choice', 'array'],
            'questions.*.correct_answer' => ['required_if:questions.*.type,multiple_choice', 'string'],
            'due_date' => ['required', 'date', 'after:today'],
        ]);

        $validated['facilitator_id'] = Auth::id();
        $validated['department_id'] = Auth::user()->department_id;

        $assessment = Assessment::create($validated);

        return redirect()->route('facilitator.assessments.show', $assessment)
            ->with('success', 'Assessment created successfully.');
    }

    public function show(Assessment $assessment)
    {
        $assessment->load(['course', 'department', 'submissions']);
        return view('facilitator.assessments.show', compact('assessment'));
    }

    public function edit(Assessment $assessment)
    {
        $courses = Course::where('department_id', Auth::user()->department_id)->get();
        return view('facilitator.assessments.edit', compact('assessment', 'courses'));
    }

    public function update(Request $request, Assessment $assessment)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(['quiz', 'exam', 'assignment'])],
            'course_id' => ['required', 'exists:courses,id'],
            'questions' => ['required', 'array'],
            'questions.*.question' => ['required', 'string'],
            'questions.*.type' => ['required', Rule::in(['multiple_choice', 'essay', 'file_upload'])],
            'questions.*.options' => ['required_if:questions.*.type,multiple_choice', 'array'],
            'questions.*.correct_answer' => ['required_if:questions.*.type,multiple_choice', 'string'],
            'due_date' => ['required', 'date', 'after:today'],
        ]);

        $assessment->update($validated);

        return redirect()->route('facilitator.assessments.show', $assessment)
            ->with('success', 'Assessment updated successfully.');
    }

    public function destroy(Assessment $assessment)
    {
        $assessment->delete();

        return redirect()->route('facilitator.assessments.index')
            ->with('success', 'Assessment deleted successfully.');
    }
}
