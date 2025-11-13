@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded">
    <div class="flex items-center justify-between">
    </div>

    @php
        $user = auth()->user();
        $messages = \App\Models\Message::with('sender')
            ->where(function($q) use ($user) {
                $q->where('recipient_id', $user->id)
                  ->orWhere('department_id', $user->department_id);
            })
            ->latest()
            ->get();
    @endphp

    @if($messages->count() > 0)
        <div class="space-y-4">
            @foreach($messages as $message)
                <div class="border rounded-lg border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <h3 class="font-semibold text-gray-800 dark:text-gray-100">
                                    {{ $message->subject ?? 'Important Message' }}
                                </h3>
                                @if($message->recipient_id)
                                    <span class="text-xs px-2 py-1 rounded bg-blue-100 text-blue-700">Personal</span>
                                @else
                                    <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-700">Department</span>
                                @endif
                            </div>
                            
                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                From: {{ $message->sender->fullname }} ({{ $message->sender->position }})
                            </div>
                            
                            <div class="prose dark:prose-invert max-w-none">
                                <p class="whitespace-pre-wrap text-gray-700 dark:text-gray-300">{{ $message->body }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-600">
                        <div class="text-xs text-gray-500">
                            Sent {{ $message->created_at->format('M d, Y \a\t g:i A') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No instructions yet</h3>
            <p class="mt-1 text-sm text-gray-500">Your facilitator hasn't sent any messages yet.</p>
            <div class="mt-6">
                <a href="{{ route('personnel.dashboard') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                    Back to Dashboard
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
