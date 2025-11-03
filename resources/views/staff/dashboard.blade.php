@extends('layouts.staff')

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-green-700 to-green-900 rounded-2xl p-8 text-white shadow-xl overflow-hidden relative" style="background: linear-gradient(135deg, #047857 0%, #064e3b 100%)!important;">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
                <pattern id="pattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                    <circle cx="10" cy="10" r="1" fill="currentColor"/>
                </pattern>
                <rect width="100%"" height="100%"" fill="url(#pattern)"/>
            </svg>
        </div>

        <div class="relative z-10 flex items-center justify-between">
            <div class="space-y-3">
                {{-- Welcome Message and Icon --}}
                <div class="flex items-center space-x-3">
                    <div class="w-16 h-16 bg-lgu-highlight/20 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/10">
                        {{-- Icon for Dashboard --}}
                        <svg class="w-8 h-8 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold mb-1">Welcome Back, Staff Member!</h1>
                        <p class="text-gray-200">Staff Verification Portal Dashboard</p>
                    </div>
                </div>
            </div>

            {{-- Real-time Clock and Date --}}
            <div class="text-right space-y-2 hidden sm:block">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 border border-white/20">
                    <p id="current-time-main" class="text-3xl font-bold text-lgu-highlight">00:00:00</p>
                    <p id="current-date" class="text-sm text-gray-200">Loading Date...</p>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- Verification Status Cards --}}
        <div class="md:col-span-2 space-y-6">
            {{-- Pending Verifications Card --}}
            <div class="bg-white rounded-xl shadow-lg p-6 border border-yellow-200/50 hover:shadow-xl transition duration-300">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">Pending Verifications</h2>
                    <a href="{{ route('staff.verification.index') }}" class="text-lgu-highlight hover:text-orange-600 font-medium text-sm flex items-center">
                        View Queue
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
                <div class="mt-4 flex items-end justify-between">
                    <p class="text-5xl font-extrabold text-lgu-headline">{{ $stats['pending_verifications'] ?? 0 }}</p>
                    <span class="text-sm text-yellow-600 bg-yellow-100 px-3 py-1 rounded-full font-medium">
                        <i class="fas fa-hourglass-half mr-1"></i> High Priority
                    </span>
                </div>
                <p class="mt-2 text-gray-500 text-sm">Documents awaiting your immediate review.</p>
            </div>

            {{-- Today's Completed Verifications Card --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-white rounded-xl shadow-lg p-5 border border-green-200/50 hover:shadow-xl transition duration-300">
                    <div class="flex items-center space-x-3">
                        <div class="p-3 rounded-full bg-green-100 text-green-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Completed Today</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['completed_today'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                {{-- Total Rejected/Revisions Card --}}
                <div class="bg-white rounded-xl shadow-lg p-5 border border-red-200/50 hover:shadow-xl transition duration-300">
                    <div class="flex items-center space-x-3">
                        <div class="p-3 rounded-full bg-red-100 text-red-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Revisions Sent</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['revisions_sent'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Latest Verification Activity (Placeholder for a List) --}}
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Latest Activity Log</h2>
                <ul class="divide-y divide-gray-100">
                    {{-- Loop through recent activities --}}
                    @for ($i = 0; $i < 4; $i++)
                        @php
                            $activities = [
                                ['status' => 'Approved', 'icon' => 'check', 'color' => 'green', 'doc' => 'Business Permit (BPN-0021)'],
                                ['status' => 'Sent for Revision', 'icon' => 'exclamation', 'color' => 'yellow', 'doc' => 'ID Verification (IDV-1099)'],
                                ['status' => 'Approved', 'icon' => 'check', 'color' => 'green', 'doc' => 'Facility Booking (FBR-543)'],
                                ['status' => 'Rejected', 'icon' => 'times', 'color' => 'red', 'doc' => 'Tax Clearance (TCR-777)'],
                            ];
                            $activity = $activities[$i % count($activities)];
                        @endphp
                        <li class="py-3 flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 rounded-full bg-{{ $activity['color'] }}-500"></div>
                                <p class="text-gray-700 text-sm font-medium">{{ $activity['status'] }} - <span class="text-gray-500 font-normal">{{ $activity['doc'] }}</span></p>
                            </div>
                            <span class="text-xs text-gray-400">5 min ago</span>
                        </li>
                    @endfor
                </ul>
                <div class="mt-4 text-center">
                    <a href="{{ route('staff.stats') }}" class="text-lgu-paragraph hover:text-lgu-headline text-sm font-medium">View Full Verification History</a>
                </div>
            </div>
        </div>

        {{-- Quick Links Sidebar --}}
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-gray-800">Quick Actions</h3>

            {{-- Quick Link: View My Stats --}}
            <a href="{{ route('staff.stats') }}" class="flex items-center p-3 bg-white border border-gray-200 rounded-lg shadow-sm hover:border-lgu-highlight hover:shadow-md transition-all w-full text-left">
                <svg class="w-8 h-8 text-lgu-headline mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-6v6m-4-2v4m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-900">My Statistics</p>
                    <p class="text-sm text-gray-600">Track your performance</p>
                </div>
            </a>

            {{-- Quick Link: Verification Queue --}}
            <a href="{{ route('staff.verification.index') }}" class="flex items-center p-3 bg-white border border-gray-200 rounded-lg shadow-sm hover:border-lgu-highlight hover:shadow-md transition-all w-full text-left">
                <svg class="w-8 h-8 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3 .895 3 2s-1.343 2-3 2v2m0 0v2m0-8h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-900">Start Verifying</p>
                    <p class="text-sm text-gray-600">Proceed to the next document</p>
                </div>
            </a>

            {{-- Quick Link: Help & Support --}}
            <a href="{{ route('staff.help-support') }}" class="flex items-center p-3 bg-white border border-gray-200 rounded-lg shadow-sm hover:border-lgu-highlight hover:shadow-md transition-all w-full text-left">
                <svg class="w-8 h-8 text-lgu-button mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9.247a3.75 3.75 0 10-4.791 4.791M9.998 12.001A3.75 3.75 0 1014.79 7.21a3.75 3.75 0 10-4.791 4.791m4.791 0L17 17M8.228 17l4.791-4.791"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-900">Help & Support</p>
                    <p class="text-sm text-gray-600">Contact admin or view FAQs</p>
                </div>
            </a>

            {{-- Quick Link: Internal Guidelines --}}
            <a href="#" class="flex items-center p-3 bg-white border border-gray-200 rounded-lg shadow-sm hover:border-lgu-highlight hover:shadow-md transition-all w-full text-left">
                <svg class="w-8 h-8 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <div>
                    <p class="font-medium text-gray-900">Guidelines</p>
                    <p class="text-sm text-gray-600">Verification procedures</p>
                </div>
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to update the date and time display
        function updateDateTime() {
            const now = new Date();
            const dateElement = document.getElementById('current-date');
            const timeElement = document.getElementById('current-time-main');

            if (dateElement) {
                const dateString = now.toLocaleDateString('en-US', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                dateElement.textContent = dateString;
            }

            if (timeElement) {
                const timeString = now.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: true
                });
                timeElement.textContent = timeString;
            }
        }

        // Initial call and set interval for real-time update
        updateDateTime();
        setInterval(updateDateTime, 1000);
    });
</script>
@endpush
@endsection