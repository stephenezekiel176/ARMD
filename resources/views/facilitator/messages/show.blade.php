@extends('layouts.facilitator')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Message Details</h1>
        <a href="{{ route('facilitator.messages.index') }}" class="text-sm text-primary-600 hover:underline">
            Back to History
        </a>
    </div>

    <div class="space-y-6">
        <!-- Message Header -->
        <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
            <div class="flex items-center justify-between mb-2">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                    {{ $message->subject ?? 'No Subject' }}
                </h2>
                @if($message->recipient_id)
                    <span class="text-xs px-2 py-1 rounded bg-blue-100 text-blue-700">Personal</span>
                @else
                    <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-700">Group</span>
                @endif
            </div>
            
            <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
                <div>
                    @if($message->recipient_id)
                        <span class="font-medium">To:</span> {{ $message->recipient->fullname }} ({{ $message->recipient->position }})
                    @else
                        <span class="font-medium">To:</span> {{ $message->department->name }} Department
                    @endif
                </div>
                <div>
                    {{ $message->created_at->format('M d, Y \a\t g:i A') }}
                </div>
            </div>
        </div>

        <!-- Message Body -->
        <div class="prose dark:prose-invert max-w-none">
            <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                <p class="whitespace-pre-wrap text-gray-700 dark:text-gray-300">{{ $message->body }}</p>
            </div>
        </div>

        <!-- Message Actions -->
        <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="text-sm text-gray-500">
                Message ID: #{{ $message->id }}
            </div>
            <div class="space-x-3">
                <button type="button" onclick="copyMessage()" 
                        class="px-3 py-1 text-sm border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                    Copy Message
                </button>
                <a href="{{ route('facilitator.messages.create') }}?template={{ $message->id }}" 
                   class="px-3 py-1 text-sm bg-primary-600 text-white rounded hover:bg-primary-700">
                    Send Similar
                </a>
            </div>
        </div>

        <!-- Delivery Status -->
        <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
            <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-2">Delivery Information</h3>
            <div class="text-sm text-blue-700 dark:text-blue-300">
                @if($message->recipient_id)
                    <p>• Sent to: {{ $message->recipient->fullname }}</p>
                    <p>• Email: {{ $message->recipient->email }}</p>
                @else
                    <p>• Sent to all personnel in {{ $message->department->name }} department</p>
                    <p>• Total recipients: {{ \App\Models\User::where('role', 'personnel')->where('department_id', $message->department_id)->count() }} personnel</p>
                @endif
                <p>• Sent via: Internal messaging system</p>
                <p>• Status: Delivered</p>
            </div>
        </div>
    </div>
</div>

<script>
function copyMessage() {
    const subject = '{{ $message->subject ?? 'No Subject' }}';
    const body = `{{ $message->body }}`;
    const fullText = `Subject: ${subject}\n\n${body}`;
    
    navigator.clipboard.writeText(fullText).then(function() {
        // Show success feedback
        const button = event.target;
        const originalText = button.textContent;
        button.textContent = 'Copied!';
        button.classList.add('bg-green-100', 'text-green-700');
        
        setTimeout(function() {
            button.textContent = originalText;
            button.classList.remove('bg-green-100', 'text-green-700');
        }, 2000);
    });
}
</script>
@endsection
