@extends('layouts.admin')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Message Details</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View message information</p>
        </div>
        <a href="{{ route('admin.messages.index') }}" class="text-sm text-primary-600 hover:text-primary-700">
            Back to Messages
        </a>
    </div>

    <!-- Message Header -->
    <div class="bg-gray-50 dark:bg-gray-900 p-6 rounded-lg mb-6">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                    {{ $message->subject ?: 'No Subject' }}
                </h3>
                <div class="space-y-2">
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">From:</span>
                        <div class="flex items-center space-x-2">
                            <div class="h-8 w-8 rounded-full bg-primary-600 flex items-center justify-center">
                                <span class="text-white text-sm font-medium">{{ substr($message->sender->fullname, 0, 1) }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $message->sender->fullname }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">({{ $message->sender->role }})</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">To:</span>
                        <div class="flex items-center space-x-2">
                            <div class="h-8 w-8 rounded-full bg-green-600 flex items-center justify-center">
                                <span class="text-white text-sm font-medium">{{ substr($message->recipient->fullname, 0, 1) }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $message->recipient->fullname }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">({{ $message->recipient->role }})</span>
                                @if($message->recipient->department)
                                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">{{ $message->recipient->department->name }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Sent:</span>
                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $message->created_at->format('F j, Y \a\t g:i A') }}</span>
                    </div>
                </div>
            </div>
            
            <div class="flex space-x-2">
                <button onclick="copyMessage()" class="p-2 text-gray-400 hover:text-primary-600 transition" title="Copy message">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                </button>
                <a href="{{ route('admin.messages.create') }}?template={{ $message->id }}" 
                   class="p-2 text-gray-400 hover:text-primary-600 transition" 
                   title="Send similar message">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Message Content -->
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6 mb-6">
        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Message Content</h4>
        <div class="prose dark:prose-invert max-w-none">
            <div class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $message->body }}</div>
        </div>
    </div>

    <!-- Message Actions -->
    <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-700">
        <div class="text-sm text-gray-500 dark:text-gray-400">
            Message ID: #{{ $message->id }}
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.messages.create') }}?template={{ $message->id }}" 
               class="px-4 py-2 bg-primary-600 text-white text-sm rounded-md hover:bg-primary-700 transition">
                Send Similar Message
            </a>
            <form action="{{ route('admin.messages.destroy', $message) }}" 
                  method="POST" 
                  onsubmit="return confirm('Are you sure you want to delete this message? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 transition">
                    Delete Message
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function copyMessage() {
    const subject = '{{ $message->subject ?? "No Subject" }}';
    const body = `{{ $message->body }}`;
    const from = 'From: {{ $message->sender->fullname }}';
    const to = 'To: {{ $message->recipient->fullname }}';
    const date = 'Date: {{ $message->created_at->format("F j, Y \a\t g:i A") }}';
    
    const fullMessage = `Subject: ${subject}\n${from}\n${to}\n${date}\n\n${body}`;
    
    navigator.clipboard.writeText(fullMessage).then(function() {
        // Show success feedback
        const button = event.currentTarget;
        const originalHTML = button.innerHTML;
        button.innerHTML = '<svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
        button.title = 'Copied!';
        
        setTimeout(function() {
            button.innerHTML = originalHTML;
            button.title = 'Copy message';
        }, 2000);
    }).catch(function(err) {
        console.error('Failed to copy message: ', err);
    });
}
</script>
@endsection

