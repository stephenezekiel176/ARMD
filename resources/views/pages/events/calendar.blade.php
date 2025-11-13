@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Company Calendar</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-300">Important dates and upcoming events</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Calendar View -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <div id="calendar" class="calendar-container"></div>
                </div>
            </div>

            <!-- Upcoming Events Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Upcoming Events</h2>
                    
                    @php
                        $upcomingEvents = $events->where('event_date', '>=', now())->take(5);
                    @endphp

                    @forelse($upcomingEvents as $event)
                        <div class="mb-4 pb-4 border-b border-gray-200 dark:border-gray-700 last:border-0 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 p-3 rounded-lg transition" 
                             onclick="showEventDetails({{ json_encode($event) }})">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 rounded-lg flex items-center justify-center text-white" 
                                         style="background-color: {{ $event->color ?? '#3B82F6' }}">
                                        <span class="text-xs font-bold">{{ $event->event_date->format('d') }}</span>
                                    </div>
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $event->title }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $event->event_date->format('M d, Y') }}
                                        @if($event->start_time)
                                            at {{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }}
                                        @endif
                                    </p>
                                    @if($event->location)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            📍 {{ $event->location }}
                                        </p>
                                    @endif
                                    <span class="inline-block mt-2 px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        {{ ucfirst($event->event_type) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No upcoming events</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Event Details Modal -->
<div id="eventModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" onclick="closeModal(event)">
    <div class="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <h3 id="modalTitle" class="text-2xl font-bold text-gray-900 dark:text-white"></h3>
                    <p id="modalDate" class="text-sm text-gray-600 dark:text-gray-400 mt-1"></p>
                </div>
                <button onclick="closeEventModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <div class="p-6 space-y-4">
            <div id="modalType"></div>
            <div id="modalLocation"></div>
            <div id="modalDepartment"></div>
            
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Event Details</h4>
                <div id="modalDescription" class="text-gray-700 dark:text-gray-300 prose dark:prose-invert max-w-none"></div>
            </div>
        </div>
    </div>
</div>

<style>
.calendar-container {
    min-height: 600px;
}

/* Calendar styling */
.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 1px;
    background: #e5e7eb;
}

.calendar-day-header {
    background: #f3f4f6;
    padding: 12px;
    text-align: center;
    font-weight: 600;
    font-size: 0.875rem;
}

.dark .calendar-day-header {
    background: #374151;
}

.calendar-day {
    background: white;
    min-height: 100px;
    padding: 8px;
    cursor: pointer;
    transition: all 0.2s;
}

.dark .calendar-day {
    background: #1f2937;
}

.calendar-day:hover {
    background: #f9fafb;
}

.dark .calendar-day:hover {
    background: #374151;
}

.calendar-day.other-month {
    opacity: 0.4;
}

.calendar-day.has-event {
    border-left: 4px solid #3B82F6;
}

.calendar-event {
    font-size: 0.75rem;
    padding: 2px 6px;
    border-radius: 4px;
    margin-top: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    cursor: pointer;
}
</style>

<script>
const events = @json($events);
const currentMonth = {{ $month }};
const currentYear = {{ $year }};

