@extends('layouts.staff')

@section('content')
<div class="space-y-6">
    <div class="bg-lgu-headline rounded-2xl p-8 text-white shadow-lgu-lg overflow-hidden relative">
        <div class="relative z-10 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">Review: {{ $booking->document_id }}</h1>
                <p class="text-gray-200">Review requirements and documents for approval</p>
            </div>
            
            {{-- Back Button --}}
            <div class="text-right space-y-2">
                <a href="{{ route('staff.verification.index') }}" class="inline-flex items-center px-4 py-2 bg-white/20 text-white rounded-lg hover:bg-white/30 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Queue
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Booking Information Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Booking Details</h2>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div class="space-y-1">
                        <dt class="font-medium text-gray-500">Applicant Name</dt>
                        <dd class="text-gray-900 font-semibold">{{ $booking->applicant_name }}</dd>
                    </div>
                    <div class="space-y-1">
                        <dt class="font-medium text-gray-500">Document Type</dt>
                        <dd class="text-gray-900">{{ $booking->type }}</dd>
                    </div>
                    <div class="space-y-1">
                        <dt class="font-medium text-gray-500">Date Submitted</dt>
                        <dd class="text-gray-900">{{ $booking->submitted_at->format('Y-m-d H:i') }}</dd>
                    </div>
                    <div class="space-y-1">
                        <dt class="font-medium text-gray-500">Facility / Service</dt>
                        <dd class="text-gray-900">{{ $booking->service_name ?? 'N/A' }}</dd>
                    </div>
                    {{-- Additional fields if needed --}}
                </dl>
            </div>

            {{-- Document Viewer Area (Placeholder) --}}
            <div class="bg-gray-800 rounded-xl shadow-xl overflow-hidden min-h-[400px] flex items-center justify-center">
                <p class="text-white/50 text-xl">Document Viewer Placeholder (PDF/Image)</p>
                {{-- Example: <iframe src="{{ $booking->document_url }}" class="w-full h-full"></iframe> --}}
            </div>

            {{-- Required Documents Checklist (Placeholder) --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Verification Checklist</h2>
                <ul class="space-y-2">
                    <li class="flex items-center text-sm">
                        <input type="checkbox" id="check_id" class="mr-2 text-lgu-headline rounded focus:ring-lgu-highlight" checked>
                        <label for="check_id" class="text-gray-700">Valid ID match (Photo & Name)</label>
                    </li>
                    <li class="flex items-center text-sm">
                        <input type="checkbox" id="check_address" class="mr-2 text-lgu-headline rounded focus:ring-lgu-highlight">
                        <label for="check_address" class="text-gray-700">Proof of Address (LGU residency)</label>
                    </li>
                    <li class="flex items-center text-sm">
                        <input type="checkbox" id="check_fee" class="mr-2 text-lgu-headline rounded focus:ring-lgu-highlight">
                        <label for="check_fee" class="text-gray-700">Payment Fee receipt attached</label>
                    </li>
                </ul>
            </div>

        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 sticky top-24">
                <h2 class="text-xl font-bold text-lgu-headline mb-4">Verification Action</h2>

                <form id="verification-form" method="POST" action="{{ route('staff.verification.process', $booking->id) }}" class="space-y-5">
                    @csrf
                    
                    {{-- Hidden method for form action (if needed for PUT/PATCH) --}}
                    {{-- @method('PATCH') --}}

                    {{-- Action Radio Buttons --}}
                    <div class="space-y-3">
                        <h3 class="text-sm font-medium text-gray-700">Choose Decision:</h3>
                        
                        {{-- Approve Option --}}
                        <div class="flex items-center">
                            <input type="radio" id="action-approve" name="action" value="approve" 
                                   data-route="{{ route('staff.verification.process', ['booking' => $booking->id, 'action' => 'approve']) }}"
                                   class="h-4 w-4 text-green-600 border-gray-300 focus:ring-green-500">
                            <label for="action-approve" class="ml-3 text-sm font-medium text-gray-900">
                                <span class="text-green-600 font-bold">Approve</span> Verification
                            </label>
                        </div>

                        {{-- Reject/Revision Option --}}
                        <div class="flex items-center">
                            <input type="radio" id="action-reject" name="action" value="reject"
                                   data-route="{{ route('staff.verification.process', ['booking' => $booking->id, 'action' => 'reject']) }}"
                                   class="h-4 w-4 text-red-600 border-gray-300 focus:ring-red-500">
                            <label for="action-reject" class="ml-3 text-sm font-medium text-gray-900">
                                <span class="text-red-600 font-bold">Reject / Send for Revision</span>
                            </label>
                        </div>
                    </div>

                    {{-- Staff Notes / Comments --}}
                    <div>
                        <label for="staff_notes" class="block text-sm font-medium text-gray-700 mb-1">Verification Notes (Required)</label>
                        <textarea id="staff_notes" name="staff_notes" rows="4" class="block w-full border-gray-300 rounded-md shadow-sm p-2 focus:border-lgu-highlight focus:ring-lgu-highlight" placeholder="Enter reason for rejection or approval comments..."></textarea>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-lgu-headline hover:bg-lgu-stroke transition duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-lgu-highlight">
                        Confirm Decision
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('verification-form');
    const actionRadios = form.querySelectorAll('input[name="action"]');
    
    // --- 1. Dynamic Form Action Update ---
    // Update the form's action URL based on the selected radio button
    actionRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const route = this.getAttribute('data-route');
            // Update form action (though not strictly necessary if using only one route in controller)
            form.action = route;
        });
    });
    
    // --- 2. SweetAlert Submission Confirmation ---
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form values
        const selectedRadio = document.querySelector('input[name="action"]:checked');
        const action = selectedRadio?.value;
        const notes = document.getElementById('staff_notes').value;
        
        // Validation Check
        if (!action || !notes.trim()) {
            Swal.fire({
                title: 'Missing Information',
                text: 'Please select an action (Approve/Reject) and provide detailed verification notes.',
                icon: 'warning',
                confirmButtonColor: '#faae2b'
            });
            return;
        }
        
        // Set up confirmation variables
        const isApproved = action === 'approve';
        const actionText = isApproved ? 'Approve' : 'Reject / Send for Revision';
        const actionColor = isApproved ? '#10b981' : '#ef4444'; // Green for Approve, Red for Reject
        
        // Show SweetAlert Confirmation Dialog
        Swal.fire({
            title: `Confirm Action: ${actionText}?`,
            text: `You are about to submit your decision for document ID {{ $booking->document_id }}.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: actionColor,
            cancelButtonColor: '#6b7280',
            confirmButtonText: `Yes, ${isApproved ? 'Approve' : 'Confirm Revision'}`
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form programmatically if confirmed
                form.submit();
            }
        });
    });
});
</script>
@endpush
@endsection