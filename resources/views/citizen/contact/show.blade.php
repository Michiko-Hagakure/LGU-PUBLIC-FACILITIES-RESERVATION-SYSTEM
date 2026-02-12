@extends('layouts.citizen')

@section('title', 'Inquiry #' . $inquiry->ticket_number)
@section('page-title', 'Inquiry Details')
@section('page-subtitle', 'View your inquiry details')

@section('page-content')
<!-- Header -->
<div class="mb-6">
    <a href="{{ URL::signedRoute('citizen.contact.my-inquiries') }}" 
       class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium mb-4">
        <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
        Back to My Inquiries
    </a>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $inquiry->subject }}</h1>
            <p class="text-gray-500 mt-1 font-mono text-sm">Ticket: {{ $inquiry->ticket_number }}</p>
        </div>
        <div class="mt-3 sm:mt-0">
            @switch($inquiry->status)
                @case('new')
                    <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-full bg-blue-100 text-blue-800">New</span>
                    @break
                @case('open')
                    <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">Open</span>
                    @break
                @case('pending')
                    <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-full bg-orange-100 text-orange-800">Pending</span>
                    @break
                @case('resolved')
                    <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">Resolved</span>
                    @break
                @case('closed')
                    <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-full bg-gray-100 text-gray-800">Closed</span>
                    @break
            @endswitch
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Content -->
    <div class="lg:col-span-2">
        <!-- Message -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i data-lucide="message-square" class="w-5 h-5 mr-2 text-blue-600"></i>
                Your Message
            </h2>
            <div class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $inquiry->message }}</div>
        </div>

        <!-- Attachments -->
        @if($inquiry->attachments && count($inquiry->attachments) > 0)
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i data-lucide="paperclip" class="w-5 h-5 mr-2 text-blue-600"></i>
                Attachments
            </h2>
            <div class="space-y-2">
                @foreach($inquiry->attachments as $attachment)
                <a href="{{ asset('storage/' . $attachment['path']) }}" target="_blank"
                   class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <i data-lucide="file" class="w-5 h-5 mr-3 text-gray-500"></i>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-700">{{ $attachment['name'] }}</p>
                        <p class="text-xs text-gray-500">{{ number_format(($attachment['size'] ?? 0) / 1024, 1) }} KB</p>
                    </div>
                    <i data-lucide="download" class="w-4 h-4 text-gray-400"></i>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Resolution -->
        @if($inquiry->resolution)
        <div class="bg-green-50 rounded-lg shadow-md p-6 mb-6 border border-green-200">
            <h2 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
                <i data-lucide="check-circle" class="w-5 h-5 mr-2 text-green-600"></i>
                Resolution
            </h2>
            <div class="text-green-800 leading-relaxed whitespace-pre-line">{{ $inquiry->resolution }}</div>
            @if($inquiry->resolved_at)
            <p class="text-sm text-green-600 mt-3">Resolved on {{ $inquiry->resolved_at->format('F j, Y \\a\\t g:i A') }}</p>
            @endif
        </div>
        @endif

        <!-- Staff Notes (if any visible to citizen) -->
        @if($inquiry->staff_notes && $inquiry->status === 'resolved')
        <div class="bg-blue-50 rounded-lg shadow-md p-6 border border-blue-200">
            <h2 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
                <i data-lucide="info" class="w-5 h-5 mr-2 text-blue-600"></i>
                Staff Notes
            </h2>
            <div class="text-blue-800 leading-relaxed whitespace-pre-line">{{ $inquiry->staff_notes }}</div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1">
        <!-- Inquiry Details -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="font-semibold text-gray-800 mb-4">Inquiry Details</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Category</p>
                    <p class="text-sm text-gray-800 mt-1">{{ ucfirst(str_replace('_', ' ', $inquiry->category)) }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Priority</p>
                    <div class="mt-1">
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
                    </div>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Submitted</p>
                    <p class="text-sm text-gray-800 mt-1">{{ $inquiry->created_at->format('F j, Y') }}</p>
                    <p class="text-xs text-gray-500">{{ $inquiry->created_at->format('g:i A') }}</p>
                </div>
                @if($inquiry->assigned_at)
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Assigned</p>
                    <p class="text-sm text-gray-800 mt-1">{{ $inquiry->assigned_at->format('F j, Y') }}</p>
                </div>
                @endif
                @if($inquiry->resolved_at)
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Resolved</p>
                    <p class="text-sm text-gray-800 mt-1">{{ $inquiry->resolved_at->format('F j, Y') }}</p>
                </div>
                @endif
                @if($inquiry->closed_at)
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Closed</p>
                    <p class="text-sm text-gray-800 mt-1">{{ $inquiry->closed_at->format('F j, Y') }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Contact Info -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="font-semibold text-gray-800 mb-4">Contact Info</h3>
            <div class="space-y-3">
                <div class="flex items-center text-sm">
                    <i data-lucide="user" class="w-4 h-4 mr-3 text-gray-400"></i>
                    <span class="text-gray-700">{{ $inquiry->name }}</span>
                </div>
                <div class="flex items-center text-sm">
                    <i data-lucide="mail" class="w-4 h-4 mr-3 text-gray-400"></i>
                    <span class="text-gray-700">{{ $inquiry->email }}</span>
                </div>
                @if($inquiry->phone)
                <div class="flex items-center text-sm">
                    <i data-lucide="phone" class="w-4 h-4 mr-3 text-gray-400"></i>
                    <span class="text-gray-700">{{ $inquiry->phone }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Need Help -->
        <div class="bg-blue-50 rounded-lg p-6 text-center">
            <i data-lucide="help-circle" class="w-8 h-8 text-blue-600 mx-auto mb-2"></i>
            <p class="text-sm text-gray-700 mb-3">Need further assistance?</p>
            <a href="{{ URL::signedRoute('citizen.contact.index') }}" 
               class="block w-full px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition text-sm">
                Submit New Inquiry
            </a>
        </div>
    </div>
</div>
@endsection
