@extends('layouts.app')

@section('title', 'Payment Slips Management - Admin Portal')

@section('content')
<div class="space-y-6">
    <div class="bg-lgu-headline rounded-2xl p-8 text-white shadow-lgu-lg overflow-hidden relative">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
                <pattern id="pattern-slips" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                    <circle cx="10" cy="10" r="1" fill="currentColor"/>
                </pattern>
                <rect width="100%" height="100%" fill="url(#pattern-slips)"/>
            </svg>
        </div>
        
        <div class="relative z-10 flex items-center justify-between">
            <div class="space-y-3">
                <div class="flex items-center space-x-3">
                    <div class="w-16 h-16 bg-lgu-highlight/20 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/10">
                        <svg class="w-8 h-8 text-lgu-highlight" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 002-2h-4a2 2 0 00-2 2v4a2 2 0 002 2h4a2 2 0 002-2V6a2 2 0 00-2-2H4zm12 8V8a2 2 0 00-2-2v4a2 2 0 002 2h-4a2 2 0 00-2 2h6zM4 16h12v-2H4v2z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold mb-1 text-white">Payment Slips Management</h1>
                        <p class="text-gray-200">Track and manage all issued facility reservation payment slips.</p>
                    </div>
                </div>
            </div>
            
            <button onclick="markExpiredSlips()" 
                    class="px-5 py-2.5 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors shadow-lg">
                <svg class="w-5 h-5 inline-block mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Mark Expired Slips
            </button>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
        @if($paymentSlips->count())
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ref. No.
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Reservation ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Amount
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Due Date
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
                        @foreach($paymentSlips as $slip)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-lgu-headline">
                                    {{ $slip->reference_number ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 hover:text-blue-800 transition-colors">
                                    <a href="{{ route('admin.reservations.show', $slip->booking_id) }}" class="font-medium">
                                        #{{ $slip->booking_id }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-lg font-bold text-green-700">
                                    ₱{{ number_format($slip->total_amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ \Carbon\Carbon::parse($slip->due_date)->format('M d, Y g:i A') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @php
                                        $statusClass = [
                                            'paid' => 'bg-green-100 text-green-800 font-semibold',
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'expired' => 'bg-red-100 text-red-800 font-medium',
                                            'overdue' => 'bg-red-100 text-red-800 font-medium',
                                        ][$slip->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-3 py-1 text-xs font-medium leading-5 rounded-full {{ $statusClass }}">
                                        {{ ucfirst($slip->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                                    <a href="{{ route('admin.payment-slips.show', $slip->id) }}" 
                                       class="text-lgu-highlight hover:text-lgu-button font-medium">View</a>
                                    
                                    @if($slip->status === 'pending' || $slip->status === 'overdue')
                                    <button onclick="openMarkPaidModal({{ $slip->id }}, '{{ $slip->reference_number }}')" 
                                            class="text-green-600 hover:text-green-800 font-medium">
                                        Mark Paid
                                    </button>
                                    @endif
                                    
                                    <a href="{{ route('admin.payment-slips.download', $slip->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 font-medium">Download</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($paymentSlips->lastPage() > 1)
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $paymentSlips->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.11 0 2 .9 2 2 0 1.1-.89 2-2 2s-2-.9-2-2 .89-2 2-2zM9 14v2h6v-2m-3-10a9 9 0 100 18 9 9 0 000-18z"/>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No Payment Slips Found</h3>
                <p class="mt-1 text-sm text-gray-500">All generated slips will appear here for management.</p>
            </div>
        @endif
    </div>
</div>

<div id="markPaidModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Mark Payment as Paid</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 mb-4">
                    Confirm that the payment for slip <strong id="slipRefNumber" class="text-lgu-headline"></strong> has been received and verified.
                </p>
                <form id="markPaidForm" method="POST">
                    @csrf
                    <input type="hidden" name="slip_id" id="paidSlipId">
                    <div class="mb-4">
                        <label for="date_paid" class="block text-sm font-medium text-gray-700 text-left">Date Paid</label>
                        <input type="datetime-local" id="date_paid" name="date_paid" 
                                class="mt-1 w-full border-gray-300 rounded-lg shadow-sm focus:border-lgu-highlight focus:ring focus:ring-lgu-highlight/50" 
                                required>
                    </div>
                </form>
            </div>
            <div class="items-center px-4 py-3 border-t">
                <button id="markPaidConfirmBtn" onclick="markAsPaid()"
                        class="px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Confirm Payment
                </button>
                <button onclick="closeMarkPaidModal()"
                        class="mt-3 px-4 py-2 bg-white text-gray-700 text-base font-medium rounded-md w-full shadow-sm border border-gray-300 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function openMarkPaidModal(slipId, refNumber) {
    document.getElementById('paidSlipId').value = slipId;
    document.getElementById('slipRefNumber').textContent = refNumber;
    // Set current time as default for convenience
    const now = new Date();
    const localNow = new Date(now.getTime() - (now.getTimezoneOffset() * 60000)).toISOString().slice(0, 16);
    document.getElementById('date_paid').value = localNow;
    
    document.getElementById('markPaidModal').classList.remove('hidden');
}

function closeMarkPaidModal() {
    document.getElementById('markPaidModal').classList.add('hidden');
}

function markAsPaid() {
    const slipId = document.getElementById('paidSlipId').value;
    const datePaid = document.getElementById('date_paid').value;

    if (!datePaid) {
        Swal.fire({
            icon: 'warning',
            title: 'Missing Date!',
            text: 'Please select the date and time the payment was made.',
            confirmButtonColor: '#F59E0B'
        });
        return;
    }

    closeMarkPaidModal();

    Swal.fire({
        title: 'Processing Payment...',
        text: 'Please wait, marking slip as paid.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading()
        }
    });

    fetch(`{{ url('admin/payment-slips') }}/${slipId}/mark-paid`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            date_paid: datePaid
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Payment Confirmed!',
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
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'An error occurred while marking as paid.',
            confirmButtonColor: '#EF4444'
        });
    });
}

function markExpiredSlips() {
    Swal.fire({
        title: 'Are you sure?',
        text: "This will automatically change the status of all overdue slips to 'Expired'!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Yes, Mark as Expired!'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Processing...',
                text: 'Updating expired slips, please wait.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
            });

            fetch('{{ route("admin.payment-slips.mark-expired") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Completed!',
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
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred while marking expired slips',
                    confirmButtonColor: '#EF4444'
                });
            });
        }
    });
}

// Close modals on outside click
document.getElementById('markPaidModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeMarkPaidModal();
    }
});
</script>
@endpush