@extends('layouts.staff')

@section('content')
<div class="space-y-6">
    <div class="bg-lgu-headline rounded-2xl p-8 text-white shadow-lgu-lg overflow-hidden relative">
        <div class="relative z-10 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Document Verification Queue</h1>
                <p class="text-gray-200">Review and verify booking requirements</p>
            </div>
            {{-- Total Pending Count --}}
            <div class="text-right space-y-2 hidden sm:block">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl px-4 py-3 border border-white/20">
                    <p class="text-2xl font-bold text-lgu-highlight">{{ $bookings->count() }}</p>
                    <p class="text-sm text-gray-200">Total Pending</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            
            {{-- Filtering Options --}}
            <div class="flex items-center space-x-4">
                
                {{-- Filter by Status (Placeholder) --}}
                <div>
                    <label for="status-filter" class="block text-sm font-medium text-gray-700 mb-1">Filter by Status</label>
                    <select id="status-filter" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition duration-150">
                        <option>All Statuses</option>
                        <option>New</option>
                        <option>Revision Required</option>
                        <option>Escalated</option>
                    </select>
                </div>

                {{-- Filter by Type (Placeholder) --}}
                <div>
                    <label for="type-filter" class="block text-sm font-medium text-gray-700 mb-1">Filter by Type</label>
                    <select id="type-filter" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition duration-150">
                        <option>All Types</option>
                        <option>Facility Booking</option>
                        <option>Business Permit</option>
                        <option>ID Verification</option>
                    </select>
                </div>
            </div>

            {{-- Search Input --}}
            <div class="w-full md:w-80">
                <label for="search-input" class="block text-sm font-medium text-gray-700 mb-1">Search Documents</label>
                <div class="relative">
                    <input type="text" id="search-input" placeholder="Search by ID or Name..."
                           class="w-full py-2 pl-10 pr-4 border border-gray-300 rounded-lg focus:outline-none focus:border-lgu-highlight transition duration-150 ease-in-out">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Verification List --}}
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        
        @if($bookings->count() > 0)
            {{-- List/Table Header --}}
            <div class="hidden md:grid grid-cols-5 gap-4 px-6 py-4 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                <div class="col-span-1">Document ID</div>
                <div class="col-span-1">Applicant Name</div>
                <div class="col-span-1">Type</div>
                <div class="col-span-1">Submitted Date</div>
                <div class="col-span-1 text-right">Action</div>
            </div>

            {{-- Verification Items Loop --}}
            <div class="divide-y divide-gray-200">
                @foreach($bookings as $booking)
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 p-6 hover:bg-lgu-bg transition duration-150">
                        
                        {{-- Document ID (Column 1) --}}
                        <div class="col-span-1 flex flex-col justify-center">
                            <p class="text-sm font-semibold text-gray-900">{{ $booking->document_id }}</p>
                            <p class="text-xs text-gray-500 md:hidden">Applicant: {{ $booking->applicant_name }}</p>
                        </div>

                        {{-- Applicant Name (Column 2) --}}
                        <div class="col-span-1 hidden md:flex flex-col justify-center">
                            <p class="text-sm text-gray-800">{{ $booking->applicant_name }}</p>
                        </div>

                        {{-- Type and Status (Column 3) --}}
                        <div class="col-span-1 flex flex-col justify-center">
                            <p class="text-sm text-gray-800">{{ $booking->type }}</p>
                            <span class="text-xs font-medium text-yellow-600 bg-yellow-100 rounded-full px-2 py-0.5 mt-1 self-start">
                                {{ $booking->status }}
                            </span>
                        </div>

                        {{-- Submission Date (Column 4) --}}
                        <div class="col-span-1 flex flex-col justify-center">
                            <p class="text-sm text-gray-500">{{ $booking->submitted_at->format('Y-m-d H:i') }}</p>
                            <p class="text-xs text-red-500 font-medium">Overdue (simulated)</p>
                        </div>

                        {{-- Action Buttons (Column 5) --}}
                        <div class="col-span-1 flex items-center justify-start md:justify-end space-x-2">
                            <a href="{{ route('staff.verification.show', $booking->id) }}" class="inline-flex items-center px-4 py-2 bg-lgu-highlight text-lgu-button-text font-medium text-sm rounded-lg hover:bg-lgu-highlight/80 transition duration-150 shadow-md">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Review
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            
            @if($bookings->count() > 15)
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-xl">
                    <p class="text-sm text-gray-600 text-center">Showing {{ $bookings->count() }} bookings</p>
                </div>
            @endif
        @else
            {{-- Empty State --}}
            <div class="p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900">Queue Cleared!</h3>
                <p class="mt-1 text-sm text-gray-500">No pending verification documents found.</p>
                <a href="{{ route('staff.dashboard') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">
                    Go to Dashboard
                </a>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Simple client-side search/filter simulation (can be replaced by AJAX)
        const searchInput = document.getElementById('search-input');
        const statusFilter = document.getElementById('status-filter');
        const typeFilter = document.getElementById('type-filter');
        const listItems = document.querySelectorAll('.grid.grid-cols-1.md\\:grid-cols-5'); // Select all document rows

        function filterList() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            const selectedStatus = statusFilter.value;
            const selectedType = typeFilter.value;

            listItems.forEach(item => {
                const docId = item.querySelector('.font-semibold').textContent.toLowerCase();
                const docType = item.querySelector('.text-sm.text-gray-800').textContent.trim();
                const docStatusElement = item.querySelector('.text-xs.font-medium');
                const docStatus = docStatusElement ? docStatusElement.textContent.trim() : 'N/A';
                
                // Search condition: Check ID or Name (assuming applicant name is in the item)
                const isMatchSearch = docId.includes(searchTerm) || 
                                      item.textContent.toLowerCase().includes(searchTerm); 

                // Filter condition: Check Status
                const isMatchStatus = selectedStatus === 'All Statuses' || docStatus === selectedStatus;

                // Filter condition: Check Type
                const isMatchType = selectedType === 'All Types' || docType === selectedType;

                if (isMatchSearch && isMatchStatus && isMatchType) {
                    item.style.display = 'grid';
                } else {
                    item.style.display = 'none';
                }
            });
            // Note: In a real application, you'd update the pagination count here too.
        }

        searchInput.addEventListener('input', filterList);
        statusFilter.addEventListener('change', filterList);
        typeFilter.addEventListener('change', filterList);
    });
</script>
@endpush
@endsection