function renderCalendar() {
    const calendarContainer = document.getElementById('calendar');
    
    // Calendar header
    const header = `
        <div class="flex justify-between items-center mb-6">
            <button onclick="changeMonth(-1)" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                ← Previous
            </button>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                ${getMonthName(currentMonth)} ${currentYear}
            </h2>
            <button onclick="changeMonth(1)" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">
                Next →
            </button>
        </div>
    `;
    
    const daysInMonth = new Date(currentYear, currentMonth, 0).getDate();
    const firstDay = new Date(currentYear, currentMonth - 1, 1).getDay();
    
    let calendarHTML = header + '<div class="calendar-grid">';
    
    // Day headers
    ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'].forEach(day => {
        calendarHTML += `<div class="calendar-day-header">${day}</div>`;
    });
    
    // Empty cells before first day
    for (let i = 0; i < firstDay; i++) {
        calendarHTML += '<div class="calendar-day other-month"></div>';
    }
    
    // Days of month
    for (let day = 1; day <= daysInMonth; day++) {
        const dateStr = `${currentYear}-${String(currentMonth).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const dayEvents = events.filter(e => e.event_date.startsWith(dateStr));
        const hasEvent = dayEvents.length > 0;
        
        calendarHTML += `
            <div class="calendar-day ${hasEvent ? 'has-event' : ''}" onclick="showDayEvents('${dateStr}')">
                <div class="font-semibold text-gray-900 dark:text-white mb-2">${day}</div>
                ${dayEvents.map(event => `
                    <div class="calendar-event" style="background-color: ${event.color || '#3B82F6'}20; color: ${event.color || '#3B82F6'};" onclick="event.stopPropagation(); showEventDetails(${JSON.stringify(event).replace(/"/g, '&quot;')})">
                        ${event.title}
                    </div>
                `).join('')}
            </div>
        `;
    }
    
    calendarHTML += '</div>';
    calendarContainer.innerHTML = calendarHTML;
}

function getMonthName(month) {
    const months = ['January', 'February', 'March', 'April', 'May', 'June', 
                    'July', 'August', 'September', 'October', 'November', 'December'];
    return months[month - 1];
}

function changeMonth(delta) {
    const url = new URL(window.location);
    let newMonth = currentMonth + delta;
    let newYear = currentYear;
    
    if (newMonth > 12) {
        newMonth = 1;
        newYear++;
    } else if (newMonth < 1) {
        newMonth = 12;
        newYear--;
    }
    
    url.searchParams.set('month', newMonth);
    url.searchParams.set('year', newYear);
    window.location.href = url.toString();
}

function showDayEvents(dateStr) {
    const dayEvents = events.filter(e => e.event_date.startsWith(dateStr));
    if (dayEvents.length === 1) {
        showEventDetails(dayEvents[0]);
    } else if (dayEvents.length > 1) {
        // Show first event or create a list modal
        showEventDetails(dayEvents[0]);
    }
}

function showEventDetails(event) {
    document.getElementById('modalTitle').textContent = event.title;
    document.getElementById('modalDate').textContent = formatEventDate(event);
    document.getElementById('modalDescription').innerHTML = event.description.replace(/\n/g, '<br>');
    
    // Event type
    document.getElementById('modalType').innerHTML = `
        <span class="inline-block px-3 py-1 text-sm rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
            ${event.event_type.replace('_', ' ').toUpperCase()}
        </span>
    `;
    
    // Location
    if (event.location) {
        document.getElementById('modalLocation').innerHTML = `
            <div class="flex items-center text-gray-700 dark:text-gray-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                ${event.location}
            </div>
        `;
    } else {
        document.getElementById('modalLocation').innerHTML = '';
    }
    
    // Department
    if (event.department) {
        document.getElementById('modalDepartment').innerHTML = `
            <div class="text-sm text-gray-600 dark:text-gray-400">
                Department: ${event.department.name}
            </div>
        `;
    } else {
        document.getElementById('modalDepartment').innerHTML = '';
    }
    
    document.getElementById('eventModal').classList.remove('hidden');
    document.getElementById('eventModal').classList.add('flex');
}

function formatEventDate(event) {
    const date = new Date(event.event_date);
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    let dateStr = date.toLocaleDateString('en-US', options);
    
    if (event.start_time) {
        const startTime = new Date(`2000-01-01 ${event.start_time}`);
        dateStr += ` at ${startTime.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' })}`;
        
        if (event.end_time) {
            const endTime = new Date(`2000-01-01 ${event.end_time}`);
            dateStr += ` - ${endTime.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' })}`;
        }
    }
    
    return dateStr;
}

function closeEventModal() {
    document.getElementById('eventModal').classList.add('hidden');
    document.getElementById('eventModal').classList.remove('flex');
}

function closeModal(event) {
    if (event.target.id === 'eventModal') {
        closeEventModal();
    }
}

// Render calendar on page load
document.addEventListener('DOMContentLoaded', () => {
    renderCalendar();
});
</script>
@endsection
