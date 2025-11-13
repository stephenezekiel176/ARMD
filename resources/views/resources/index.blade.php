@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Available Courses</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Explore courses from all departments and enroll to start learning</p>
            </div>
            @auth
                <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300">
                    My Dashboard
                </a>
            @endauth
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <!-- Department Filter -->
            <div class="flex-1 max-w-xs">
                <label for="department" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Department</label>
                <select id="department" name="department" class="w-full rounded border-gray-300 px-3 py-2 dark:bg-gray-700 dark:border-gray-600" onchange="filterCourses()">
                    <option value="">All Departments</option>
                    @foreach(\App\Models\Department::all() as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Type Filter -->
            <div class="flex-1 max-w-xs">
                <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Course Type</label>
                <select id="type" name="type" class="w-full rounded border-gray-300 px-3 py-2 dark:bg-gray-700 dark:border-gray-600" onchange="filterCourses()">
                    <option value="">All Types</option>
                    <option value="video">🎥 Videos</option>
                    <option value="ebook">📚 E-books</option>
                    <option value="audio">🎵 Audio</option>
                    <option value="image">🖼️ Images</option>
                    <option value="podcast">🎙️ Podcasts</option>
                </select>
            </div>

            <!-- Search Box -->
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search Courses</label>
                <div class="relative">
                    <input type="text" id="search" name="search" 
                           placeholder="Search by title, description, or facilitator..."
                           class="w-full rounded border-gray-300 px-3 py-2 pr-10 dark:bg-gray-700 dark:border-gray-600"
                           oninput="filterCourses()">
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Courses Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($resources as $course)
            <div class="course-card bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden hover:shadow-lg transition" 
                 data-department="{{ $course->department_id }}" 
                 data-type="{{ $course->type }}" 
                 data-title="{{ strtolower($course->title) }}" 
                 data-description="{{ strtolower($course->description ?? '') }}"
                 data-facilitator="{{ strtolower($course->facilitator->fullname) }}">
                
                <!-- Course Header -->
                <div class="p-4 bg-gray-50 dark:bg-gray-900">
                    <div class="flex items-center justify-between mb-2">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            @switch($course->type)
                                @case('video')
                                    bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @break
                                @case('ebook')
                                    bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                    @break
                                @case('audio')
                                    bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @break
                                @case('image')
                                    bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                    @break
                                @case('podcast')
                                    bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @break
                                @default
                                    bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                            @endswitch">
                            @switch($course->type)
                                @case('video')
                                    🎥 {{ ucfirst($course->type) }}
                                    @break
                                @case('ebook')
                                    📚 {{ ucfirst($course->type) }}
                                    @break
                                @case('audio')
                                    🎵 {{ ucfirst($course->type) }}
                                    @break
                                @case('image')
                                    🖼️ {{ ucfirst($course->type) }}
                                    @break
                                @case('podcast')
                                    🎙️ {{ ucfirst($course->type) }}
                                    @break
                                @default
                                    📄 {{ ucfirst($course->type) }}
                            @endswitch
                        </span>
                        @if($course->is_previewable)
                            <span class="text-xs text-gray-500 dark:text-gray-400">Preview Available</span>
                        @endif
                    </div>
                    <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-2">{{ $course->title }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ $course->description ? Str::limit($course->description, 100) : 'No description available' }}
                    </p>
                </div>

                <!-- Course Details -->
                <div class="p-4">
                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-3">
                        <div>Facilitator: {{ $course->facilitator->fullname }}</div>
                        <div>Department: {{ $course->department->name }}</div>
                        <div>Duration: {{ $course->duration ?? 'N/A' }} minutes</div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                        @if($course->is_previewable)
                            <a href="{{ route('resources.show', $course->id) }}" 
                               class="flex-1 text-center px-3 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 text-sm">
                                Preview
                            </a>
                        @endif

                        @guest
                            <button onclick="showAuthModal()" 
                                    class="flex-1 px-3 py-2 bg-primary-600 text-white rounded hover:bg-primary-700 text-sm">
                                Enroll Now
                            </button>
                        @else
                            @php
                                $isEnrolled = auth()->user()->enrollments()->where('course_id', $course->id)->exists();
                            @endphp
                            @if($isEnrolled)
                                <button disabled class="flex-1 px-3 py-2 bg-green-100 text-green-700 rounded text-sm cursor-not-allowed">
                                    ✓ Enrolled
                                </button>
                            @else
                                <form action="{{ route('resources.enroll', $course->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" 
                                            onclick="return confirm('Are you sure you want to enroll in this course?')"
                                            class="w-full px-3 py-2 bg-primary-600 text-white rounded hover:bg-primary-700 text-sm">
                                        Enroll Now
                                    </button>
                                </form>
                            @endif
                        @endguest
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No courses available</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No courses have been created yet.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg px-6 py-4">
        {{ $resources->links() }}
    </div>
</div>

<!-- Authentication Modal -->
@guest
<div id="authModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-primary-100 dark:bg-primary-900">
                <svg class="h-6 w-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100 mt-4">Authentication Required</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    You need to be logged in to enroll in courses. Please sign in or create an account to continue.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <a href="{{ route('login') }}" 
                   class="px-4 py-2 bg-primary-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 mb-2 block text-center">
                    Sign In
                </a>
                <a href="{{ route('register') }}" 
                   class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 block text-center">
                    Create Account
                </a>
            </div>
            <div class="items-center px-4 py-3">
                <button onclick="hideAuthModal()" 
                        class="px-4 py-2 text-gray-500 dark:text-gray-400 text-base font-medium rounded-md w-full shadow-sm hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
@endguest

<script>
function filterCourses() {
    const department = document.getElementById('department').value;
    const type = document.getElementById('type').value.toLowerCase();
    const search = document.getElementById('search').value.toLowerCase();
    const cards = document.querySelectorAll('.course-card');
    
    cards.forEach(card => {
        const cardDepartment = card.dataset.department;
        const cardType = card.dataset.type;
        const title = card.dataset.title;
        const description = card.dataset.description;
        const facilitator = card.dataset.facilitator;
        
        const matchesDepartment = !department || cardDepartment === department;
        const matchesType = !type || cardType === type;
        const matchesSearch = !search || 
            title.includes(search) || 
            description.includes(search) || 
            facilitator.includes(search);
        
        card.style.display = matchesDepartment && matchesType && matchesSearch ? 'block' : 'none';
    });
}

function showAuthModal() {
    document.getElementById('authModal').classList.remove('hidden');
}

function hideAuthModal() {
    document.getElementById('authModal').classList.add('hidden');
}
</script>
@endsection
