@extends('citizen.layouts.app-sidebar')

@section('title', 'My Reservations - LGU1 Citizen Portal')
@section('page-title', 'Reservation History')
@section('page-description', 'View your past and current reservations')

@section('content')
<div class="space-y-6">
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="flex justify-end">
        <a href="{{ route('citizen.reservations.create') }}" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-md">
            <i class="fas fa-plus mr-2"></i> New Reservation
        </a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        @if($bookings->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Booking ID
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Facility
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Event Date
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Time Slot
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($bookings as $booking)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ $booking->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $booking->facility->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($booking->start_time)->format('g:i A') }} - 
                                    {{ \Carbon\Carbon::parse($booking->end_time)->format('g:i A') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClass = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'approved' => 'bg-green-100 text-green-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            'cancelled' => 'bg-gray-100 text-gray-800',
                                        ][$booking->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <a href="{{ route('citizen.reservations.show', $booking->id) }}" class="text-blue-600 hover:text-blue-900">View Details</a>
                                    
                                    @if($booking->status === 'approved' && \Carbon\Carbon::parse($booking->end_time)->isFuture())
                                        <button onclick="openExtensionModal({{ $booking->id }}, '{{ \Carbon\Carbon::parse($booking->end_time)->format('Y-m-d\TH:i') }}')" 
                                                class="text-purple-600 hover:text-purple-900 ml-2">
                                            Extend
                                        </button>
                                    @endif

                                    @if($booking->status === 'pending')
                                        <form action="{{ route('citizen.bookings.cancel', $booking->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to cancel this reservation?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 ml-2">Cancel</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4">
                {{ $bookings->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No Reservations Found</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new facility reservation.</p>
                <div class="mt-6">
                    <a href="{{ route('citizen.reservations.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700">
                        <i class="fas fa-plus mr-2"></i> New Reservation
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<div id="extensionModal" class="fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="modal-title">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-bold text-gray-900 mb-4" id="modal-title">Extend Reservation</h3>
        <p class="text-sm text-gray-600 mb-4">You are requesting to extend booking #<span id="bookingIdDisplay" class="font-semibold"></span>.</p>
        
        <div class="space-y-4">
            <div>
                <label for="new_end_time_input" class="block text-sm font-medium text-gray-700">New End Time</label>
                <input type="datetime-local" id="new_end_time_input" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <p id="timeError" class="mt-1 text-xs text-red-500 hidden">Please select a time later than the current end time.</p>
            </div>
            <div>
                <label for="extension_reason_input" class="block text-sm font-medium text-gray-700">Reason for Extension (Optional)</label>
                <textarea id="extension_reason_input" rows="3" class="mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>
        </div>

        <div class="mt-5 flex justify-end space-x-3">
            <button onclick="closeExtensionModal()" class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300">
                Cancel
            </button>
            <button onclick="submitExtension()" class="px-4 py-2 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700">
                Submit Extension
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
let currentBookingId = null;
let currentEndTime = null;

function openExtensionModal(bookingId, endTime) {
    currentBookingId = bookingId;
    currentEndTime = new Date(endTime);
    document.getElementById('bookingIdDisplay').textContent = bookingId;
    document.getElementById('new_end_time_input').value = ''; // Reset input
    document.getElementById('extension_reason_input').value = ''; // Reset reason
    document.getElementById('timeError').classList.add('hidden'); // Hide error
    document.getElementById('extensionModal').classList.remove('hidden');
}

function closeExtensionModal() {
    document.getElementById('extensionModal').classList.add('hidden');
    document.getElementById('extension_reason_input').value = '';
    document.getElementById('new_end_time_input').value = '';
    document.getElementById('timeError').classList.add('hidden');
    currentBookingId = null;
    currentEndTime = null;
}

function submitExtension() {
    const newEndTimeInput = document.getElementById('new_end_time_input');
    const extensionReasonInput = document.getElementById('extension_reason_input');
    const timeError = document.getElementById('timeError');
    
    const newEndTime = newEndTimeInput.value;
    const extensionReason = extensionReasonInput.value;
    
    if (!newEndTime) {
        timeError.textContent = 'New End Time is required.';
        timeError.classList.remove('hidden');
        return;
    }
    
    const selectedTime = new Date(newEndTime);
    
    // Check if the new time is later than the current end time
    if (selectedTime <= currentEndTime) {
        timeError.textContent = 'Please select a time later than the current end time ({{ \Carbon\Carbon::parse($booking->end_time)->format('M d, Y g:i A') }}).';
        timeError.classList.remove('hidden');
        return;
    }
    
    timeError.classList.add('hidden');

    Swal.fire({
        title: 'Confirm Extension',
        text: "Are you sure you want to request this extension?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#8B5CF6', // Purple color
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Yes, Submit Request'
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit the form
            const form = document.createElement('form');
            form.method = 'POST';
            // Use the determined route
            form.action = `/citizen/bookings/${currentBookingId}/extend`; 
            
            // Add CSRF token
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('meta[name=\"csrf-token\"]').content;
            form.appendChild(csrfInput);
            
            // Add new end time
            const endTimeInput = document.createElement('input');
            endTimeInput.type = 'hidden';
            endTimeInput.name = 'new_end_time';
            endTimeInput.value = newEndTime;
            form.appendChild(endTimeInput);
            
            // Add extension reason
            if (extensionReason) {
                const reasonInput = document.createElement('input');
                reasonInput.type = 'hidden';
                reasonInput.name = 'extension_reason';
                reasonInput.value = extensionReason;
                form.appendChild(reasonInput);
            }
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}

// Close modal when clicking outside
document.getElementById('extensionModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeExtensionModal();
    }
});
</script>
@endpush
@endsection