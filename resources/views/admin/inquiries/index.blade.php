@extends('layouts.admin')

@section('title', 'Inquiry Management')
@section('page-title', 'Inquiry Management')
@section('page-subtitle', 'Manage citizen support tickets and inquiries')

@section('page-content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
    <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-indigo-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Total</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['total'] }}</p>
            </div>
            <i data-lucide="inbox" class="w-8 h-8 text-indigo-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">New</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['new'] }}</p>
            </div>
            <i data-lucide="plus-circle" class="w-8 h-8 text-blue-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-yellow-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Open</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['open'] }}</p>
            </div>
            <i data-lucide="clock" class="w-8 h-8 text-yellow-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-red-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Urgent</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['urgent'] }}</p>
            </div>
            <i data-lucide="alert-triangle" class="w-8 h-8 text-red-500"></i>
        </div>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-4 border-l-4 border-gray-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600">Unassigned</p>
                <p class="text-2xl font-bold text-gray-800">{{ $stats['unassigned'] }}</p>
            </div>
            <i data-lucide="user-x" class="w-8 h-8 text-gray-500"></i>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form method="GET" action="{{ URL::signedRoute('admin.inquiries.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Ticket, name, email..."
                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="all">All Status</option>
                <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
            <select name="priority" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="all">All Priority</option>
                <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
            <select name="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm">
                <option value="all">All Categories</option>
                <option value="general" {{ request('category') == 'general' ? 'selected' : '' }}>General</option>
                <option value="booking_issue" {{ request('category') == 'booking_issue' ? 'selected' : '' }}>Booking Issue</option>
                <option value="payment_issue" {{ request('category') == 'payment_issue' ? 'selected' : '' }}>Payment Issue</option>
                <option value="technical_issue" {{ request('category') == 'technical_issue' ? 'selected' : '' }}>Technical Issue</option>
                <option value="complaint" {{ request('category') == 'complaint' ? 'selected' : '' }}>Complaint</option>
                <option value="suggestion" {{ request('category') == 'suggestion' ? 'selected' : '' }}>Suggestion</option>
                <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>
        <div class="flex items-end gap-2 md:col-span-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition text-sm">
                <i data-lucide="search" class="w-4 h-4 inline-block mr-1"></i> Filter
            </button>
            <a href="{{ URL::signedRoute('admin.inquiries.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-4 py-2 rounded-lg transition text-sm">
                Reset
            </a>
        </div>
    </form>
</div>

<!-- Inquiries Table -->
@if($inquiries->count() > 0)
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ticket</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Citizen</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subject</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($inquiries as $inquiry)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="font-mono text-sm font-semibold text-blue-600">{{ $inquiry->ticket_number }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $inquiry->name }}</div>
                        <div class="text-xs text-gray-500">{{ $inquiry->email }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ Str::limit($inquiry->subject, 40) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ ucfirst(str_replace('_', ' ', $inquiry->category)) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @switch($inquiry->status)
                            @case('new')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">New</span>
                                @break
                            @case('open')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Open</span>
                                @break
                            @case('pending')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">Pending</span>
                                @break
                            @case('resolved')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Resolved</span>
                                @break
                            @case('closed')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Closed</span>
                                @break
                        @endswitch
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @switch($inquiry->priority)
                            @case('urgent')
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800">Urgent</span>
                                @break
                            @case('high')
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-orange-100 text-orange-800">High</span>
                                @break
                            @case('normal')
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800">Normal</span>
                                @break
                            @case('low')
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-gray-100 text-gray-800">Low</span>
                                @break
                        @endswitch
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $inquiry->created_at->format('M j, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="{{ URL::signedRoute('admin.inquiries.show', $inquiry->id) }}" 
                            class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                            <i data-lucide="eye" class="w-4 h-4 mr-1"></i> View
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="mt-6">
    {{ $inquiries->appends(request()->query())->links() }}
</div>
@else
<div class="bg-white rounded-lg shadow-md p-12 text-center">
    <i data-lucide="inbox" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
    <p class="text-gray-600 text-lg">No inquiries found.</p>
    <p class="text-gray-400 text-sm mt-1">Citizen inquiries will appear here when submitted.</p>
</div>
@endif

@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
            timer: 2000,
            showConfirmButton: false
        });
    });
</script>
@endif
@endsection
