@extends('layouts.app')

@section('title', 'All Facility Reservations')

@section('content')
<div class="space-y-6">
    <div class="bg-lgu-headline rounded-2xl p-8 text-white shadow-lgu-lg overflow-hidden relative">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
                <pattern id="pattern-reservations-index" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                    <circle cx="10" cy="10" r="1" fill="currentColor"/>
                </pattern>
                <rect width="100%" height="100%" fill="url(#pattern-reservations-index)"/>
            </svg>
        </div>
        
        <div class="relative z-10 flex items-center justify-between">
            <div class="space-y-3">
                <div class="flex items-center space-x-3">
                    <div class="w-16 h-16 bg-lgu-highlight/20 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/10">
                        <svg class="w-8 h-8 text-lgu-highlight" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold mb-1 text-white">All Facility Reservations</h1>
                        <p class="text-gray-200">A comprehensive list of all facility and equipment bookings.</p>
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.reservations.calendar') }}" 
               class="px-5 py-2.5 bg-lgu-highlight text-white font-semibold rounded-lg hover:bg-lgu-button transition-colors shadow-lg">
                <svg class="w-5 h-5 inline-block mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                View Calendar
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-4 border border-gray-200 flex flex-col md:flex-row gap-4">
        <form method="GET" action="{{ route('admin.reservations.index') }}" class="flex-grow flex flex-col md:flex-row gap-4">
            <div class="flex-grow">
                <label for="search" class="sr-only">Search Reservations</label>
                <input type="text" name="search" id="search" placeholder="Search by name, event or ID..." 
                       value="{{ request('search') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-lgu-highlight focus:border-lgu-highlight">
            </div>
            
            <div class="w-full md:w-auto">
                <label for="status" class="sr-only">Filter by Status</label>
                <select name="status" id="status" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-lgu-highlight focus:border-lgu-highlight">
                    <option value="">All Statuses</option>
                    @foreach(['pending', 'approved', 'rejected', 'cancelled', 'completed'] as $s)
                        <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                            {{ ucfirst($s) }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <button type="submit" 
                    class="px-5 py-2.5 bg-lgu-highlight text-white font-semibold rounded-lg hover:bg-lgu-button transition-colors shadow-md w-full md:w-auto">
                Filter
            </button>
        </form>
        @if(request()->has('search') || request()->has('status'))
            <a href="{{ route('admin.reservations.index') }}" 
               class="px-5 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-100 transition-colors shadow-md w-full md:w-auto text-center">
                Clear Filters
            </a>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
        @if($reservations->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Applicant
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Facility
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Event Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Fee
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reservations as $reservation)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ $reservation->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $reservation->user_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-lgu-headline">
                                    {{ $reservation->facility->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ \Carbon\Carbon::parse($reservation->start_time)->format('M d, Y') }}
                                    <span class="block text-xs text-gray-500">{{ \Carbon\Carbon::parse($reservation->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('g:i A') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-md font-bold text-green-700">
                                    ₱{{ number_format($reservation->total_fee, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $statusClass = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'approved' => 'bg-green-100 text-green-800 font-semibold',
                                            'rejected' => 'bg-red-100 text-red-800 font-medium',
                                            'cancelled' => 'bg-gray-100 text-gray-800',
                                            'completed' => 'bg-blue-100 text-blue-800',
                                        ][$reservation->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-3 py-1 text-xs font-medium leading-5 rounded-full {{ $statusClass }}">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                                    <a href="{{ route('admin.reservations.review', $reservation->id) }}" 
                                       class="text-lgu-highlight hover:text-lgu-button font-medium">
                                        Review
                                    </a>
                                    
                                    @if($reservation->status === 'pending')
                                    <button onclick="openQuickApprovalModal({{ $reservation->id }})"
                                            class="text-green-600 hover:text-green-800 font-medium">
                                        Quick Approve
                                    </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $reservations->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5h6"></path>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No Reservations Found</h3>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or search terms.</p>
            </div>
        @endif
    </div>
</div>

<div id="quickApprovalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let currentReservationId = null;

function openQuickApprovalModal(reservationId) {
    currentReservationId = reservationId;
    document.getElementById('quickApprovalModal').classList.remove('hidden');
    // Optionally pre-fill some fields if needed
}

function closeQuickApprovalModal() {
    document.getElementById('quickApprovalModal').classList.add('hidden');
    currentReservationId = null;
    document.getElementById('quickApprovalForm').reset();
}

document.getElementById('quickApprovalForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!currentReservationId) return;
    
    const formData = new FormData(this);
    
    Swal.fire({
        title: 'Processing...',
        text: 'Approving reservation, please wait.',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading() }
    });

    // Assume the form submits to the correct route with the current ID
    fetch(`/admin/reservations/${currentReservationId}/approve`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        closeQuickApprovalModal();
        
        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                confirmButtonColor: '#10B981'
            }).then(() => {
                window.location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: data.message || 'An error occurred',
                confirmButtonColor: '#EF4444'
            });
        }
    })
    .catch(error => {
        closeQuickApprovalModal();
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'An error occurred while processing the request',
            confirmButtonColor: '#EF4444'
        });
    });
});
</script>
@endpush