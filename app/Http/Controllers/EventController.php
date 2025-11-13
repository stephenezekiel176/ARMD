<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\CalendarEvent;
use App\Models\Reminder;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function meetings()
    {
        $upcomingMeetings = Meeting::upcoming()
            ->with(['organizer', 'department'])
            ->latest('meeting_date')
            ->get();

        $todayMeetings = Meeting::today()
            ->with(['organizer', 'department'])
            ->get();

        return view('pages.events.meetings', compact('upcomingMeetings', 'todayMeetings'));
    }

    public function calendar(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $events = CalendarEvent::with(['creator', 'department'])
            ->whereYear('event_date', $year)
            ->whereMonth('event_date', $month)
            ->orderBy('event_date')
            ->get();

        return view('pages.events.calendar', compact('events', 'month', 'year'));
    }

    public function calendarJson(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');

        $events = CalendarEvent::whereBetween('event_date', [$start, $end])->get();

        return response()->json($events->map(function($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->event_date->format('Y-m-d'),
                'end' => $event->event_date->format('Y-m-d'),
                'color' => $event->color,
                'description' => $event->description,
            ];
        }));
    }

    public function reminders()
    {
        $upcomingReminders = Reminder::pending()
            ->where('remind_at', '>=', now())
            ->orderBy('remind_at')
            ->limit(20)
            ->get();

        return view('pages.events.reminders', compact('upcomingReminders'));
    }
}
