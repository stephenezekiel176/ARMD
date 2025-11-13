@extends('layouts.facilitator')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Compose Message</h1>
        <a href="{{ route('facilitator.messages.index') }}" class="text-sm text-primary-600 hover:underline">
            Back to History
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('facilitator.messages.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Message Type Selection -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Message Type
            </label>
            <div class="flex space-x-4">
                <label class="flex items-center">
                    <input type="radio" name="message_type" value="department" checked 
                           class="mr-2" onchange="toggleRecipientSelection()">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Department-wide</span>
                </label>
                <label class="flex items-center">
                    <input type="radio" name="message_type" value="individual" 
                           class="mr-2" onchange="toggleRecipientSelection()">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Individual Personnel</span>
                </label>
            </div>
        </div>

        <!-- Recipient Selection (for individual messages) -->
        <div id="recipient-selection" class="hidden">
            <label for="recipient_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Select Personnel
            </label>
            <select name="recipient_id" id="recipient_id" 
                    class="w-full rounded border-gray-300 px-3 py-2 dark:bg-gray-700 dark:border-gray-600">
                <option value="">Choose a personnel member...</option>
                @foreach($personnel as $person)
                    <option value="{{ $person->id }}">{{ $person->fullname }} - {{ $person->position }}</option>
                @endforeach
            </select>
        </div>

        <!-- Subject -->
        <div>
            <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Subject (optional)
            </label>
            <input type="text" name="subject" id="subject" 
                   placeholder="Enter message subject..."
                   class="w-full rounded border-gray-300 px-3 py-2 dark:bg-gray-700 dark:border-gray-600">
        </div>

        <!-- Message Body -->
        <div>
            <label for="body" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Message
            </label>
            <textarea name="body" id="body" rows="6" required
                      placeholder="Write your message here..."
                      class="w-full rounded border-gray-300 px-3 py-2 dark:bg-gray-700 dark:border-gray-600"></textarea>
            <div class="mt-1 text-xs text-gray-500">
                {{ 500 - strlen(old('body', '')) }} characters remaining
            </div>
        </div>

        <!-- Message Templates -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Quick Templates
            </label>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                <button type="button" onclick="useTemplate('reminder')" 
                        class="text-left p-2 border rounded text-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                    <div class="font-medium">Course Reminder</div>
                    <div class="text-xs text-gray-500">Reminder about upcoming deadlines</div>
                </button>
                <button type="button" onclick="useTemplate('congratulations')" 
                        class="text-left p-2 border rounded text-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                    <div class="font-medium">Congratulations</div>
                    <div class="text-xs text-gray-500"> congratulate on achievement</div>
                </button>
                <button type="button" onclick="useTemplate('announcement')" 
                        class="text-left p-2 border rounded text-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                    <div class="font-medium">Department Announcement</div>
                    <div class="text-xs text-gray-500">General department announcement</div>
                </button>
                <button type="button" onclick="useTemplate('feedback')" 
                        class="text-left p-2 border rounded text-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                    <div class="font-medium">Feedback Request</div>
                    <div class="text-xs text-gray-500">Request for feedback on courses</div>
                </button>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('facilitator.messages.index') }}" 
               class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" 
                    class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700">
                Send Message
            </button>
        </div>
    </form>
</div>

<script>
function toggleRecipientSelection() {
    const messageType = document.querySelector('input[name="message_type"]:checked').value;
    const recipientSelection = document.getElementById('recipient-selection');
    
    if (messageType === 'individual') {
        recipientSelection.classList.remove('hidden');
        document.getElementById('recipient_id').required = true;
    } else {
        recipientSelection.classList.add('hidden');
        document.getElementById('recipient_id').required = false;
        document.getElementById('recipient_id').value = '';
    }
}

function useTemplate(type) {
    const subject = document.getElementById('subject');
    const body = document.getElementById('body');
    
    const templates = {
        reminder: {
            subject: 'Course Reminder - Upcoming Deadline',
            body: 'This is a friendly reminder that you have upcoming deadlines for your enrolled courses. Please make sure to complete your assessments and submit them on time. If you need any assistance or have questions, please don\'t hesitate to reach out.'
        },
        congratulations: {
            subject: 'Congratulations on Your Achievement!',
            body: 'Congratulations on your excellent performance! Your dedication and hard work have paid off. Keep up the great work and continue striving for excellence in your learning journey.'
        },
        announcement: {
            subject: 'Department Announcement',
            body: 'We have an important announcement for the department. Please review this information carefully and let us know if you have any questions or concerns.'
        },
        feedback: {
            subject: 'Request for Course Feedback',
            body: 'We value your feedback on the courses you\'ve completed. Please share your thoughts and suggestions to help us improve the learning experience for everyone.'
        }
    };
    
    if (templates[type]) {
        subject.value = templates[type].subject;
        body.value = templates[type].body;
        updateCharCount();
    }
}

function updateCharCount() {
    const body = document.getElementById('body');
    const charCount = body.value.length;
    const remaining = 500 - charCount;
    const counter = document.querySelector('.text-xs.text-gray-500');
    counter.textContent = remaining + ' characters remaining';
}

document.getElementById('body').addEventListener('input', updateCharCount);
</script>
@endsection
