<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ARMD') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }

        /* Smooth transitions */
        .page-transition {
            transition: opacity 0.3s ease-in-out;
        }

        /* Sidebar animations */
        .sidebar-slide-enter {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }

        .sidebar-slide-enter-active {
            transform: translateX(0);
        }

        /* Footer positioning */
        .content-wrapper {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        .main-content {
            flex: 1;
        }

        /* Smooth hover transitions */
        .hover-transition {
            transition: all 0.2s ease-in-out;
        }

        /* Professional animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out forwards;
        }
    </style>
</head>
    <body class="h-full font-sans antialiased bg-gray-100"
          x-data="{ isMobileMenuOpen: false }"
          class="content-wrapper">
        <div class="min-h-full page-transition">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-200 relative z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('home') }}"
                               class="text-2xl font-bold text-primary-600 hover-transition hover:scale-105">
                                ARMD
                            </a>
                        </div>

                        <!-- Navigation Links (show for all users) -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex items-center animate-fade-in">
                            <!-- Departments Dropdown -->
                            <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                                <button class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-100 hover-transition">
                                    Departments
                                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                
                                <!-- Dropdown Menu - 3-Row Grid Layout -->
                                <div x-show="open" 
                                     x-cloak
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 transform scale-100"
                                     x-transition:leave-end="opacity-0 transform scale-95"
                                     class="absolute left-0 mt-2 w-[800px] bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                    <div class="p-6">
                                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-4">
                                            Browse by Department
                                        </div>
                                        
                                        @if($departments->count() > 0)
                                            <div class="grid grid-rows-3 grid-flow-col auto-cols-fr gap-3">
                                                @foreach($departments as $department)
                                                    <a href="{{ route('departments.show', $department->id) }}" 
                                                       class="block p-3 text-sm text-gray-700 hover:bg-gray-100 hover:text-primary-600 rounded-md transition-colors border border-gray-100">
                                                        <div class="flex items-start">
                                                            @if($department->icon)
                                                                <span class="mr-2 text-lg flex-shrink-0">{{ $department->icon }}</span>
                                                            @endif
                                                            <div class="min-w-0 flex-1">
                                                                <div class="font-medium truncate">{{ $department->name }}</div>
                                                                <div class="text-xs text-gray-500 mt-1">
                                                                    {{ $department->users()->where('role', 'personnel')->count() }} personnel
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                @endforeach
                                            </div>
                                            
                                            <div class="mt-4 pt-3 border-t border-gray-200">
                                                <a href="{{ route('departments.index') }}" 
                                                   class="block text-center px-4 py-2 text-sm text-primary-600 font-medium hover:bg-primary-50 rounded-md transition-colors">
                                                    View All Departments →
                                                </a>
                                            </div>
                                        @else
                                            <div class="px-4 py-8 text-sm text-gray-500 text-center">
                                                No departments available
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Resources Dropdown -->
                            <div class="relative group" x-data="{ open: false, libraryOpen: false }" @mouseenter="open = true" @mouseleave="open = false">
                                <button class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-100 hover-transition">
                                    Resources
                                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                
                                <!-- Dropdown Panel - Row Layout -->
                                <div x-show="open" 
                                     x-cloak
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute left-0 mt-2 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                                    <div class="flex">
                                        <!-- Library Section with Sub-dropdown -->
                                        <div class="relative group" @mouseenter="libraryOpen = true" @mouseleave="libraryOpen = false">
                                            <a href="{{ route('resources.library') }}" class="block px-6 py-4 text-sm font-medium text-gray-700 hover:bg-gray-50 whitespace-nowrap">
                                                Library
                                                <svg class="inline ml-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                            
                                            <!-- Library Sub-dropdown - SAVE Acronym -->
                                            <div x-show="libraryOpen" 
                                                 x-cloak
                                                 x-transition:enter="transition ease-out duration-200"
                                                 x-transition:enter-start="transform opacity-0 scale-95"
                                                 x-transition:enter-end="transform opacity-100 scale-100"
                                                 class="absolute left-full top-0 ml-1 w-96 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 z-50 p-6">
                                                <div class="mb-4">
                                                    <h4 class="font-semibold text-gray-900 text-lg">SAVE - Digital Products</h4>
                                                    <p class="text-sm text-gray-600 mt-2">Software, Audio, Video & E-books</p>
                                                    <p class="text-xs text-gray-500 mt-1">Digital products for staff assessment and company training solutions</p>
                                                </div>
                                                <div class="space-y-4 text-sm text-gray-600">
                                                    <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                                                        <strong class="text-blue-900 font-semibold">Software:</strong>
                                                        <p class="mt-1 text-xs">Interactive tools for skill assessment and hands-on learning. Includes simulation software, assessment tools, and practical training applications.</p>
                                                    </div>
                                                    <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                                                        <strong class="text-green-900 font-semibold">Audio:</strong>
                                                        <p class="mt-1 text-xs">Podcasts and audio content for on-the-go learning. Perfect for auditory learners and mobile training sessions.</p>
                                                    </div>
                                                    <div class="p-4 bg-purple-50 rounded-lg border border-purple-200">
                                                        <strong class="text-purple-900 font-semibold">Video:</strong>
                                                        <p class="mt-1 text-xs">Visual tutorials and recorded training sessions. Ideal for demonstrations and visual learning experiences.</p>
                                                    </div>
                                                    <div class="p-4 bg-orange-50 rounded-lg border border-orange-200">
                                                        <strong class="text-orange-900 font-semibold">E-books:</strong>
                                                        <p class="mt-1 text-xs">Digital manuals and comprehensive guides. Reference materials for in-depth knowledge and self-paced learning.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="border-l border-gray-200"></div>
                                        
                                        <!-- Other Resources - Row Layout -->
                                        <div class="py-1">
                                            <a href="{{ route('resources.courses') }}" class="block px-6 py-4 text-sm font-medium text-gray-700 hover:bg-gray-50 whitespace-nowrap">
                                                Courses
                                                <div class="text-xs text-gray-500">File formats & course types</div>
                                                <div class="text-xs text-gray-400 mt-1">Audiobooks, podcasts, e-books, videos</div>
                                            </a>
                                            <a href="{{ route('resources.training-fundamentals') }}" class="block px-6 py-4 text-sm font-medium text-gray-700 hover:bg-gray-50 whitespace-nowrap">
                                                Training Fundamentals
                                                <div class="text-xs text-gray-500">Core training principles</div>
                                                <div class="text-xs text-gray-400 mt-1">Results & personnel development</div>
                                            </a>
                                            <a href="{{ route('resources.tools') }}" class="block px-6 py-4 text-sm font-medium text-gray-700 hover:bg-gray-50 whitespace-nowrap">
                                                Tools
                                                <div class="text-xs text-gray-500">Department software tools</div>
                                                <div class="text-xs text-gray-400 mt-1">Software solutions by department</div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Assessments Dropdown -->
                            <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                                <button class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-100 hover-transition">
                                    Assessments
                                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                
                                <!-- Dropdown Panel - Row and Column Layout -->
                                <div x-show="open" 
                                     x-cloak
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute left-0 mt-2 w-80 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                                    <div class="grid grid-cols-2 gap-0">
                                        <!-- First Column -->
                                        <div class="p-4 border-r border-gray-200">
                                            <a href="{{ route('assessments.assignments') }}" class="block p-3 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-md transition-colors mb-2">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    <div>
                                                        <div class="font-medium">Assignments</div>
                                                        <div class="text-xs text-gray-500">View & manage assignments</div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="{{ route('assessments.performance-tracker') }}" class="block p-3 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                                    </svg>
                                                    <div>
                                                        <div class="font-medium">Performance Tracker</div>
                                                        <div class="text-xs text-gray-500">Monitor progress metrics</div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        
                                        <!-- Second Column -->
                                        <div class="p-4">
                                            <a href="{{ route('assessments.ratings-points') }}" class="block p-3 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-md transition-colors mb-2">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                                    </svg>
                                                    <div>
                                                        <div class="font-medium">Ratings & Points</div>
                                                        <div class="text-xs text-gray-500">Mutual rating system</div>
                                                    </div>
                                                </div>
                                            </a>
                                            <a href="{{ route('assessments.charts') }}" class="block p-3 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <div>
                                                        <div class="font-medium">Charts</div>
                                                        <div class="text-xs text-gray-500">Department analytics</div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modular Training Dropdown -->
                            <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                                <button class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-100 hover-transition">
                                    Modular Training
                                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                
                                <!-- Dropdown Panel - Row Layout -->
                                <div x-show="open" 
                                     x-cloak
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute left-0 mt-2 w-80 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                                    <div class="p-4">
                                        <div class="grid grid-cols-1 gap-2">
                                            <a href="{{ route('training.workshops') }}" class="block p-4 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                                <div class="flex items-start">
                                                    <svg class="w-5 h-5 mr-3 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                    </svg>
                                                    <div class="flex-1">
                                                        <div class="font-medium text-blue-600">Workshops</div>
                                                        <div class="text-xs text-gray-500 mt-1">Outcomes, attendee records, speakers & overview</div>
                                                        <div class="text-xs text-gray-400 mt-2">Admin-controlled workshop management</div>
                                                    </div>
                                                </div>
                                            </a>
                                            
                                            <a href="{{ route('training.symposium') }}" class="block p-4 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                                <div class="flex items-start">
                                                    <svg class="w-5 h-5 mr-3 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                    </svg>
                                                    <div class="flex-1">
                                                        <div class="font-medium text-green-600">Symposium</div>
                                                        <div class="text-xs text-gray-500 mt-1">Symposium data & records management</div>
                                                        <div class="text-xs text-gray-400 mt-2">Admin-controlled symposium data</div>
                                                    </div>
                                                </div>
                                            </a>
                                            
                                            <a href="{{ route('training.practical-guides') }}" class="block p-4 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                                <div class="flex items-start">
                                                    <svg class="w-5 h-5 mr-3 text-purple-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                    </svg>
                                                    <div class="flex-1">
                                                        <div class="font-medium text-purple-600">Practical Guides</div>
                                                        <div class="text-xs text-gray-500 mt-1">Step-by-step instructions & best practices</div>
                                                        <div class="text-xs text-gray-400 mt-2">Admin-controlled guide content</div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Events Dropdown -->
                            <div class="relative group" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                                <button class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-100 hover-transition">
                                    Events
                                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                
                                <!-- Dropdown Panel - Row and Column Layout -->
                                <div x-show="open" 
                                     x-cloak
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute left-0 mt-2 w-80 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                                    <div class="grid grid-cols-1 gap-0">
                                        <div class="p-4 border-b border-gray-200">
                                            <a href="{{ route('events.meetings') }}" class="block p-3 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                                <div class="flex items-start">
                                                    <svg class="w-5 h-5 mr-3 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                    </svg>
                                                    <div class="flex-1">
                                                        <div class="font-medium text-red-600">Meetings</div>
                                                        <div class="text-xs text-gray-500 mt-1">Upcoming meetings & schedules</div>
                                                        <div class="text-xs text-gray-400 mt-2">Admin-controlled meeting updates</div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        
                                        <div class="p-4 border-b border-gray-200">
                                            <a href="{{ route('events.calendar') }}" class="block p-3 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                                <div class="flex items-start">
                                                    <svg class="w-5 h-5 mr-3 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    <div class="flex-1">
                                                        <div class="font-medium text-blue-600">Calendar</div>
                                                        <div class="text-xs text-gray-500 mt-1">Company events & dates</div>
                                                        <div class="text-xs text-gray-400 mt-2">Admin-marked with event details</div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        
                                        <div class="p-4">
                                            <a href="{{ route('events.reminders') }}" class="block p-3 text-sm font-medium text-gray-700 hover:bg-gray-50 rounded-md transition-colors">
                                                <div class="flex items-start">
                                                    <svg class="w-5 h-5 mr-3 text-yellow-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                                    </svg>
                                                    <div class="flex-1">
                                                        <div class="font-medium text-yellow-600">Reminders</div>
                                                        <div class="text-xs text-gray-500 mt-1">Event notifications & alerts</div>
                                                        <div class="text-xs text-gray-400 mt-2">Admin-controlled reminder system</div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @guest
                            <!-- Login Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" 
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-primary-600 rounded-md hover:bg-primary-50 hover-transition">
                                    Sign In
                                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                
                                <!-- Dropdown Panel -->
                                <div x-show="open" 
                                     x-cloak
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     @click.away="open = false"
                                     class="absolute right-0 mt-2 w-64 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                                    <div class="p-4">
                                        <h3 class="text-sm font-medium text-gray-900 mb-3">Choose Login Type</h3>
                                        
                                        <!-- Facilitator Login -->
                                        <div class="mb-3">
                                            <a href="{{ route('login') }}?type=facilitator" 
                                               class="block w-full text-left px-4 py-3 bg-primary-600 text-white text-sm font-medium rounded-md hover:bg-primary-700 transition-colors">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                    Login as Facilitator
                                                </div>
                                                <div class="text-xs mt-1 opacity-90">Use your facilitator code</div>
                                            </a>
                                        </div>
                                        
                                        <!-- Personnel Login -->
                                        <div>
                                            <a href="{{ route('login') }}?type=personnel" 
                                               class="block w-full text-left px-4 py-3 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200 transition-colors">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                    </svg>
                                                    Login as Personnel
                                                </div>
                                                <div class="text-xs mt-1 opacity-75">Use email and password</div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <a href="{{ route('register') }}"
                               class="inline-flex items-center px-3 py-2 text-sm font-medium text-primary-600 rounded-md hover:bg-primary-50 hover-transition">
                                Get Started
                            </a>
                            @endguest
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Auth navigation -->
                        @auth
                            @if(auth()->user()->role === 'personnel')
                                <!-- Personnel department and facilitator info -->
                                <div class="hidden sm:flex items-center space-x-4 text-sm">
                                    <div class="text-gray-600">
                                        <span class="font-medium">{{ auth()->user()->department->name }}</span>
                                        <span class="mx-2">•</span>
                                        <span>Facilitator: {{ auth()->user()->department->users()->where('role', 'facilitator')->first()->fullname ?? 'Not Assigned' }}</span>
                                    </div>
                                </div>
                            @endif

                            <div class="flex items-center space-x-2">
                            <!-- Get Help Button -->
                            <a href="{{ route('help.index') }}"
                               class="flex items-center justify-center h-8 w-8 rounded-full hover:bg-gray-100 p-0 hover-transition"
                               title="Get Help">
                                <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </a>

                            <!-- Logout Button -->
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit"
                                        class="flex items-center justify-center h-8 w-8 rounded-full hover:bg-gray-100 p-0 hover-transition"
                                        title="Logout">
                                    <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Mobile menu -->
            <div x-show="isMobileMenuOpen"
                 x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="sm:hidden">
                @guest
                <div class="px-2 pt-2 pb-3 space-y-1">
                    <!-- Departments Mobile Dropdown -->
                    <div class="px-3 py-2">
                        <div class="text-base font-medium text-gray-700 mb-2">Departments</div>
                        <div class="pl-4 space-y-1">
                            @forelse($departments as $department)
                                <a href="{{ route('departments.show', $department->id) }}" 
                                   class="block px-3 py-2 text-sm text-gray-600 hover:text-primary-600 hover:bg-gray-50 rounded-md">
                                    <div class="flex items-center">
                                        @if($department->icon)
                                            <span class="mr-2">{{ $department->icon }}</span>
                                        @endif
                                        {{ $department->name }}
                                    </div>
                                </a>
                            @empty
                                <div class="px-3 py-2 text-sm text-gray-500">
                                    No departments available
                                </div>
                            @endforelse
                            <a href="{{ route('departments.index') }}" 
                               class="block px-3 py-2 text-sm text-primary-600 font-medium hover:bg-primary-50 rounded-md">
                                View All Departments →
                            </a>
                        </div>
                    </div>

                    <!-- Resources Mobile Dropdown -->
                    <div class="px-3 py-2">
                        <div class="text-base font-medium text-gray-700 mb-2">Resources</div>
                        <div class="pl-4 space-y-1">
                            @forelse($departments as $department)
                                <a href="{{ route('departments.resources.index', $department->id) }}" 
                                   class="block px-3 py-2 text-sm text-gray-600 hover:text-primary-600 hover:bg-gray-50 rounded-md">
                                    <div class="flex items-center">
                                        @if($department->icon)
                                            <span class="mr-2">{{ $department->icon }}</span>
                                        @endif
                                        {{ $department->name }} Resources
                                    </div>
                                </a>
                            @empty
                                <div class="px-3 py-2 text-sm text-gray-500">
                                    No departments available
                                </div>
                            @endforelse
                            <a href="{{ route('resource.types') }}" 
                               class="block px-3 py-2 text-sm text-gray-600 hover:text-primary-600 hover:bg-gray-50 rounded-md">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    Resource Types
                                </div>
                            </a>
                            <a href="{{ route('trainings.index') }}" 
                               class="block px-3 py-2 text-sm text-gray-600 hover:text-primary-600 hover:bg-gray-50 rounded-md">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    Trainings & Assessments
                                </div>
                            </a>
                            <a href="{{ route('resources.index') }}" 
                               class="block px-3 py-2 text-sm text-primary-600 font-medium hover:bg-primary-50 rounded-md">
                                View All Resources →
                            </a>
                        </div>
                    </div>
                    
                    <!-- Mobile Login Options -->
                    <div class="px-3 py-2 border-t border-gray-200 mt-3 pt-3">
                        <div class="text-base font-medium text-gray-700 mb-2">Sign In</div>
                        <div class="pl-4 space-y-2">
                            <a href="{{ route('login') }}?type=facilitator" 
                               class="block px-4 py-3 bg-primary-600 text-white text-sm font-medium rounded-md hover:bg-primary-700 transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Login as Facilitator
                                </div>
                                <div class="text-xs mt-1 opacity-90">Use your facilitator code</div>
                            </a>
                            <a href="{{ route('login') }}?type=personnel" 
                               class="block px-4 py-3 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200 transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    Login as Personnel
                                </div>
                                <div class="text-xs mt-1 opacity-75">Use email and password</div>
                            </a>
                        </div>
                    </div>
                    
                    <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium text-primary-600 hover:bg-gray-50">
                        Get Started
                    </a>
                </div>
                @endguest
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content py-10 animate-fade-in">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                @if(session('success'))
                    <div x-data="{ show: true }"
                         x-show="show"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform translate-y-2"
                         class="mb-6 rounded-md bg-green-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">
                                    {{ session('success') }}
                                </p>
                            </div>
                            <div class="ml-auto pl-3">
                                <div class="-mx-1.5 -my-1.5">
                                    <button @click="show = false" class="inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50 focus:ring-green-600">
                                        <span class="sr-only">Dismiss</span>
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div x-data="{ show: true }"
                         x-show="show"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-y-2"
                         x-transition:enter-end="opacity-100 transform translate-y-0"
                         x-transition:leave="transition ease-in duration-300"
                         x-transition:leave-start="opacity-100 transform translate-y-0"
                         x-transition:leave-end="opacity-0 transform translate-y-2"
                         class="mb-6 rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">
                                    {{ session('error') }}
                                </p>
                            </div>
                            <div class="ml-auto pl-3">
                                <div class="-mx-1.5 -my-1.5">
                                    <button @click="show = false" class="inline-flex rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-red-50 focus:ring-red-600">
                                        <span class="sr-only">Dismiss</span>
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="mt-auto bg-gray-50 border-t border-gray-200">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <!-- First Row - Blog, News, Magazine, About Us -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                    <!-- Blog -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                            Blog
                        </h3>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Our online journal featuring regular posts with written entries, photos, and videos in reverse chronological order. Perfect for personal reflection and brand building.
                        </p>
                        <div class="space-y-2">
                            <a href="{{ route('blog.latest-posts') }}" class="block text-sm text-primary-600 hover:underline transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Latest Posts
                                </div>
                            </a>
                            <a href="{{ route('blog.categories') }}" class="block text-sm text-primary-600 hover:underline transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    Categories
                                </div>
                            </a>
                            <a href="{{ route('blog.archive') }}" class="block text-sm text-primary-600 hover:underline transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Archive
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- News -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                            News
                        </h3>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Breaking news, reports, opinion pieces, and press releases organized by sections with multimedia support for rapid updates.
                        </p>
                        <div class="space-y-2">
                            <a href="{{ route('news.latest') }}" class="block text-sm text-primary-600 hover:underline transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                    Latest News
                                </div>
                            </a>
                            <a href="{{ route('news.categories') }}" class="block text-sm text-primary-600 hover:underline transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    Categories
                                </div>
                            </a>
                            <a href="{{ route('news.multimedia') }}" class="block text-sm text-primary-600 hover:underline transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    Multimedia
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Magazine -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            Magazine
                        </h3>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Internal and external communication platform featuring company initiatives, performance updates, and success stories.
                        </p>
                        <div class="space-y-2">
                            <a href="{{ route('magazine.current-issue') }}" class="block text-sm text-primary-600 hover:underline transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    </svg>
                                    Current Issue
                                </div>
                            </a>
                            <a href="{{ route('magazine.archives') }}" class="block text-sm text-primary-600 hover:underline transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Archives
                                </div>
                            </a>
                            <a href="{{ route('magazine.company-stories') }}" class="block text-sm text-primary-600 hover:underline transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Company Stories
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- About Us -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            About Us
                        </h3>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Everything about Atommart - who we are, what we do, and our mission to transform learning management.
                        </p>
                        <div class="space-y-2">
                            <a href="{{ route('about.story') }}" class="block text-sm text-primary-600 hover:underline transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    Our Story
                                </div>
                            </a>
                            <a href="{{ route('about.mission-values') }}" class="block text-sm text-primary-600 hover:underline transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                    </svg>
                                    Mission & Values
                                </div>
                            </a>
                            <a href="{{ route('about.team') }}" class="block text-sm text-primary-600 hover:underline transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Team
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Second Row - Help Center, Help Forum, Call/Chat -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pt-8 border-t border-gray-200">
                    <!-- Help Center -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Help Center
                        </h3>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Search functionality, FAQs, organized categories, step-by-step guides, and customer feedback for comprehensive support.
                        </p>
                        <div class="space-y-2">
                            <a href="{{ route('help.index') }}" class="block text-sm text-primary-600 hover:underline transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Search FAQs
                                </div>
                            </a>
                            <a href="{{ route('help-center.guides') }}" class="block text-sm text-primary-600 hover:underline transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Guides & Tutorials
                                </div>
                            </a>
                            <a href="{{ route('help-center.contact') }}" class="block text-sm text-primary-600 hover:underline transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    Contact Support
                                </div>
                            </a>
                            <a href="{{ route('help-center.feedback') }}" class="block text-sm text-primary-600 hover:underline transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                    </svg>
                                    Send Feedback
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Help Forum -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                            </svg>
                            Help Forum
                        </h3>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Discussion threads, user profiles, search functionality, moderation tools, and community engagement features.
                        </p>
                        <div class="space-y-2">
                            <a href="{{ route('help-forum.topics') }}" class="block text-sm text-primary-600 hover:underline transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                    </svg>
                                    Browse Topics
                                </div>
                            </a>
                            <a href="{{ route('help-forum.guidelines') }}" class="block text-sm text-primary-600 hover:underline transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    Community Guidelines
                                </div>
                            </a>
                            <a href="{{ route('help-forum.profiles') }}" class="block text-sm text-primary-600 hover:underline transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    User Profiles
                                </div>
                            </a>
                            <a href="{{ route('help-forum.events') }}" class="block text-sm text-primary-600 hover:underline transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Events & Calendar
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Call/Chat -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            Call/Chat
                        </h3>
                        <p class="text-sm text-gray-600 leading-relaxed">
                            Get in touch with us through phone calls or chat for immediate assistance and support.
                        </p>
                        <div class="space-y-2">
                            <a href="tel:+1234567890" class="block text-sm text-primary-600 hover:underline transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    +1 (234) 567-890
                                </div>
                            </a>
                            <a href="{{ route('support.live-chat') }}" class="block text-sm text-primary-600 hover:underline transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    Live Chat Support
                                </div>
                            </a>
                            <a href="{{ route('support.email') }}" class="block text-sm text-primary-600 hover:underline transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    Email Support
                                </div>
                            </a>
                            <a href="{{ route('support.whatsapp') }}" class="block text-sm text-primary-600 hover:underline transition-colors">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    WhatsApp Support
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Bottom Copyright Row -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <p class="text-sm text-gray-500">
                            &copy; {{ date('Y') }} Atommart Resource Management Database. All rights reserved.
                        </p>
                        <div class="flex space-x-6 mt-4 md:mt-0">
                            <a href="#" class="text-gray-400 hover:text-gray-500 hover-transition">
                                <span class="sr-only">Privacy Policy</span>
                                Privacy
                            </a>
                            <a href="#" class="text-gray-400 hover:text-gray-500 hover-transition">
                                <span class="sr-only">Terms of Service</span>
                                Terms
                            </a>
                            <a href="#" class="text-gray-400 hover:text-gray-500 hover-transition">
                                <span class="sr-only">Cookie Policy</span>
                                Cookies
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @stack('scripts')
    <script>
        window.Laravel = {
            @auth
            user: @json(auth()->user()),
            @else
            user: null,
            @endauth
        };
    </script>
</body>
</html>
