<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CalendarEvent;
use App\Models\Department;
use Illuminate\Http\Request;

class AdminCalendarController extends Controller
{
    public function index(Request $request)
    {
        $query = CalendarEvent::with(['creator', 'department'])->latest('event_date');

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $events = $query->paginate(20);
        $departments = Department::all();

        return view('admin.calendar.index', compact('events', 'departments'));
    }

    public function create()
    {
        $departments = Department::all();
        $categories = [
            'Meeting',
            'Workshop',
            'Training',
            'Conference',
            'Seminar',
            'Team Building',
            'Review',
            'Planning',
            'Other'
        ];
        return view('admin.calendar.create', compact('departments', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'all_day' => 'nullable|boolean',
            'event_type' => 'required|in:holiday,company_event,deadline,birthday,anniversary,meeting,workshop,training,other',
            'color' => 'nullable|string|max:7',
            'location' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'participants' => 'nullable|array',
            'is_recurring' => 'nullable|boolean',
            'recurrence_pattern' => 'nullable|string|in:daily,weekly,monthly,yearly',
            'recurrence_end_date' => 'nullable|date|after:event_date',
            'send_reminder' => 'nullable|boolean',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['all_day'] = $request->has('all_day');
        $validated['is_recurring'] = $request->has('is_recurring');
        $validated['send_reminder'] = $request->has('send_reminder');

        CalendarEvent::create($validated);

        return redirect()->route('admin.calendar.index')->with('success', 'Calendar event created successfully.');
    }

    public function edit(CalendarEvent $calendar)
    {
        $departments = Department::all();
        $categories = [
            'Meeting',
            'Workshop',
            'Training',
            'Conference',
            'Seminar',
            'Team Building',
            'Review',
            'Planning',
            'Other'
        ];
        return view('admin.calendar.edit', compact('calendar', 'departments', 'categories'));
    }

    public function update(Request $request, CalendarEvent $calendar)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'all_day' => 'nullable|boolean',
            'event_type' => 'required|in:holiday,company_event,deadline,birthday,anniversary,meeting,workshop,training,other',
            'color' => 'nullable|string|max:7',
            'location' => 'nullable|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'participants' => 'nullable|array',
            'is_recurring' => 'nullable|boolean',
            'recurrence_pattern' => 'nullable|string|in:daily,weekly,monthly,yearly',
            'recurrence_end_date' => 'nullable|date|after:event_date',
            'send_reminder' => 'nullable|boolean',
        ]);

        $validated['all_day'] = $request->has('all_day');
        $validated['is_recurring'] = $request->has('is_recurring');
        $validated['send_reminder'] = $request->has('send_reminder');

        $calendar->update($validated);

        return redirect()->route('admin.calendar.index')->with('success', 'Calendar event updated successfully.');
    }

    public function destroy(CalendarEvent $calendar)
    {
        $calendar->delete();

        return redirect()->route('admin.calendar.index')->with('success', 'Calendar event deleted successfully.');
    }

    public function getEvents(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');

        $events = CalendarEvent::whereBetween('event_date', [$start, $end])->get();

        return response()->json($events->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->all_day ? $event->event_date->format('Y-m-d') : $event->event_date->format('Y-m-d') . 'T' . $event->start_time,
                'end' => $event->all_day ? null : ($event->end_time ? $event->event_date->format('Y-m-d') . 'T' . $event->end_time : null),
                'allDay' => $event->all_day,
                'backgroundColor' => $event->color,
                'borderColor' => $event->color,
            ];
        }));
    }
}
