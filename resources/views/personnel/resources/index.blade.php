@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Resources</h1>
        <a href="{{ route('personnel.dashboard') }}" class="text-sm text-primary-600 hover:underline">Back to Dashboard</a>
    </div>

    <!-- Resource Type Filter -->
    <div class="mb-6 flex flex-col sm:flex-row gap-4">
        <!-- Type Dropdown -->
        <div class="flex-1 max-w-xs">
            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Resource Type</label>
            <select id="type" name="type" class="w-full rounded border-gray-300 px-3 py-2 dark:bg-gray-700 dark:border-gray-600" onchange="filterResources()">
                <option value="">All Types</option>
                <option value="video">Videos</option>
                <option value="ebook">E-books</option>
            </select>
        </div>

        <!-- Search Box -->
        <div class="flex-1">
            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search Resources</label>
            <div class="relative">
                <input type="text" id="search" name="search" 
                       placeholder="Search by title, description, or facilitator..."
                       class="w-full rounded border-gray-300 px-3 py-2 pr-10 dark:bg-gray-700 dark:border-gray-600"
                       oninput="filterResources()">
                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Resources Grid -->
    <div id="resources-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($resources as $resource)
            <div class="resource-card border rounded-lg border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition" 
                 data-type="{{ $resource->type }}" 
                 data-title="{{ strtolower($resource->title) }}" 
                 data-description="{{ strtolower($resource->description ?? '') }}"
                 data-facilitator="{{ strtolower($resource->facilitator->fullname) }}">
                
                <!-- Resource Header -->
                <div class="p-4 bg-gray-50 dark:bg-gray-900">
                    <div class="flex items-center justify-between mb-2">
                        <span class="px-2 py-1 text-xs rounded {{ $resource->type === 'video' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                            {{ ucfirst($resource->type) }}
                        </span>
                        @if($resource->is_previewable)
                            <span class="text-xs text-gray-500">Preview Available</span>
                        @endif
                    </div>
                    <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-2">{{ $resource->title }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ $resource->description ? Str::limit($resource->description, 100) : 'No description available' }}
                    </p>
                </div>

                <!-- Resource Details -->
                <div class="p-4">
                    <div class="text-sm text-gray-500 mb-3">
                        <div>Facilitator: {{ $resource->facilitator->fullname }}</div>
                        <div>Duration: {{ $resource->duration ?? 'N/A' }}</div>
                        <div>Department: {{ $resource->department->name }}</div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                        @if($resource->is_previewable)
                            <a href="{{ route('personnel.resources.show', $resource->id) }}" 
                               class="flex-1 text-center px-3 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 text-sm">
                                Preview
                            </a>
                        @endif

                        @if($resource->enrollments_count > 0)
                            <button disabled class="flex-1 px-3 py-2 bg-green-100 text-green-700 rounded text-sm cursor-not-allowed">
                                ✓ Enrolled
                            </button>
                        @else
                            <form action="{{ route('personnel.enroll.store') }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="course_id" value="{{ $resource->id }}">
                                <button type="submit" 
                                        onclick="return confirm('Are you sure you want to enroll in this course?')"
                                        class="w-full px-3 py-2 bg-primary-600 text-white rounded hover:bg-primary-700 text-sm">
                                    Enroll Now
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($resources->count() === 0)
        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No resources available</h3>
            <p class="mt-1 text-sm text-gray-500">No resources have been added to your department yet.</p>
        </div>
    @endif
</div>

<script>
function filterResources() {
    const type = document.getElementById('type').value.toLowerCase();
    const search = document.getElementById('search').value.toLowerCase();
    const cards = document.querySelectorAll('.resource-card');
    
    cards.forEach(card => {
        const cardType = card.dataset.type;
        const title = card.dataset.title;
        const description = card.dataset.description;
        const facilitator = card.dataset.facilitator;
        
        const matchesType = !type || cardType === type;
        const matchesSearch = !search || 
            title.includes(search) || 
            description.includes(search) || 
            facilitator.includes(search);
        
        card.style.display = matchesType && matchesSearch ? 'block' : 'none';
    });
}
</script>
@endsection
