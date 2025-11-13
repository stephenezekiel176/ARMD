@extends('layouts.admin')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Send Message</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Send a message to facilitators</p>
        </div>
        <a href="{{ route('admin.messages.index') }}" class="text-sm text-primary-600 hover:text-primary-700">
            Back to Messages
        </a>
    </div>

    <form action="{{ route('admin.messages.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Recipient Selection -->
        <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Recipients</h3>
            
            <div class="space-y-3">
                <label class="flex items-center">
                    <input type="radio" name="recipient_type" value="all" 
                           class="form-radio h-4 w-4 text-primary-600" checked>
                    <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        All Facilitators
                    </span>
                    <span class="ml-2 text-sm text-gray-500">({{ $facilitators->count() }} total)</span>
                </label>

                <label class="flex items-center">
                    <input type="radio" name="recipient_type" value="individual" 
                           class="form-radio h-4 w-4 text-primary-600"
                           id="individual-radio">
                    <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Individual Facilitator
                    </span>
                </label>

                <div id="recipient-select" class="hidden">
                    <select name="recipient_id" 
                            class="mt-2 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Select a facilitator...</option>
                        @foreach($facilitators as $facilitator)
                            <option value="{{ $facilitator->id }}">
                                {{ $facilitator->fullname }} - {{ $facilitator->department->name ?? 'No Department' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Message Content -->
        <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Message Content</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Subject (Optional)
                    </label>
                    <input type="text" name="subject" maxlength="255"
                           class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 focus:ring-primary-500 focus:border-primary-500"
                           placeholder="Enter message subject...">
                    @error('subject')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Message *
                    </label>
                    <textarea name="body" rows="8" required
                              class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 px-3 py-2 focus:ring-primary-500 focus:border-primary-500"
                              placeholder="Type your message here..."></textarea>
                    @error('body')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <!-- Quick Templates -->
        <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Quick Templates</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <button type="button" onclick="useTemplate('announcement')" 
                        class="text-left p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-white dark:hover:bg-gray-800 transition">
                    <div class="font-medium text-gray-900 dark:text-gray-100">General Announcement</div>
                    <div class="text-sm text-gray-500">Send a general announcement to all facilitators</div>
                </button>

                <button type="button" onclick="useTemplate('training')" 
                        class="text-left p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-white dark:hover:bg-gray-800 transition">
                    <div class="font-medium text-gray-900 dark:text-gray-100">Training Reminder</div>
                    <div class="text-sm text-gray-500">Remind facilitators about upcoming training</div>
                </button>

                <button type="button" onclick="useTemplate('performance')" 
                        class="text-left p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-white dark:hover:bg-gray-800 transition">
                    <div class="font-medium text-gray-900 dark:text-gray-100">Performance Update</div>
                    <div class="text-sm text-gray-500">Share performance metrics and updates</div>
                </button>

                <button type="button" onclick="useTemplate('welcome')" 
                        class="text-left p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-white dark:hover:bg-gray-800 transition">
                    <div class="font-medium text-gray-900 dark:text-gray-100">Welcome Message</div>
                    <div class="text-sm text-gray-500">Welcome new facilitators to the platform</div>
                </button>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('admin.messages.index') }}" 
               class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-primary-600 text-white text-sm font-medium rounded-md hover:bg-primary-700 focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                Send Message
            </button>
        </div>
    </form>
</div>

<script>
// Handle recipient type toggle
document.getElementById('individual-radio').addEventListener('change', function() {
    const selectDiv = document.getElementById('recipient-select');
    if (this.checked) {
        selectDiv.classList.remove('hidden');
    } else {
        selectDiv.classList.add('hidden');
    }
});

// Handle "All Facilitators" radio
document.querySelector('input[value="all"]').addEventListener('change', function() {
    const selectDiv = document.getElementById('recipient-select');
    if (this.checked) {
        selectDiv.classList.add('hidden');
    }
});

// Template function
function useTemplate(type) {
    const subjectField = document.querySelector('input[name="subject"]');
    const bodyField = document.querySelector('textarea[name="body"]');
    
    const templates = {
        announcement: {
            subject: 'Important Announcement',
            body: 'Dear Facilitators,\n\nWe have an important announcement to share with you.\n\n[Your message here]\n\nPlease review this information and take any necessary actions.\n\nBest regards,\nAdministration'
        },
        training: {
            subject: 'Training Session Reminder',
            body: 'Dear Facilitators,\n\nThis is a reminder about the upcoming training session scheduled for [Date/Time].\n\nPlease ensure you attend this important training as it covers [topics].\n\nLocation: [Location/Online]\n\nIf you have any questions, please don\'t hesitate to reach out.\n\nBest regards,\nTraining Department'
        },
        performance: {
            subject: 'Performance Metrics Update',
            body: 'Dear Facilitators,\n\nWe are pleased to share the latest performance metrics for your departments.\n\nKey highlights:\n- [Metric 1]: [Value]\n- [Metric 2]: [Value]\n- [Metric 3]: [Value]\n\nContinue the excellent work! Your dedication to training and development is greatly appreciated.\n\nBest regards,\nAdministration'
        },
        welcome: {
            subject: 'Welcome to Atommart LMS',
            body: 'Dear Facilitator,\n\nWelcome to the Atommart Learning Management System! We are excited to have you join our team of dedicated facilitators.\n\nAs a facilitator, you will be able to:\n- Create and manage courses\n- Track personnel progress\n- Send messages to your department\n- Generate reports\n\nPlease take some time to familiarize yourself with the platform. If you need any assistance, our support team is here to help.\n\nWe look forward to working with you!\n\nBest regards,\nAdministration'
        }
    };
    
    if (templates[type]) {
        subjectField.value = templates[type].subject;
        bodyField.value = templates[type].body;
    }
}
</script>
@endsection

