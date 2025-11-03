@extends('citizen.layouts.app-sidebar')

@section('title', 'Payment Slip Details')

@section('content')
<div class="mb-6 no-print">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between">
        <div class="mb-4 sm:mb-0">
            <a href="{{ route('citizen.payment-slips.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-2 transition duration-150 ease-in-out">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Payment Slips
            </a>
            <h1 class="text-3xl font-extrabold text-gray-900">Payment Slip #{{ $paymentSlip->slip_number }}</h1>
            <div class="flex items-center mt-3 space-x-4">
                @if($paymentSlip->status === 'paid')
                    <span class="inline-flex items-center px-4 py-1.5 text-sm font-bold rounded-full bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1.5"></i> Paid
                    </span>
                @elseif($paymentSlip->status === 'expired')
                    <span class="inline-flex items-center px-4 py-1.5 text-sm font-bold rounded-full bg-red-100 text-red-800">
                        <i class="fas fa-times-circle mr-1.5"></i> Expired
                    </span>
                @else
                    <span class="inline-flex items-center px-4 py-1.5 text-sm font-bold rounded-full bg-yellow-100 text-yellow-800">
                        <i class="fas fa-clock mr-1.5"></i> Awaiting Payment
                    </span>
                @endif
            </div>
        </div>
        <div class="flex flex-wrap space-x-3">
            <a href="{{ route('citizen.payment-slips.download', $paymentSlip->id) }}"
               class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 mb-2 sm:mb-0">
                <i class="fas fa-download mr-2"></i>
                Download PDF
            </a>
            <button onclick="window.print()"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 mb-2 sm:mb-0">
                <i class="fas fa-print mr-2"></i>
                Print
            </button>
            <button onclick="saveAsImage(event)"
                    class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                <i class="fas fa-camera mr-2"></i>
                Save as Image
            </button>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 printable">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white shadow-xl rounded-xl p-6 transition duration-300 hover:shadow-2xl">
            <h3 class="text-xl font-bold text-gray-900 mb-5 pb-2 border-b border-gray-100 flex items-center">
                <i class="fas fa-receipt text-blue-500 mr-3"></i>
                Payment Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-500 uppercase">Payment Slip Number</label>
                    <p class="mt-1 text-base text-gray-900 font-mono break-all">{{ $paymentSlip->slip_number }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-500 uppercase">Amount Due</label>
                    <p class="mt-1 text-2xl font-extrabold text-green-600">₱{{ number_format($paymentSlip->amount, 2) }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-500 uppercase">Generated Date</label>
                    <p class="mt-1 text-base text-gray-900">{{ $paymentSlip->created_at->format('F j, Y g:i A') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-500 uppercase">Due Date</label>
                    <p class="mt-1 text-base text-gray-900">{{ $paymentSlip->due_date->format('F j, Y') }}</p>
                    @if($paymentSlip->status === 'unpaid')
                        @if($paymentSlip->days_until_due > 0)
                            <p class="text-sm mt-1 text-yellow-600 font-medium"><i class="far fa-hourglass-half mr-1"></i> {{ $paymentSlip->days_until_due }} days remaining</p>
                        @else
                            <p class="text-sm mt-1 text-red-600 font-medium"><i class="fas fa-exclamation-triangle mr-1"></i> Overdue</p>
                        @endif
                    @endif
                </div>
                @if($paymentSlip->paid_at)
                    <div>
                        <label class="block text-sm font-semibold text-gray-500 uppercase">Paid Date</label>
                        <p class="mt-1 text-base text-gray-900">{{ $paymentSlip->paid_at->format('F j, Y g:i A') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-500 uppercase">Payment Method</label>
                        <p class="mt-1 text-base text-gray-900">{{ ucfirst($paymentSlip->payment_method) }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="bg-white shadow-xl rounded-xl p-6 transition duration-300 hover:shadow-2xl">
            <h3 class="text-xl font-bold text-gray-900 mb-5 pb-2 border-b border-gray-100 flex items-center">
                <i class="fas fa-calendar-check text-blue-500 mr-3"></i>
                Reservation Details
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-500 uppercase">Event Name</label>
                    <p class="mt-1 text-base text-gray-900">{{ $paymentSlip->booking->event_name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-500 uppercase">Facility</label>
                    <p class="mt-1 text-base text-gray-900">{{ $paymentSlip->booking->facility->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-500 uppercase">Event Date</label>
                    <p class="mt-1 text-base text-gray-900">{{ $paymentSlip->booking->event_date->format('F j, Y') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-500 uppercase">Time</label>
                    <p class="mt-1 text-base text-gray-900">{{ $paymentSlip->booking->start_time }} - {{ $paymentSlip->booking->end_time }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-500 uppercase">Expected Attendees</label>
                    <p class="mt-1 text-base text-gray-900">{{ $paymentSlip->booking->expected_attendees }} people</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-500 uppercase">Reservation Status</label>
                    <span class="inline-flex mt-1 px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800">
                        Approved
                    </span>
                </div>
            </div>
            @if($paymentSlip->booking->event_description)
                <div class="mt-6 pt-4 border-t border-gray-100">
                    <label class="block text-sm font-semibold text-gray-500 uppercase">Event Description</label>
                    <p class="mt-1 text-base text-gray-900">{{ $paymentSlip->booking->event_description }}</p>
                </div>
            @endif
        </div>

        @if($paymentSlip->cashier_notes)
            <div class="bg-blue-50 border border-blue-300 rounded-xl p-6">
                <h3 class="text-xl font-bold text-blue-900 mb-3 flex items-center">
                    <i class="fas fa-sticky-note text-blue-600 mr-3"></i>
                    Cashier Notes
                </h3>
                <p class="text-base text-blue-800">{{ $paymentSlip->cashier_notes }}</p>
            </div>
        @endif
    </div>

    <div class="space-y-6 lg:col-span-1">
        <div class="bg-white shadow-xl rounded-xl p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-5 pb-2 border-b border-gray-100">Current Status</h3>
            <div class="space-y-4">
                @if($paymentSlip->status === 'paid')
                    <div class="flex items-start p-3 bg-green-50 rounded-lg text-green-700">
                        <i class="fas fa-check-circle text-2xl mr-3 mt-0.5"></i>
                        <div>
                            <p class="font-bold">Payment Complete</p>
                            <p class="text-sm text-gray-600">Paid on {{ $paymentSlip->paid_at->format('F j, Y') }}</p>
                        </div>
                    </div>
                @elseif($paymentSlip->status === 'expired')
                    <div class="flex items-start p-3 bg-red-50 rounded-lg text-red-700">
                        <i class="fas fa-times-circle text-2xl mr-3 mt-0.5"></i>
                        <div>
                            <p class="font-bold">Payment Expired</p>
                            <p class="text-sm text-gray-600">Due date was {{ $paymentSlip->due_date->format('F j, Y') }}</p>
                        </div>
                    </div>
                @else
                    <div class="flex items-start p-3 bg-yellow-50 rounded-lg text-yellow-700">
                        <i class="fas fa-clock text-2xl mr-3 mt-0.5"></i>
                        <div>
                            <p class="font-bold">Payment Pending</p>
                            @if($paymentSlip->days_until_due > 0)
                                <p class="text-sm text-gray-600">{{ $paymentSlip->days_until_due }} days until due</p>
                            @else
                                <p class="text-sm text-red-600 font-semibold">Overdue - please pay immediately</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @if($paymentSlip->status === 'unpaid')
            <div class="bg-blue-50 border-2 border-blue-400 rounded-xl p-6">
                <h3 class="text-xl font-bold text-blue-900 mb-5 flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-3"></i>
                    How to Pay
                </h3>
                <div class="space-y-4 text-base text-blue-800">
                    <div class="flex items-start">
                        <span class="flex-shrink-0 w-7 h-7 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5">1</span>
                        <p>Download and print this payment slip.</p>
                    </div>
                    <div class="flex items-start">
                        <span class="flex-shrink-0 w-7 h-7 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5">2</span>
                        <p>Visit the **LGU1 Cashier's Office**.</p>
                    </div>
                    <div class="flex items-start">
                        <span class="flex-shrink-0 w-7 h-7 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5">3</span>
                        <p>Present payment slip and a valid ID.</p>
                    </div>
                    <div class="flex items-start">
                        <span class="flex-shrink-0 w-7 h-7 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold mr-3 mt-0.5">4</span>
                        <p>Pay **₱{{ number_format($paymentSlip->amount, 2) }}** in cash or check.</p>
                    </div>
                </div>

                <div class="mt-6 p-4 bg-blue-100 border border-blue-200 rounded-lg">
                    <p class="text-sm font-bold text-blue-900">Office Hours:</p>
                    <p class="text-sm text-blue-800">Monday - Friday: 8:00 AM - 5:00 PM</p>
                </div>
            </div>
        @endif

        <div class="bg-white shadow-xl rounded-xl p-6 no-print">
            <h3 class="text-xl font-bold text-gray-900 mb-5 pb-2 border-b border-gray-100">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('citizen.payment-slips.download', $paymentSlip->id) }}"
                   class="w-full inline-flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition duration-150 ease-in-out">
                    <i class="fas fa-download mr-2"></i>
                    Download PDF
                </a>
                <button onclick="window.print()"
                        class="w-full inline-flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition duration-150 ease-in-out">
                    <i class="fas fa-print mr-2"></i>
                    Print This Page
                </button>
                <button onclick="saveAsImage(event)"
                        class="w-full inline-flex items-center justify-center px-4 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-semibold transition duration-150 ease-in-out">
                    <i class="fas fa-camera mr-2"></i>
                    Save as Image
                </button>
                <a href="{{ route('citizen.reservation.history') }}"
                   class="w-full inline-flex items-center justify-center px-4 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 font-semibold transition duration-150 ease-in-out">
                    <i class="fas fa-history mr-2"></i>
                    View All Reservations
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
function saveAsImage(event) {
    // Show loading
    const button = event.currentTarget; // Gamitin ang currentTarget para makuha ang button na nag-trigger
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Capturing...';
    button.disabled = true;

    // Use the element with the 'printable' class for capture
    // I-capture lang ang main content, hindi kasama ang header/actions
    const paymentSlipElement = document.querySelector('.printable'); // Gumamit ng mas specific na class

    const options = {
        backgroundColor: '#ffffff',
        scale: 2, // Higher resolution for better quality
        useCORS: true,
        allowTaint: false,
        // Set a fixed width for consistent image size, height can be auto
        width: paymentSlipElement.offsetWidth,
        windowWidth: paymentSlipElement.scrollWidth,
        windowHeight: paymentSlipElement.scrollHeight,
        scrollX: -window.scrollX,
        scrollY: -window.scrollY
    };

    html2canvas(paymentSlipElement, options).then(function(canvas) {
        // Create download link
        const link = document.createElement('a');
        // Linisin ang slip number para sa filename
        const safeSlipNumber = '{{ $paymentSlip->slip_number }}'.replace(/[^a-z0-9]/gi, '_');
        link.download = `payment-slip-${safeSlipNumber}.png`;
        link.href = canvas.toDataURL('image/png');

        // Trigger download
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        // Reset button
        button.innerHTML = originalText;
        button.disabled = false;

        // Show success message
        alert('Payment slip saved as image successfully!');
    }).catch(function(error) {
        console.error('Error saving image:', error);
        // Reset button
        button.innerHTML = originalText;
        button.disabled = false;
        alert('Sorry, there was an error saving the image. Please try printing instead. Error: ' + error.message);
    });
}

// Make the payment slip look good for screenshots and printing
document.addEventListener('DOMContentLoaded', function() {
    // Add specific styles for print and image capture
    const style = document.createElement('style');
    style.textContent = `
        /* Styles for better print/image capture */
        @media screen {
            .printable {
                /* Add a subtle container style for image capture */
                /* box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); */
            }
            .no-print {
                /* Exclude elements from print */
            }
        }

        /* Styles for printing */
        @media print {
            .no-print {
                display: none !important;
            }
            /* Hide everything by default, then show printable content */
            body * {
                visibility: hidden;
            }
            .printable, .printable * {
                visibility: visible !important;
            }
            .printable {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0;
            }
            /* Ensure text colors are dark for print contrast */
            .text-gray-900, .text-gray-700 {
                color: #000 !important;
            }
            .text-green-600 {
                color: #059669 !important; /* Tailwind green-600 */
            }
        }
    `;
    document.head.appendChild(style);

    // Add 'printable' class to the main content div.
    // NOTE: This class was already present in the original HTML but using the escaped class,
    // I am updating it to use a direct, more readable class 'printable'.
    // The query selector is updated to reflect the new class name usage in the HTML above.
    // const mainContentGrid = document.querySelector('.grid.grid-cols-1.lg\\:grid-cols-3.gap-6');
    // if (mainContentGrid) {
    //     mainContentGrid.classList.add('printable');
    // }
});
</script>

@endsection