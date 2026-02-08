@extends('layouts.admin')

@section('title', 'Inquiry #' . $inquiry->ticket_number)
@section('page-title', 'Inquiry Details')
@section('page-subtitle', 'View and manage inquiry')

@section('page-content')
<!-- Back Button & Header -->
<div class="mb-6">
    <a href="{{ URL::signedRoute('admin.inquiries.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium mb-4">
        <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
        Back to Inquiries
    </a>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $inquiry->subject }}</h1>
            <p class="text-gray-500 mt-1 font-mono text-sm">{{ $inquiry->ticket_number }}</p>
        </div>
        <div class="mt-3 sm:mt-0 flex items-center gap-2">
            @switch($inquiry->status)
                @case('new')
                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">New</span>
                    @break
                @case('open')
                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">Open</span>
                    @break
                @case('pending')
                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-orange-100 text-orange-800">Pending</span>
                    @break
                @case('resolved')
                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-green-100 text-green-800">Resolved</span>
                    @break
                @case('closed')
                    <span class="px-4 py-2 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">Closed</span>
                    @break
            @endswitch
            @switch($inquiry->priority)
                @case('urgent')
                    <span class="px-3 py-1 text-xs font-semibold rounded bg-red-100 text-red-800">Urgent</span>
                    @break
                @case('high')
                    <span class="px-3 py-1 text-xs font-semibold rounded bg-orange-100 text-orange-800">High</span>
                    @break
                @case('normal')
                    <span class="px-3 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800">Normal</span>
                    @break
                @case('low')
                    <span class="px-3 py-1 text-xs font-semibold rounded bg-gray-100 text-gray-800">Low</span>
                    @break
            @endswitch
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Citizen Message -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i data-lucide="message-square" class="w-5 h-5 mr-2 text-blue-600"></i>
                Citizen Message
            </h2>
            <div class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $inquiry->message }}</div>
        </div>

        <!-- Attachments -->
        @if($inquiry->attachments && count($inquiry->attachments) > 0)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i data-lucide="paperclip" class="w-5 h-5 mr-2 text-blue-600"></i>
                Attachments ({{ count($inquiry->attachments) }})
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

        <!-- Staff Notes -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i data-lucide="sticky-note" class="w-5 h-5 mr-2 text-yellow-600"></i>
                Staff Notes
            </h2>
            @if($inquiry->staff_notes)
            <div class="text-gray-700 leading-relaxed whitespace-pre-line bg-yellow-50 p-4 rounded-lg mb-4 text-sm">{{ $inquiry->staff_notes }}</div>
            @else
            <p class="text-gray-400 text-sm mb-4">No staff notes yet.</p>
            @endif

            @if(!in_array($inquiry->status, ['closed']))
            <form action="{{ URL::signedRoute('admin.inquiries.note', $inquiry->id) }}" method="POST">
                @csrf
                <textarea name="note" rows="3" required placeholder="Add a note..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                <button type="submit" class="mt-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-4 py-2 rounded-lg transition text-sm">
                    <i data-lucide="plus" class="w-4 h-4 inline-block mr-1"></i> Add Note
                </button>
            </form>
            @endif
        </div>

        <!-- Resolution -->
        @if($inquiry->resolution)
        <div class="bg-green-50 rounded-lg shadow-md p-6 border border-green-200">
            <h2 class="text-lg font-semibold text-green-800 mb-3 flex items-center">
                <i data-lucide="check-circle" class="w-5 h-5 mr-2 text-green-600"></i>
                Resolution
            </h2>
            <div class="text-green-800 leading-relaxed whitespace-pre-line">{{ $inquiry->resolution }}</div>
            @if($inquiry->resolved_at)
            <p class="text-sm text-green-600 mt-3">Resolved on {{ $inquiry->resolved_at->format('F j, Y \\a\\t g:i A') }}</p>
            @endif
        </div>
        @endif

        <!-- Resolve Form -->
        @if(!in_array($inquiry->status, ['resolved', 'closed']))
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <i data-lucide="check-circle" class="w-5 h-5 mr-2 text-green-600"></i>
                Resolve Inquiry
            </h2>
            <form action="{{ URL::signedRoute('admin.inquiries.resolve', $inquiry->id) }}" method="POST">
                @csrf
                <textarea name="resolution" rows="4" required placeholder="Enter the resolution details..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent"></textarea>
                <button type="submit" class="mt-2 bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg transition text-sm">
                    <i data-lucide="check" class="w-4 h-4 inline-block mr-1"></i> Mark as Resolved
                </button>
            </form>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Inquiry Details -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Inquiry Details</h3>
            <div class="space-y-4 text-sm">
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Ticket Number</p>
                    <p class="font-mono font-semibold text-gray-800 mt-1">{{ $inquiry->ticket_number }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Category</p>
                    <p class="text-gray-800 mt-1">{{ ucfirst(str_replace('_', ' ', $inquiry->category)) }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Submitted</p>
                    <p class="text-gray-800 mt-1">{{ $inquiry->created_at->format('F j, Y') }}</p>
                    <p class="text-xs text-gray-500">{{ $inquiry->created_at->format('g:i A') }} ({{ $inquiry->created_at->diffForHumans() }})</p>
                </div>
                @if($inquiry->assigned_at)
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Assigned At</p>
                    <p class="text-gray-800 mt-1">{{ $inquiry->assigned_at->format('F j, Y g:i A') }}</p>
                </div>
                @endif
                @if($inquiry->resolved_at)
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Resolved At</p>
                    <p class="text-gray-800 mt-1">{{ $inquiry->resolved_at->format('F j, Y g:i A') }}</p>
                </div>
                @endif
                @if($inquiry->closed_at)
                <div>
                    <p class="text-xs font-medium text-gray-500 uppercase">Closed At</p>
                    <p class="text-gray-800 mt-1">{{ $inquiry->closed_at->format('F j, Y g:i A') }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Citizen Info -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Citizen Info</h3>
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

        <!-- Quick Actions -->
        @if(!in_array($inquiry->status, ['closed']))
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <!-- Assign -->
                <form action="{{ URL::signedRoute('admin.inquiries.assign', $inquiry->id) }}" method="POST">
                    @csrf
                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Assign To</label>
                    <select name="assigned_to" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm mb-2">
                        <option value="">Select staff member</option>
                        @foreach($staffMembers as $staff)
                        <option value="{{ $staff->id }}" {{ $inquiry->assigned_to == $staff->id ? 'selected' : '' }}>
                            {{ $staff->full_name }} ({{ ucfirst($staff->role) }})
                        </option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg transition text-sm">
                        <i data-lucide="user-check" class="w-4 h-4 inline-block mr-1"></i> Assign
                    </button>
                </form>

                <!-- Update Status -->
                <form action="{{ URL::signedRoute('admin.inquiries.status', $inquiry->id) }}" method="POST" class="mt-3">
                    @csrf
                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Update Status</label>
                    <select name="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm mb-2">
                        <option value="new" {{ $inquiry->status == 'new' ? 'selected' : '' }}>New</option>
                        <option value="open" {{ $inquiry->status == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="pending" {{ $inquiry->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="resolved" {{ $inquiry->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="closed" {{ $inquiry->status == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                    <button type="submit" class="w-full bg-gray-600 hover:bg-gray-700 text-white font-semibold px-4 py-2 rounded-lg transition text-sm">
                        <i data-lucide="refresh-cw" class="w-4 h-4 inline-block mr-1"></i> Update Status
                    </button>
                </form>

                <!-- Update Priority -->
                <form action="{{ URL::signedRoute('admin.inquiries.priority', $inquiry->id) }}" method="POST" class="mt-3">
                    @csrf
                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Update Priority</label>
                    <select name="priority" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm mb-2">
                        <option value="low" {{ $inquiry->priority == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="normal" {{ $inquiry->priority == 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="high" {{ $inquiry->priority == 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ $inquiry->priority == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                    <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold px-4 py-2 rounded-lg transition text-sm">
                        <i data-lucide="alert-triangle" class="w-4 h-4 inline-block mr-1"></i> Update Priority
                    </button>
                </form>

                <!-- Close Inquiry -->
                @if($inquiry->status == 'resolved')
                <form action="{{ URL::signedRoute('admin.inquiries.close', $inquiry->id) }}" method="POST" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-semibold px-4 py-2 rounded-lg transition text-sm"
                        onclick="return confirm('Are you sure you want to close this inquiry?')">
                        <i data-lucide="archive" class="w-4 h-4 inline-block mr-1"></i> Close Inquiry
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

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
