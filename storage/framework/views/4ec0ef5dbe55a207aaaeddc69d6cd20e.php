

<?php $__env->startSection('page-title', 'Transaction Details'); ?>
<?php $__env->startSection('page-subtitle', 'View detailed transaction information'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="pb-gr-2xl">
    <!-- Back Button -->
    <div class="mb-gr-md">
        <a href="<?php echo e(route('admin.transactions.index')); ?>" class="inline-flex items-center text-small font-medium text-gray-600 hover:text-gray-900">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-gr-xs"></i>
            Back to Transactions
        </a>
    </div>

    <!-- Page Header -->
    <div class="flex justify-between items-start mb-gr-lg">
        <div>
            <h1 class="text-h3 font-bold text-gray-900 mb-gr-2xs">Transaction Details</h1>
            <p class="text-small text-gray-600">Reference: <span class="font-mono font-semibold"><?php echo e($transaction->slip_number ?? $transaction->or_number ?? 'N/A'); ?></span></p>
        </div>
        <div class="flex items-center space-x-gr-sm">
            <span class="inline-flex items-center px-gr-sm py-gr-xs rounded-full text-small font-medium
                <?php if($transaction->status == 'paid'): ?> bg-green-100 text-green-700
                <?php elseif($transaction->status == 'pending'): ?> bg-orange-100 text-orange-700
                <?php elseif($transaction->status == 'cancelled'): ?> bg-red-100 text-red-700
                <?php else: ?> bg-gray-100 text-gray-700
                <?php endif; ?>">
                <i data-lucide="<?php echo e($transaction->status == 'paid' ? 'circle-check' : ($transaction->status == 'pending' ? 'clock' : 'x-circle')); ?>" class="w-4 h-4 mr-gr-xs"></i>
                <?php echo e(ucfirst($transaction->status)); ?>

            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-gr-lg">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-gr-lg">
            <!-- Payment Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg">
                <h3 class="text-h5 font-bold text-gray-900 mb-gr-md flex items-center">
                    <i data-lucide="credit-card" class="w-5 h-5 mr-gr-xs text-lgu-green"></i>
                    Payment Information
                </h3>

                <div class="grid grid-cols-2 gap-gr-md">
                    <div>
                        <p class="text-caption text-gray-600 mb-gr-3xs">Amount Due</p>
                        <p class="text-h4 font-bold text-gray-900">₱<?php echo e(number_format($transaction->amount_due, 2)); ?></p>
                    </div>
                    <div>
                        <p class="text-caption text-gray-600 mb-gr-3xs">Payment Method</p>
                        <p class="text-body font-semibold text-gray-900"><?php echo e(ucfirst(str_replace('_', ' ', $transaction->payment_method ?? 'N/A'))); ?></p>
                    </div>
                    <div>
                        <p class="text-caption text-gray-600 mb-gr-3xs">Transaction Date</p>
                        <p class="text-body font-medium text-gray-900"><?php echo e(\Carbon\Carbon::parse($transaction->created_at)->format('F d, Y h:i A')); ?></p>
                    </div>
                    <?php if($transaction->paid_at): ?>
                    <div>
                        <p class="text-caption text-gray-600 mb-gr-3xs">Paid At</p>
                        <p class="text-body font-medium text-gray-900"><?php echo e(\Carbon\Carbon::parse($transaction->paid_at)->format('F d, Y h:i A')); ?></p>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if(isset($transaction->payment_receipt_url) && $transaction->payment_receipt_url): ?>
                <div class="mt-gr-md pt-gr-md border-t border-gray-200">
                    <p class="text-caption text-gray-600 mb-gr-sm">Proof of Payment</p>
                    <a href="<?php echo e(asset('storage/' . $transaction->proof_of_payment)); ?>" target="_blank" class="inline-flex items-center text-lgu-green hover:text-lgu-green-dark font-medium">
                        <i data-lucide="image" class="w-4 h-4 mr-gr-xs"></i>
                        View Proof
                    </a>
                </div>
                <?php endif; ?>
            </div>

            <!-- Booking Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg">
                <h3 class="text-h5 font-bold text-gray-900 mb-gr-md flex items-center">
                    <i data-lucide="calendar" class="w-5 h-5 mr-gr-xs text-lgu-green"></i>
                    Booking Information
                </h3>

                <div class="space-y-gr-sm">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-caption text-gray-600">Facility</p>
                            <p class="text-body font-semibold text-gray-900"><?php echo e($booking->facility_name ?? 'N/A'); ?></p>
                        </div>
                        <a href="<?php echo e(route('admin.bookings.show', $transaction->booking_id)); ?>" class="text-lgu-green hover:text-lgu-green-dark font-medium text-small">
                            View Booking
                        </a>
                    </div>

                    <div class="grid grid-cols-2 gap-gr-md pt-gr-sm border-t border-gray-200">
                        <div>
                            <p class="text-caption text-gray-600 mb-gr-3xs">Event Date</p>
                            <p class="text-body font-medium text-gray-900"><?php echo e(\Carbon\Carbon::parse($booking->start_time)->format('F d, Y')); ?></p>
                        </div>
                        <div>
                            <p class="text-caption text-gray-600 mb-gr-3xs">Time</p>
                            <p class="text-body font-medium text-gray-900">
                                <?php echo e(\Carbon\Carbon::parse($booking->start_time)->format('h:i A')); ?> - 
                                <?php echo e(\Carbon\Carbon::parse($booking->end_time)->format('h:i A')); ?>

                            </p>
                        </div>
                        <div>
                            <p class="text-caption text-gray-600 mb-gr-3xs">Attendees</p>
                            <p class="text-body font-medium text-gray-900"><?php echo e(number_format($booking->attendees ?? 0)); ?></p>
                        </div>
                        <div>
                            <p class="text-caption text-gray-600 mb-gr-3xs">Booking Status</p>
                            <span class="inline-flex items-center px-gr-xs py-gr-3xs rounded-full text-caption font-medium
                                <?php if($booking->status == 'confirmed'): ?> bg-green-100 text-green-700
                                <?php elseif($booking->status == 'pending'): ?> bg-yellow-100 text-yellow-700
                                <?php elseif($booking->status == 'rejected'): ?> bg-red-100 text-red-700
                                <?php else: ?> bg-gray-100 text-gray-700
                                <?php endif; ?>">
                                <?php echo e(ucfirst($booking->status)); ?>

                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Citizen Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg">
                <h3 class="text-h5 font-bold text-gray-900 mb-gr-md flex items-center">
                    <i data-lucide="user" class="w-5 h-5 mr-gr-xs text-lgu-green"></i>
                    Citizen Information
                </h3>

                <div class="grid grid-cols-2 gap-gr-md">
                    <div>
                        <p class="text-caption text-gray-600 mb-gr-3xs">Full Name</p>
                        <p class="text-body font-semibold text-gray-900"><?php echo e($citizen->full_name ?? 'N/A'); ?></p>
                    </div>
                    <div>
                        <p class="text-caption text-gray-600 mb-gr-3xs">Email</p>
                        <p class="text-body font-medium text-gray-900"><?php echo e($citizen->email ?? 'N/A'); ?></p>
                    </div>
                    <div>
                        <p class="text-caption text-gray-600 mb-gr-3xs">Mobile Number</p>
                        <p class="text-body font-medium text-gray-900"><?php echo e($citizen->mobile_number ?? 'N/A'); ?></p>
                    </div>
                    <div>
                        <p class="text-caption text-gray-600 mb-gr-3xs">Citizen ID</p>
                        <p class="text-body font-mono font-medium text-gray-900"><?php echo e($citizen->id); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-gr-lg">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg">
                <h3 class="text-h6 font-bold text-gray-900 mb-gr-md">Actions</h3>
                <div class="space-y-gr-sm">
                    <button onclick="printTransaction()" class="w-full btn-secondary justify-center">
                        <i data-lucide="printer" class="w-4 h-4 mr-gr-xs"></i>
                        Print Receipt
                    </button>
                    <button onclick="sendReceipt()" class="w-full btn-secondary justify-center">
                        <i data-lucide="mail" class="w-4 h-4 mr-gr-xs"></i>
                        Email Receipt
                    </button>
                    <?php if($transaction->status == 'pending'): ?>
                    <button onclick="markAsPaid()" class="w-full bg-green-600 hover:bg-green-700 text-white px-gr-md py-gr-sm rounded-lg font-medium transition-colors flex items-center justify-center">
                        <i data-lucide="circle-check" class="w-4 h-4 mr-gr-xs"></i>
                        Mark as Paid
                    </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg">
                <h3 class="text-h6 font-bold text-gray-900 mb-gr-md">Timeline</h3>
                <div class="space-y-gr-md">
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-gr-sm flex-shrink-0">
                            <i data-lucide="plus" class="w-4 h-4 text-blue-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-small font-semibold text-gray-900">Transaction Created</p>
                            <p class="text-caption text-gray-500"><?php echo e(\Carbon\Carbon::parse($transaction->created_at)->format('M d, Y h:i A')); ?></p>
                        </div>
                    </div>

                    <?php if($transaction->paid_at): ?>
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-gr-sm flex-shrink-0">
                            <i data-lucide="circle-check" class="w-4 h-4 text-green-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-small font-semibold text-gray-900">Payment Received</p>
                            <p class="text-caption text-gray-500"><?php echo e(\Carbon\Carbon::parse($transaction->paid_at)->format('M d, Y h:i A')); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($transaction->status == 'cancelled'): ?>
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-gr-sm flex-shrink-0">
                            <i data-lucide="x-circle" class="w-4 h-4 text-red-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-small font-semibold text-gray-900">Transaction Cancelled</p>
                            <p class="text-caption text-gray-500"><?php echo e(\Carbon\Carbon::parse($transaction->updated_at)->format('M d, Y h:i A')); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Summary -->
            <div class="bg-lgu-green bg-opacity-10 rounded-xl border border-lgu-green p-gr-lg">
                <h3 class="text-h6 font-bold text-gray-900 mb-gr-md">Payment Summary</h3>
                <div class="space-y-gr-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-small text-gray-600">Facility Fee</span>
                        <span class="text-small font-semibold text-gray-900">₱<?php echo e(number_format($booking->total_cost ?? $transaction->amount_due, 2)); ?></span>
                    </div>
                    <div class="pt-gr-sm border-t border-lgu-green">
                        <div class="flex justify-between items-center">
                            <span class="text-body font-bold text-gray-900">Total</span>
                            <span class="text-h5 font-bold text-lgu-green">₱<?php echo e(number_format($transaction->amount_due, 2)); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function printTransaction() {
    window.print();
}

function sendReceipt() {
    Swal.fire({
        title: 'Send Receipt',
        text: "Send the digital receipt to <?php echo e($citizen->email ?? 'this citizen'); ?>?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#047857',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Send it',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading state
            Swal.fire({
                title: 'Sending...',
                text: 'Please wait while the email is being processed.',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            // AJAX Call to Backend
            fetch("<?php echo e(route('admin.transactions.email', $transaction->id)); ?>", {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Email Sent!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#047857'
                    });
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error',
                    text: 'The email could not be sent: ' + error.message,
                    icon: 'error',
                    confirmButtonColor: '#047857'
                });
            });
        }
    });
}

function markAsPaid() {
    Swal.fire({
        title: 'Mark as Paid',
        text: 'Confirm that this payment has been received?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#047857',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, mark as paid',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit form to mark as paid
            Swal.fire({
                title: 'Coming Soon',
                text: 'Manual payment confirmation will be available soon!',
                icon: 'info',
                confirmButtonColor: '#047857'
            });
        }
    });
}

// Initialize Lucide icons
lucide.createIcons();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\LGU-PUBLIC-FACILITIES-RESERVATION-SYSTEM\resources\views/admin/transactions/show.blade.php ENDPATH**/ ?>