<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ARMD') }} - Facilitator Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-sans antialiased bg-gray-100 dark:bg-gray-900" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', sidebarOpen: false }" x-init="$watch('darkMode', val => { localStorage.setItem('darkMode', val); document.documentElement.classList.toggle('dark', val) }); if(localStorage.getItem('darkMode') === 'true') { document.documentElement.classList.add('dark') }" :class="{ 'dark': darkMode }">
    <div class="min-h-screen flex flex-col">
        <!-- Sidebar -->
        <div x-show="sidebarOpen" class="fixed inset-0 z-40 lg:hidden" role="dialog" aria-modal="true">
            <!-- Overlay -->
            <div class="fixed inset-0 bg-gray-600 bg-opacity-75" aria-hidden="true" @click="sidebarOpen = false"></div>

            <!-- Sidebar panel -->
            <div class="relative flex flex-col w-64 h-full bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700">
                <div class="flex-1 flex flex-col min-h-0 pt-5">
                    <!-- Close button -->
                    <div class="flex items-center justify-between px-4">
                        <div class="flex-shrink-0">
                            <a href="{{ route('facilitator.dashboard') }}" class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                                ARMD
                            </a>
                        </div>
                        <button @click="sidebarOpen = false" class="lg:hidden">
                            <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Sidebar Navigation -->
                    @include('layouts.partials.facilitator-navigation')
                </div>
            </div>
        </div>

        <!-- Static sidebar for desktop -->
        <div class="hidden lg:flex lg:flex-col lg:w-64 lg:fixed lg:inset-y-0 border-r border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
            <div class="flex-1 flex flex-col min-h-0 pt-5">
                <div class="flex items-center justify-between px-4">
                    <div class="flex-shrink-0">
                        <a href="{{ route('facilitator.dashboard') }}" class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                            ARMD
                        </a>
                    </div>
                </div>

                <!-- Sidebar Navigation -->
                @include('layouts.partials.facilitator-navigation')
            </div>
        </div>

    <!-- Main content area -->
    <div class="lg:pl-64 flex flex-col flex-1">
            <!-- Top navigation -->
            <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <!-- Left section: Department info and menu button -->
                        <div class="flex">
                            <button @click="sidebarOpen = true" class="lg:hidden px-4 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500">
                                <span class="sr-only">Open sidebar</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">
                                    {{ auth()->user()->department->name }} - {{ auth()->user()->position }}
                                </span>
                            </div>
                        </div>

                        <!-- Right section: Dark mode and profile -->
                        <div class="flex items-center">
                            <!-- Dark mode toggle -->
                            <button @click="darkMode = !darkMode" class="p-2 text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">
                                <svg x-show="!darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                                </svg>
                                <svg x-show="darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707"/>
                                </svg>
                            </button>

                            <!-- Profile and Logout Icons -->
                            <div class="ml-3 flex items-center space-x-2">
                                <!-- Logout Button -->
                                <form method="POST" action="{{ route('logout') }}" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="flex items-center justify-center h-8 w-8 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 p-0 hover-transition"
                                            title="Logout">
                                        <svg class="h-5 w-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main content -->
            <main class="flex-1">
                <div class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        @if(session('error'))
                            <div class="mb-6">
                                <div class="rounded-md bg-red-50 dark:bg-red-900/40 border border-red-100 dark:border-red-800 p-4 text-red-700 dark:text-red-200">
                                    {{ session('error') }}
                                </div>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="mb-6">
                                <div class="rounded-md bg-green-50 dark:bg-green-900/40 border border-green-100 dark:border-green-800 p-4 text-green-700 dark:text-green-200">
                                    {{ session('success') }}
                                </div>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="mt-auto bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <p class="text-center text-sm text-gray-500 dark:text-gray-400">© {{ date('Y') }} Atommart. All rights reserved.</p>
                </div>
            </footer>
        </div>
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
