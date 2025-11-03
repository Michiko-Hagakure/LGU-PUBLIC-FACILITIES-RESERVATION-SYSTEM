@extends('citizen.layouts.app-sidebar')

@section('title', 'Dashboard - LGU1 Citizen Portal')
@section('page-title', 'Dashboard')
@section('page-description', 'Welcome to your citizen portal')

@section('content')
<div class="space-y-6">
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ $user->full_name }}!</h1>
                <p class="text-gray-600 mt-1">Manage your facility reservations and profile</p>
            </div>
            <div class="flex items-center space-x-4">
                @if($user->is_verified)
                    <div class="flex items-center text-green-600">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span class="text-sm font-medium">Active Account</span>
                    </div>
                @else
                    <div class="flex items-center text-yellow-600">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span class="text-sm font-medium">Verification Pending</span>
                    </div>
                    {{-- Trigger alert for unverified user only if they are not yet verified --}}
                    @push('scripts')
                        <script>
                            document.addEventListener('DOMContentLoaded', showVerificationAlert);
                        </script>
                    @endpush
                @endif
            </div>
        </div>
    </div>


    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <i class="fas fa-building text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h2 class="text-lg font-semibold text-gray-900">{{ number_format($availableFacilitiesCount) }}</h2>
                    <p class="text-sm text-gray-500">Available Facilities</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h2 class="text-lg font-semibold text-gray-900">{{ number_format($approvedReservationsCount) }}</h2>
                    <p class="text-sm text-gray-500">Approved Reservations</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100">
                    <i class="fas fa-money-bill-wave text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h2 class="text-lg font-semibold text-gray-900">{{ number_format($pendingPaymentSlipsCount) }}</h2>
                    <p class="text-sm text-gray-500">Pending Payment Slips</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Latest Payment Slips</h2>
        
        @if($paymentSlips->count() > 0)
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
                                Total Fee
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Due Date
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
                        @foreach($paymentSlips as $slip)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ $slip->booking_id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $slip->booking->facility->name ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-semibold">
                                    ₱{{ number_format($slip->total_fee, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="{{ \Carbon\Carbon::parse($slip->due_date)->isPast() && $slip->status !== 'paid' ? 'text-red-600 font-medium' : 'text-gray-900' }}">
                                        {{ \Carbon\Carbon::parse($slip->due_date)->format('M d, Y') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusClass = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'paid' => 'bg-green-100 text-green-800',
                                            'expired' => 'bg-red-100 text-red-800',
                                        ][$slip->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                        {{ ucfirst($slip->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('citizen.payment-slips.show', $slip->id) }}" class="text-blue-600 hover:text-blue-900">View Slip</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4 text-center">
                <a href="{{ route('citizen.reservation-history') }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">View all reservations &rarr;</a>
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <div class="mb-3">
                    <i class="fas fa-money-check-alt text-gray-400 text-2xl"></i>
                </div>
                <p class="text-gray-500">No payment slips yet</p>
                <p class="text-sm text-gray-400 mt-1">Payment slips will appear here once your reservations are approved</p>
            </div>
        @endif
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-bullhorn text-blue-400"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">System Announcements</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Welcome to the new LGU1 Citizen Portal!</li>
                        <li>All reservations require advance booking and approval.</li>
                        <li>For urgent requests, please contact our office directly.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function showVerificationAlert() {
    Swal.fire({
        icon: 'warning',
        title: 'Account Verification Required',
        text: 'Your account is still pending verification. Please wait for staff approval before making reservations.',
        confirmButtonText: 'OK',
        confirmButtonColor: '#3B82F6'
    });
}
</script>
@endpush
@endsection