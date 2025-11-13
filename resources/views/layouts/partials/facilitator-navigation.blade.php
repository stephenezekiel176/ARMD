<!-- Navigation Links -->
<nav class="mt-5 flex-1 px-4" x-data="{ activeSection: '{{ request()->segment(2) }}' }">
    <div class="space-y-1">
        <!-- Dashboard -->
        <a href="{{ route('facilitator.dashboard') }}"
           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 {{ request()->routeIs('facilitator.dashboard') ? 'bg-primary-100 dark:bg-primary-800 text-primary-700 dark:text-primary-200' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
            <svg class="mr-3 h-5 w-5 transition-colors duration-150 ease-in-out {{ request()->routeIs('facilitator.dashboard') ? 'text-primary-500 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span>Dashboard</span>
            <span class="ml-auto text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-150">Overview</span>
        </a>

        <!-- Personnel Database (primary) -->
        <div x-data="{ open: {{ request()->routeIs('facilitator.personnel.*') ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="open = !open"
                    @keydown.space.prevent="open = !open"
                    @keydown.enter.prevent="open = !open"
                    class="w-full flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 {{ request()->routeIs('facilitator.personnel.*') ? 'bg-primary-100 dark:bg-primary-800 text-primary-700 dark:text-primary-200' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                <svg class="mr-3 h-5 w-5 transition-colors duration-150 ease-in-out {{ request()->routeIs('facilitator.personnel.*') ? 'text-primary-500 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>Personnel Database</span>
                <svg class="ml-auto h-5 w-5 transform transition-transform duration-200" :class="{ 'rotate-90': open }" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
            </button>
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-1"
                 class="pl-4 space-y-1">
                @php
                    $deptPersonnel = \App\Models\Personnel::with('user')
                        ->whereHas('user', function($q) { $q->where('department_id', auth()->user()->department_id); })
                        ->latest()
                        ->take(10)
                        ->get();
                @endphp
                @forelse($deptPersonnel as $person)
                    <a href="{{ route('facilitator.personnel.show', $person->id) }}"
                       class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 {{ request()->is('facilitator/personnel/'.$person->id) ? 'bg-primary-50 text-primary-700 dark:bg-primary-900 dark:text-primary-200' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                        <span class="truncate">{{ $person->user->fullname }}</span>
                    </a>
                @empty
                    <div class="text-sm text-gray-400 px-2 py-2">No personnel assigned</div>
                @endforelse
                
                <!-- View All Personnel Link -->
                <a href="{{ route('facilitator.personnel.index') }}"
                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 border-t border-gray-100 dark:border-gray-700 mt-1 pt-2">
                    <svg class="mr-2 h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>View All Personnel</span>
                    <span class="ml-auto text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-150">Database</span>
                </a>
            </div>
        </div>

        <!-- Courses -->
        <div x-data="{ open: {{ request()->routeIs('facilitator.courses.*') ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="open = !open"
                    @keydown.space.prevent="open = !open"
                    @keydown.enter.prevent="open = !open"
                    class="w-full group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 {{ request()->routeIs('facilitator.courses.*') ? 'bg-primary-100 dark:bg-primary-800 text-primary-700 dark:text-primary-200' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                <svg class="mr-3 h-5 w-5 transition-colors duration-150 ease-in-out {{ request()->routeIs('facilitator.courses.*') ? 'text-primary-500 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <span>Courses</span>
                <svg class="ml-auto h-5 w-5 transform transition-transform duration-200" :class="{ 'rotate-90': open }" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
            </button>
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-1"
                 class="pl-4 space-y-1">
                <a href="{{ route('facilitator.courses.index') }}"
                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 {{ request()->routeIs('facilitator.courses.index') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900 dark:text-primary-200' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <span>All Courses</span>
                    <span class="ml-auto text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-150">Manage</span>
                </a>
                
                <!-- Course Format Filters -->
                <a href="{{ route('facilitator.courses.index', ['format' => 'video']) }}"
                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 {{ request()->routeIs('facilitator.courses.index') && request('format') == 'video' ? 'bg-primary-50 text-primary-700 dark:bg-primary-900 dark:text-primary-200' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <svg class="mr-3 h-4 w-4 transition-colors duration-150 ease-in-out {{ request()->routeIs('facilitator.courses.index') && request('format') == 'video' ? 'text-primary-500 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    <span>Video Courses</span>
                </a>
                
                <a href="{{ route('facilitator.courses.index', ['format' => 'ebook']) }}"
                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 {{ request()->routeIs('facilitator.courses.index') && request('format') == 'ebook' ? 'bg-primary-50 text-primary-700 dark:bg-primary-900 dark:text-primary-200' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <svg class="mr-3 h-4 w-4 transition-colors duration-150 ease-in-out {{ request()->routeIs('facilitator.courses.index') && request('format') == 'ebook' ? 'text-primary-500 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span>E-Book Courses</span>
                </a>
                
                <a href="{{ route('facilitator.courses.index', ['format' => 'audio']) }}"
                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 {{ request()->routeIs('facilitator.courses.index') && request('format') == 'audio' ? 'bg-primary-50 text-primary-700 dark:bg-primary-900 dark:text-primary-200' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <svg class="mr-3 h-4 w-4 transition-colors duration-150 ease-in-out {{ request()->routeIs('facilitator.courses.index') && request('format') == 'audio' ? 'text-primary-500 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
                    </svg>
                    <span>Audio Courses</span>
                </a>
                
                <a href="{{ route('facilitator.courses.index', ['format' => 'image']) }}"
                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 {{ request()->routeIs('facilitator.courses.index') && request('format') == 'image' ? 'bg-primary-50 text-primary-700 dark:bg-primary-900 dark:text-primary-200' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <svg class="mr-3 h-4 w-4 transition-colors duration-150 ease-in-out {{ request()->routeIs('facilitator.courses.index') && request('format') == 'image' ? 'text-primary-500 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Image Courses</span>
                </a>
                
                <a href="{{ route('facilitator.courses.index', ['format' => 'podcast']) }}"
                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 {{ request()->routeIs('facilitator.courses.index') && request('format') == 'podcast' ? 'bg-primary-50 text-primary-700 dark:bg-primary-900 dark:text-primary-200' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <svg class="mr-3 h-4 w-4 transition-colors duration-150 ease-in-out {{ request()->routeIs('facilitator.courses.index') && request('format') == 'podcast' ? 'text-primary-500 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                    </svg>
                    <span>Podcast Courses</span>
                </a>
                
                <a href="{{ route('facilitator.courses.create') }}"
                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 {{ request()->routeIs('facilitator.courses.create') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900 dark:text-primary-200' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <span>Create Course</span>
                    <span class="ml-auto text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-150">New</span>
                </a>
            </div>
        </div>

        <!-- Assessments -->
        <div x-data="{ open: {{ request()->routeIs('facilitator.assessments.*') ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="open = !open"
                    @keydown.space.prevent="open = !open"
                    @keydown.enter.prevent="open = !open"
                    class="w-full group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 {{ request()->routeIs('facilitator.assessments.*') ? 'bg-primary-100 dark:bg-primary-800 text-primary-700 dark:text-primary-200' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                <svg class="mr-3 h-5 w-5 transition-colors duration-150 ease-in-out {{ request()->routeIs('facilitator.assessments.*') ? 'text-primary-500 dark:text-primary-400' : 'text-gray-500 dark:text-gray-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <span>Assessments</span>
                <svg class="ml-auto h-5 w-5 transform transition-transform duration-200" :class="{ 'rotate-90': open }" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
            </button>
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-1"
                 class="pl-4 space-y-1">
                <a href="{{ route('facilitator.assessments.index') }}"
                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 {{ request()->routeIs('facilitator.assessments.index') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900 dark:text-primary-200' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <span>All Assessments</span>
                    <span class="ml-auto text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-150">View all</span>
                </a>
                <a href="{{ route('facilitator.assessments.create') }}"
                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 {{ request()->routeIs('facilitator.assessments.create') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900 dark:text-primary-200' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <span>Create Assessment</span>
                    <span class="ml-auto text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-150">New</span>
                </a>
                <a href="{{ route('facilitator.assessments.submissions') }}"
                   class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 {{ request()->routeIs('facilitator.assessments.submissions') ? 'bg-primary-50 text-primary-700 dark:bg-primary-900 dark:text-primary-200' : 'text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    <span>Submissions</span>
                    <span class="ml-auto text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-150">Review</span>
                </a>
            </div>
        </div>
    </div>
</nav>
