<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class AdminResourceController extends Controller
{
    public function index()
    {
        $resources = Course::with('department', 'facilitator')->latest()->paginate(20);
        return view('admin.resources.index', compact('resources'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();
        $facilitators = User::where('role', 'facilitator')->orderBy('fullname')->get();
        return view('admin.resources.create', compact('departments', 'facilitators'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,ebook',
            'file' => 'required|file|max:102400', // 100MB max
            'description' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'facilitator_id' => 'required|exists:users,id',
            'duration' => 'required|integer|min:1',
            'is_previewable' => 'boolean',
        ]);

        if ($request->hasFile('file')) {
            $validated['file_path'] = $request->file('file')->store('courses', 'public');
        }

        $validated['is_previewable'] = $request->has('is_previewable');

        Course::create($validated);

        return redirect()->route('admin.resources.index')->with('success', 'Company training created successfully.');
    }

    public function edit(Course $resource)
    {
        $departments = Department::orderBy('name')->get();
        $facilitators = User::where('role', 'facilitator')->orderBy('fullname')->get();
        return view('admin.resources.edit', compact('resource', 'departments', 'facilitators'));
    }

    public function update(Request $request, Course $resource)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,ebook',
            'file' => 'nullable|file|max:102400',
            'description' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'facilitator_id' => 'required|exists:users,id',
            'duration' => 'required|integer|min:1',
            'is_previewable' => 'boolean',
        ]);

        if ($request->hasFile('file')) {
            // Delete old file
            if ($resource->file_path) {
                Storage::disk('public')->delete($resource->file_path);
            }
            $validated['file_path'] = $request->file('file')->store('courses', 'public');
        }

        $validated['is_previewable'] = $request->has('is_previewable');

        $resource->update($validated);

        return redirect()->route('admin.resources.index')->with('success', 'Company training updated successfully.');
    }

    public function destroy(Course $resource)
    {
        // Delete associated file
        if ($resource->file_path) {
            Storage::disk('public')->delete($resource->file_path);
        }

        $resource->delete();

        return redirect()->route('admin.resources.index')->with('success', 'Company training deleted successfully.');
    }
}
