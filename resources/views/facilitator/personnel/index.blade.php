@extends('layouts.facilitator')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded">
    <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Personnel</h1>
    <p class="mt-2 text-sm text-gray-500">List of personnel in your department.</p>

    <div class="mt-4 flex items-center space-x-3">
        <form action="{{ route('facilitator.messages.store') }}" method="POST" class="w-full max-w-xl">
            @csrf
            <input type="hidden" name="recipient_id" value="">
            <div class="flex items-center space-x-2">
                <input name="subject" placeholder="Subject (optional)" class="flex-1 rounded border-gray-300 px-3 py-2 dark:bg-gray-700 dark:border-gray-600" />
                <button type="button" @click="$el.nextElementSibling.classList.toggle('hidden')" class="px-3 py-2 bg-gray-100 rounded dark:bg-gray-700">Message</button>
            </div>
            <div class="hidden mt-2">
                <textarea name="body" rows="3" class="w-full rounded border-gray-300 px-3 py-2 dark:bg-gray-700 dark:border-gray-600" placeholder="Write a group message to department or choose a person below to send personally"></textarea>
                <div class="mt-2 text-right">
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded">Send Group</button>
                </div>
            </div>
        </form>
    </div>

    <div class="mt-4 divide-y divide-gray-100 dark:divide-gray-700">
        @foreach($personnel as $person)
            <div class="py-3 flex items-center justify-between">
                <div>
                    <div class="font-medium text-gray-800 dark:text-gray-100">{{ $person->fullname }}</div>
                    <div class="text-sm text-gray-500">{{ $person->position ?? 'Personnel' }}</div>
                    <div class="text-xs text-gray-400">Courses: {{ $person->enrollments->count() }} • Points: {{ $person->points }}</div>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('facilitator.personnel.show', $person->id) }}" class="text-sm text-primary-600 hover:underline">View Details</a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $personnel->links() }}
    </div>
</div>
@endsection
