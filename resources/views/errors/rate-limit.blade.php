@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <div class="mx-auto h-12 w-12 text-red-500">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-white">
                Rate Limit Exceeded
            </h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Too many requests. Please try again later.
            </p>
        </div>
        
        <div class="mt-8 bg-white dark:bg-gray-800 py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <div class="space-y-4">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    <p class="mb-2">You've exceeded the allowed number of requests for this time period.</p>
                    <p>This helps us maintain service quality for all users.</p>
                </div>
                
                <div class="pt-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                        What you can do:
                    </p>
                    <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                        <li>• Wait a few minutes and try again</li>
                        <li>• Reduce the frequency of your requests</li>
                        <li>• Contact support if you need higher limits</li>
                    </ul>
                </div>
            </div>
            
            <div class="mt-6">
                <a href="{{ url('/') }}" 
                   class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Go to Homepage
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
