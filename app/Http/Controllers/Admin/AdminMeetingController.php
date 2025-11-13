<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminMeetingController extends Controller
{
    public function index(Request $request)
    {
        $query = Meeting::with(['organizer', 'department'])->latest('meeting_date');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $meetings = $query->paginate(20);
        $departments = Department::all();

        return view('admin.meetings.index', compact('meetings', 'departments'));
    }

    public function create()
    {
        $departments = Department::all();
        $users = User::all();
        return view('admin.meetings.create', compact('departments', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'agenda' => 'nullable|string',
            'meeting_date' => 'required|date',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'location' => 'nullable|string|max:255',
            'meeting_link' => 'nullable|url',
            'type' => 'required|in:team,department,company_wide,board,other',
            'department_id' => 'nullable|exists:departments,id',
            'participants' => 'nullable|array',
            'attachments.*' => 'nullable|file|max:10240',
            'status' => 'required|in:scheduled,ongoing,completed,cancelled,postponed',
        ]);

        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $attachments[] = [
                    'path' => $file->store('meetings/attachments', 'public'),
                    'name' => $file->getClientOriginalName(),
                ];
            }
            $validated['attachments'] = $attachments;
        }

        $validated['organizer_id'] = auth()->id();

        Meeting::create($validated);

        return redirect()->route('admin.meetings.index')->with('success', 'Meeting created successfully.');
    }

    public function edit(Meeting $meeting)
    {
        $departments = Department::all();
        $users = User::all();
        return view('admin.meetings.edit', compact('meeting', 'departments', 'users'));
    }

    public function update(Request $request, Meeting $meeting)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'agenda' => 'nullable|string',
            'meeting_date' => 'required|date',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'location' => 'nullable|string|max:255',
            'meeting_link' => 'nullable|url',
            'type' => 'required|in:team,department,company_wide,board,other',
            'department_id' => 'nullable|exists:departments,id',
            'participants' => 'nullable|array',
            'minutes' => 'nullable|array',
            'attachments.*' => 'nullable|file|max:10240',
            'status' => 'required|in:scheduled,ongoing,completed,cancelled,postponed',
        ]);

        if ($request->hasFile('attachments')) {
            $attachments = $meeting->attachments ?? [];
            foreach ($request->file('attachments') as $file) {
                $attachments[] = [
                    'path' => $file->store('meetings/attachments', 'public'),
                    'name' => $file->getClientOriginalName(),
                ];
            }
            $validated['attachments'] = $attachments;
        }

        $meeting->update($validated);

        return redirect()->route('admin.meetings.index')->with('success', 'Meeting updated successfully.');
    }

    public function destroy(Meeting $meeting)
    {
        if ($meeting->attachments) {
            foreach ($meeting->attachments as $attachment) {
                Storage::disk('public')->delete($attachment['path']);
            }
        }

        $meeting->delete();

        return redirect()->route('admin.meetings.index')->with('success', 'Meeting deleted successfully.');
    }
}
