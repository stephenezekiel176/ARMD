@extends('layouts.facilitator')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Message History</h1>
        <a href="{{ route('facilitator.messages.create') }}" class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700">
            Compose New Message
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-4">
        @forelse($messages as $message)
            <div class="p-4 border rounded border-gray-200 dark:border-gray-700 hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-2">
                            <h3 class="font-semibold text-gray-800 dark:text-gray-100">
                                {{ $message->subject ?? 'No Subject' }}
                            </h3>
                            @if($message->recipient_id)
                                <span class="text-xs px-2 py-1 rounded bg-blue-100 text-blue-700">Personal</span>
                            @else
                                <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-700">Group</span>
                            @endif
                        </div>
                        
                        <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                            @if($message->recipient_id)
                                To: {{ $message->recipient->fullname }}
                            @else
                                To: {{ $message->department->name }} Department
                            @endif
                        </div>
                        
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            {{ Str::limit(strip_tags($message->body), 150) }}
                        </div>
                        
                        <div class="text-xs text-gray-500 mt-2">
                            Sent {{ $message->created_at->diffForHumans() }}
                        </div>
                    </div>
                    
                    <div class="ml-4">
                        <a href="{{ route('facilitator.messages.show', $message->id) }}" 
                           class="text-sm text-primary-600 hover:underline">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500">
                <div class="mb-4">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <p>No messages sent yet.</p>
                <a href="{{ route('facilitator.messages.create') }}" class="mt-2 inline-block text-primary-600 hover:underline">
                    Send your first message
                </a>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $messages->links() }}
    </div>
</div>
@endsection
