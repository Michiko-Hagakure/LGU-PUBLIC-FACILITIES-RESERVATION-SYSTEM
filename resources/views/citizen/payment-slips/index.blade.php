@extends('citizen.layouts.app-sidebar')

@section('title', 'Payment Slips')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Payment Slips</h1>
    <p class="text-gray-600 mt-1">View and download your payment slips for approved reservations</p>
</div>

@if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
        <span class="block sm:inline font-medium"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</span>
    </div>
@endif

@if($paymentSlips->count() > 0)
    <div class="bg-white shadow-xl rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Payment Slip
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Event Details
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Amount
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Due Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($paymentSlips as $slip)
                        <tr class="hover:bg-blue-50 transition duration-150 ease-in-out">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-bold text-gray-900">{{ $slip->slip_number }}</div>
                                    <div class="text-xs text-gray-500 mt-1">Generated {{ $slip->created_at->format('M j, Y') }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $slip->booking->event_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $slip->booking->facility->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">{{ $slip->booking->event_date->format('M j, Y') }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-green-600">₱{{ number_format($slip->amount, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($slip->status === 'paid')
                                    <span class="inline-flex px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> Paid
                                    </span>
                                @elseif($slip->status === 'expired')
                                    <span class="inline-flex px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i> Expired
                                    </span>
                                @else
                                    <span class="inline-flex px-3 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i> Unpaid
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $slip->due_date->format('M j, Y') }}</div>
                                @if($slip->status === 'unpaid')
                                    @if($slip->days_until_due > 0)
                                        <div class="text-xs text-yellow-600 mt-1">{{ $slip->days_until_due }} days left</div>
                                    @else
                                        <div class="text-xs text-red-600 mt-1 font-semibold">Overdue</div>
                                    @endif
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-3 items-center">
                                    <a href="{{ route('citizen.payment-slips.show', $slip->id) }}"
                                       class="text-blue-600 hover:text-blue-800 transition duration-150 ease-in-out">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </a>
                                    <a href="{{ route('citizen.payment-slips.download', $slip->id) }}"
                                       class="text-green-600 hover:text-green-800 transition duration-150 ease-in-out">
                                        <i class="fas fa-download mr-1"></i> PDF
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($paymentSlips->where('status', 'unpaid')->count() > 0)
        <div class="mt-8 bg-blue-50 border-2 border-blue-300 rounded-xl p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-500 text-2xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-xl font-bold text-blue-900 mb-2">Payment Instructions</h3>
                    <div class="mt-2 text-sm text-blue-800">
                        <ul class="list-disc list-inside space-y-2 pl-4">
                            <li>**Print** your payment slip or save it to your mobile device.</li>
                            <li>Visit the **LGU1 Cashier's Office** during business hours.</li>
                            <li>Present your payment slip and **valid ID**.</li>
                            <li>Pay the exact amount in cash or check.</li>
                            <li>Keep your official receipt for your records.</li>
                        </ul>
                    </div>
                    <div class="mt-6 p-4 bg-blue-100 rounded-lg border border-blue-200">
                        <p class="text-sm font-bold text-blue-900">⚠️ Important:</p>
                        <p class="text-sm text-blue-800 mt-1">Payment must be made before the due date. **Expired payment slips cannot be processed** and may require reapplication.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

@else
    <div class="bg-white shadow-xl rounded-xl p-12 text-center border-2 border-gray-100">
        <div class="max-w-md mx-auto">
            <i class="fas fa-receipt text-gray-300 text-7xl mb-6"></i>
            <h3 class="text-xl font-bold text-gray-900 mb-3">No Payment Slips Yet</h3>
            <p class="text-gray-500 mb-8">
                You'll see payment slips here once your reservation requests are **approved** by the admin.
            </p>
            <a href="{{ route('citizen.reservations') }}"
               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <i class="fas fa-plus mr-2"></i>
                Make a Reservation
            </a>
        </div>
    </div>
@endif
@endsection