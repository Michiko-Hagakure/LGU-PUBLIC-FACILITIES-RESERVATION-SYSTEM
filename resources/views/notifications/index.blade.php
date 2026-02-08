@extends('layouts.' . match(strtolower(session('user_role', 'citizen'))) {
    'admin' => 'admin',
    'super admin' => 'superadmin',
    'reservations staff' => 'staff',
    'treasurer' => 'treasurer',
    'cbd staff' => 'cbd',
    default => 'citizen',
})

@section('page-title', 'All Notifications')
@section('page-subtitle', 'View your notification history')

@section('page-content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">All Notifications</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $notifications->total() }} total notifications</p>
        </div>
        <a href="{{ url()->previous() }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-medium">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            Go Back
        </a>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        @forelse($notifications as $notification)
            <div class="px-6 py-4 border-b border-gray-100 last:border-b-0 {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }} hover:bg-gray-50 transition-colors">
                <div class="flex items-start">
                    <!-- Icon -->
                    <div class="flex-shrink-0 mr-4">
                        <div class="w-10 h-10 {{ $notification->read_at ? 'bg-gray-100' : 'bg-blue-100' }} rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="{{ $notification->read_at ? 'text-gray-500' : 'text-blue-600' }}">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M12 16v-4"/>
                                <path d="M12 8h.01"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <p class="text-sm {{ $notification->read_at ? 'text-gray-700' : 'font-semibold text-gray-900' }}">
                            {{ $notification->message }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">{{ $notification->time_ago }}</p>
                    </div>

                    <!-- Unread indicator -->
                    @if(!$notification->read_at)
                        <div class="flex-shrink-0 ml-3">
                            <div class="w-2.5 h-2.5 bg-blue-600 rounded-full"></div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="px-6 py-16 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4 text-gray-300">
                    <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                    <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                </svg>
                <p class="text-gray-500 font-medium">No notifications yet</p>
                <p class="text-gray-400 text-sm mt-1">You'll see your notifications here when you receive them.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
