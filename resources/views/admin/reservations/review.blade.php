@extends('layouts.app')

@section('title', 'Review Reservation #{{ $reservation->id }}')

@section('content')
<div class="space-y-6">
    <div class="bg-lgu-headline rounded-2xl p-6 text-white shadow-lgu-lg overflow-hidden relative">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
                <pattern id="pattern-reservations-review" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                    <circle cx="10" cy="10" r="1" fill="currentColor"/>
                </pattern>
                <rect width="100%" height="100%" fill="url(#pattern-reservations-review)"/>
            </svg>
        </div>
        
        <div class="relative z-10 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-lgu-highlight/20 rounded-xl flex items-center justify-center backdrop-blur-sm border border-white/10">
                    <svg class="w-6 h-6 text-lgu-highlight" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <a href="{{ route('admin.reservations.index') }}" class="text-sm text-gray-300 hover:text-white transition-colors">&leftarrow; Back to List</a>
                    <h1 class="text-2xl font-bold mb-1 text-white">Review Reservation #{{ $reservation->id }}</h1>
                </div>
            </div>
            
            <div class="text-right">
                <p class="text-sm font-medium text-gray-200">Current Status</p>
                @php
                    $statusClass = [
                        'pending' => 'bg-yellow-400 text-yellow-900',
                        'approved' => 'bg-green-500 text-white',
                        'rejected' => 'bg-red-500 text-white',
                        'cancelled' => 'bg-gray-400 text-gray-800',
                    ][$reservation->status] ?? 'bg-gray-400 text-white';
                @endphp
                <span class="text-xl font-bold px-3 py-1 rounded-full {{ $statusClass }}">
                    {{ ucfirst($reservation->status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 mb-4 border-b pb-2">Event & Facility Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Facility Reserved</p>
                        <p class="text-lg font-semibold text-lgu-highlight">{{ $reservation->facility->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Event Title/Purpose</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $reservation->event_title }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-600">Reservation Date & Time</p>
                        <p class="text-lg font-semibold text-gray-900">
                            {{ \Carbon\Carbon::parse($reservation->start_time)->format('M d, Y') }} 
                            <span class="text-lgu-headline font-bold ml-2">
                                {{ \Carbon\Carbon::parse($reservation->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('g:i A') }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Expected Attendees</p>
                        <p class="text-lg font-semibold text-gray-900">{{ number_format($reservation->expected_attendees) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Event Type</p>
                        <p class="text-lg font-semibold text-gray-900">{{ ucfirst($reservation->event_type) }}</p>
                    </div>
                </div>

                @if($reservation->description)
                <div class="mt-4 border-t pt-4">
                    <p class="text-sm text-gray-600 mb-1">Detailed Event Description</p>
                    <p class="text-gray-800 whitespace-pre-wrap">{{ $reservation->description }}</p>
                </div>
                @endif
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 mb-4 border-b pb-2">Applicant Details</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Full Name</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $reservation->user_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Contact / Email</p>
                        <p class="text-lg font-semibold text-lgu-highlight">{{ $reservation->email }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-600">Address</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $reservation->address }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 mb-4 border-b pb-2">Uploaded Documents & Signature</h2>
                <div class="space-y-4">
                    
                    @foreach(['valid_id', 'id_selfie', 'authorization_letter', 'event_proposal'] as $doc)
                        @if($reservation->{$doc})
                        <div class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="flex items-center space-x-3">
                                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M7 3a1 1 0 00-1 1v10a1 1 0 001 1h6a1 1 0 001-1V4a1 1 0 00-1-1H7zM5 4a3 3 0 013-3h4a3 3 0 013 3v12a3 3 0 01-3 3H8a3 3 0 01-3-3V4zM8 8h4v2H8V8z"/></svg>
                                <div>
                                    <p class="font-medium text-gray-800">{{ ucwords(str_replace('_', ' ', $doc)) }}</p>
                                    <p class="text-xs text-gray-500">{{ formatFileSize(Storage::disk('public')->size($reservation->{$doc})) }}</p>
                                </div>
                            </div>
                            <button onclick="openImageModal('{{ Storage::url($reservation->{$doc}) }}')"
                                    class="text-blue-600 hover:text-blue-800 font-medium flex items-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span>View</span>
                            </button>
                        </div>
                        @else
                        @endif
                    @endforeach

                    @if($reservation->signature_data)
                    <div class="flex items-center justify-between p-3 border border-green-200 rounded-lg bg-green-50">
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <div>
                                <p class="font-medium text-gray-800">Applicant Signature</p>
                                <p class="text-xs text-green-600">Provided digitally or via upload</p>
                            </div>
                        </div>
                        <button onclick="openImageModal('{{ Storage::url($reservation->signature_data) }}')"
                                class="text-blue-600 hover:text-blue-800 font-medium flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <span>View</span>
                        </button>
                    </div>
                    @else
                    <div class="p-3 border border-red-200 rounded-lg bg-red-50 text-red-800 text-sm">
                        ⚠️ **Warning:** Applicant Signature is **Missing**.
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                <h2 class="text-xl font-bold text-gray-900 mb-4 border-b pb-2">Fee & Payment Summary</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Reservation Fee</span>
                        <span class="font-medium text-gray-800">₱{{ number_format($reservation->reservation_fee, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Equipment Rental</span>
                        <span class="font-medium text-gray-800">₱{{ number_format($reservation->equipment_fee, 2) }}</span>
                    </div>
                    <div class="flex justify-between border-t pt-3">
                        <span class="text-lg font-bold text-lgu-headline">TOTAL FEE</span>
                        <span class="text-xl font-bold text-green-600">₱{{ number_format($reservation->total_fee, 2) }}</span>
                    </div>
                    
                    @if($reservation->payment_status === 'pending')
                    <div class="p-3 bg-yellow-100 text-yellow-800 rounded-lg text-sm font-medium mt-4">
                        <span class="font-bold">PAYMENT PENDING:</span> The payment slip has been issued but not yet paid/verified.
                    </div>
                    @elseif($reservation->payment_status === 'paid')
                    <div class="p-3 bg-green-100 text-green-800 rounded-lg text-sm font-medium mt-4">
                        <span class="font-bold">PAYMENT VERIFIED:</span> Full payment has been received.
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-1 space-y-6">
            
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200 sticky top-4">
                <h2 class="text-xl font-bold text-gray-900 mb-4 border-b pb-2">Review Actions</h2>

                @if($reservation->status === 'pending')
                    
                    <form action="{{ route('admin.reservations.approve', $reservation->id) }}" method="POST" class="mb-4">
                        @csrf
                        <button type="submit" 
                                class="w-full py-3 bg-green-600 text-white text-lg font-semibold rounded-lg hover:bg-green-700 transition-colors shadow-lg">
                            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Approve Reservation
                        </button>
                    </form>

                    <button onclick="openRejectionModal()"
                            class="w-full py-3 bg-red-600 text-white text-lg font-semibold rounded-lg hover:bg-red-700 transition-colors shadow-lg">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Reject Reservation
                    </button>

                @elseif($reservation->status === 'approved')
                    <div class="p-4 bg-green-50 border border-green-300 rounded-lg text-center">
                        <p class="text-green-800 font-semibold">This reservation has already been **Approved**.</p>
                    </div>
                    @if($reservation->approval_notes)
                    <div class="mt-4 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                        <p class="text-sm text-gray-700">**Approval Notes:** {{ $reservation->approval_notes }}</p>
                    </div>
                    @endif
                @elseif($reservation->status === 'rejected')
                    <div class="p-4 bg-red-50 border border-red-300 rounded-lg text-center">
                        <p class="text-red-800 font-semibold">This reservation has been **Rejected**.</p>
                    </div>
                    @if($reservation->rejection_reason)
                    <div class="mt-4 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                        <p class="text-sm text-gray-700">**Reason:** {{ $reservation->rejection_reason }}</p>
                    </div>
                    @endif
                @endif
                
                <div class="mt-6 border-t pt-4">
                    <h3 class="text-md font-semibold text-gray-700 mb-2">Internal Review Notes</h3>
                    <textarea id="admin_notes" name="admin_notes" rows="4" 
                              class="w-full border-gray-300 rounded-lg focus:ring-lgu-highlight focus:border-lgu-highlight" 
                              placeholder="Add internal notes about the review process...">{{ $reservation->admin_notes }}</textarea>
                    <button onclick="saveAdminNotes({{ $reservation->id }})" 
                            class="mt-2 w-full py-2 bg-lgu-highlight text-white text-sm font-medium rounded-lg hover:bg-lgu-button transition-colors">
                        Save Notes
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<div id="rejectionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-xl bg-white">
        <div class="mt-3">
            <h3 class="text-xl font-bold text-red-600 mb-4 border-b pb-2">Reject Reservation #{{ $reservation->id }}</h3>
            <form id="rejectionForm" action="{{ route('admin.reservations.reject', $reservation->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Reason for Rejection <span class="text-red-500">*</span>
                    </label>
                    <textarea id="rejection_reason" name="rejection_reason" rows="4" required
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring focus:ring-red-500/50" 
                        placeholder="Please provide a clear and concise reason for rejecting this reservation."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3 border-t pt-4">
                    <button type="button" onclick="closeRejectionModal()"
                            class="px-5 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-5 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors">
                        Confirm Rejection
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-1/2 -translate-y-1/2 mx-auto max-w-4xl p-4">
        <button onclick="closeImageModal()" class="absolute -top-10 right-0 text-white text-3xl font-bold">&times;</button>
        <img id="modalImage" src="" alt="Document View" class="max-w-full max-h-screen mx-auto rounded-lg shadow-2xl">
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ------------------- Modals -------------------
    function openRejectionModal() {
        document.getElementById('rejectionModal').classList.remove('hidden');
    }

    function closeRejectionModal() {
        document.getElementById('rejectionModal').classList.add('hidden');
        document.getElementById('rejectionForm').reset();
    }

    function openImageModal(imageUrl) {
        document.getElementById('modalImage').src = imageUrl;
        document.getElementById('imageModal').classList.remove('hidden');
    }

    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.getElementById('modalImage').src = '';
    }

    // Attach to window so they are globally callable
    window.openRejectionModal = openRejectionModal;
    window.closeRejectionModal = closeRejectionModal;
    window.openImageModal = openImageModal;
    window.closeImageModal = closeImageModal;
    
    // Close modals on outside click
    document.getElementById('imageModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeImageModal();
        }
    });

    document.getElementById('rejectionModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeRejectionModal();
        }
    });

    // ------------------- Form Submissions -------------------
    // Handle Approval Form Submission (using the default form action)
    document.querySelectorAll('form[action$="/approve"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Confirm Approval',
                text: 'Are you sure you want to approve this reservation?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10B981',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, Approve it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Approving reservation, please wait.',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading() }
                    });
                    this.submit(); // Submit the form after confirmation
                }
            });
        });
    });

    // Handle Rejection Form Submission (using the rejection modal form action)
    document.getElementById('rejectionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const reason = document.getElementById('rejection_reason').value;
        if (!reason.trim()) {
            Swal.fire({
                icon: 'warning',
                title: 'Reason Required',
                text: 'Please provide a reason for rejection.',
                confirmButtonColor: '#F59E0B'
            });
            return;
        }

        Swal.fire({
            title: 'Processing Rejection...',
            text: 'Rejecting reservation and notifying the applicant.',
            allowOutsideClick: false,
            didOpen: () => { Swal.showLoading() }
        });
        
        // Use Fetch for a cleaner update without page reload issues from SweetAlert
        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ rejection_reason: reason })
        })
        .then(response => response.json())
        .then(data => {
            closeRejectionModal();
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Rejected!',
                    text: data.message,
                    confirmButtonColor: '#10B981'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message || 'An error occurred during rejection.',
                    confirmButtonColor: '#EF4444'
                });
            }
        })
        .catch(error => {
            closeRejectionModal();
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Network error or server failed to respond.',
                confirmButtonColor: '#EF4444'
            });
        });
    });
});

function saveAdminNotes(reservationId) {
    const notes = document.getElementById('admin_notes').value;
    
    Swal.fire({
        title: 'Saving Notes...',
        allowOutsideClick: false,
        didOpen: () => { Swal.showLoading() }
    });

    fetch(`/admin/reservations/${reservationId}/notes`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ admin_notes: notes })
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Notes Saved!',
                text: data.message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error Saving Notes!',
                text: data.message || 'An error occurred.',
                confirmButtonColor: '#EF4444'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Could not save notes due to a network error.',
            confirmButtonColor: '#EF4444'
        });
    });
}
</script>
@endpush

@php
    // Helper function (as seen in the original snippet, kept for functionality)
    function formatFileSize($bytes) {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
@endphp