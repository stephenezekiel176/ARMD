@extends('layouts.admin')
@section('header', 'Ratings Management')
@section('content')
<div class="space-y-6">
    <form method="GET" class="flex justify-end space-x-4">
        <select name="is_approved" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-sm">
            <option value="">All</option>
            <option value="1">Approved</option>
            <option value="0">Pending</option>
        </select>
        <select name="rating_type" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-sm">
            <option value="">All Types</option>
            <option value="personnel_to_facilitator">Personnel to Facilitator</option>
            <option value="facilitator_to_personnel">Facilitator to Personnel</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md">Filter</button>
    </form>
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rater</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rated</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Overall Rating</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($ratings as $rating)
                <tr>
                    <td class="px-6 py-4">{{ $rating->rater->fullname }}</td>
                    <td class="px-6 py-4">{{ $rating->rated->fullname }}</td>
                    <td class="px-6 py-4 text-sm">{{ str_replace('_', ' ', ucfirst($rating->rating_type)) }}</td>
                    <td class="px-6 py-4">{{ $rating->overall_rating }}/5</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $rating->is_approved ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $rating->is_approved ? 'Approved' : 'Pending' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        @if(!$rating->is_approved)
                        <form action="{{ route('admin.ratings.approve', $rating) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-green-600">Approve</button>
                        </form>
                        <form action="{{ route('admin.ratings.reject', $rating) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-red-600">Reject</button>
                        </form>
                        @endif
                        <form action="{{ route('admin.ratings.destroy', $rating) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete?')" class="text-red-600">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">No ratings found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $ratings->links() }}</div>
</div>
@endsection
