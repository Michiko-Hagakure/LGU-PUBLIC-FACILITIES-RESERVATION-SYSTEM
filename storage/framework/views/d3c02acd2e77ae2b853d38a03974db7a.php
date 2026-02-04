

<?php $__env->startSection('title', 'Operational Metrics - Admin'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="space-y-6">
    <!-- Header with Date Filter -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-lgu-headline">Operational Metrics Dashboard</h2>
                <p class="text-gray-600 mt-1">Monitor system performance and workflow efficiency</p>
            </div>
            
            <form method="GET" action="<?php echo e(route('admin.analytics.operational-metrics')); ?>" class="flex items-center gap-3">
                <input type="date" name="start_date" value="<?php echo e($startDate); ?>" 
                       class="px-4 py-2 border border-lgu-stroke rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight">
                <span class="text-gray-600">to</span>
                <input type="date" name="end_date" value="<?php echo e($endDate); ?>" 
                       class="px-4 py-2 border border-lgu-stroke rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight">
                <button type="submit" class="px-6 py-2 bg-lgu-headline text-white rounded-lg hover:bg-opacity-90 transition-all">
                    <i data-lucide="filter" class="w-4 h-4 inline mr-2"></i>
                    Filter
                </button>
            </form>
        </div>
    </div>

    <!-- Processing Time Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Avg Staff Verification Time -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-gray-600">Avg. Staff Verification</h3>
                <i data-lucide="clock" class="w-5 h-5 text-blue-500"></i>
            </div>
            <p class="text-3xl font-bold text-lgu-headline"><?php echo e(round($avgStaffVerificationTime ?? 0, 1)); ?>h</p>
            <p class="text-xs <?php echo e(($avgStaffVerificationTime ?? 0) > 48 ? 'text-red-600' : 'text-gray-500'); ?> mt-2">
                <?php echo e(($avgStaffVerificationTime ?? 0) > 48 ? '⚠️ Above target (48h)' : '✓ Within target'); ?>

            </p>
        </div>

        <!-- Avg Payment Time -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-gray-600">Avg. Payment Time</h3>
                <i data-lucide="credit-card" class="w-5 h-5 text-green-500"></i>
            </div>
            <p class="text-3xl font-bold text-lgu-headline"><?php echo e(round($avgPaymentTime ?? 0, 1)); ?>h</p>
            <p class="text-xs <?php echo e(($avgPaymentTime ?? 0) > 24 ? 'text-yellow-600' : 'text-gray-500'); ?> mt-2">
                <?php echo e(($avgPaymentTime ?? 0) > 24 ? '⚠️ Above target (24h)' : '✓ Within target'); ?>

            </p>
        </div>

        <!-- Total Processing Time -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium text-gray-600">Total Processing Time</h3>
                <i data-lucide="activity" class="w-5 h-5 text-purple-500"></i>
            </div>
            <p class="text-3xl font-bold text-lgu-headline"><?php echo e(round($avgTotalProcessingTime ?? 0, 1)); ?>h</p>
            <p class="text-xs text-gray-500 mt-2">From submission to confirmation</p>
        </div>
    </div>

    <!-- Booking Status Rates -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-lgu-headline mb-6">Booking Outcome Rates</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Total Bookings -->
            <div class="text-center">
                <div class="text-4xl font-bold text-lgu-headline mb-2"><?php echo e(number_format($totalBookings)); ?></div>
                <p class="text-sm text-gray-600">Total Bookings</p>
            </div>

            <!-- Completion Rate -->
            <div class="text-center">
                <div class="text-4xl font-bold text-green-600 mb-2"><?php echo e(number_format($completionRate, 1)); ?>%</div>
                <p class="text-sm text-gray-600">Completion Rate</p>
                <p class="text-xs text-gray-500 mt-1"><?php echo e($completedBookings); ?> completed</p>
            </div>

            <!-- Expiration Rate -->
            <div class="text-center">
                <div class="text-4xl font-bold text-red-600 mb-2"><?php echo e(number_format($expirationRate, 1)); ?>%</div>
                <p class="text-sm text-gray-600">Expiration Rate</p>
                <p class="text-xs text-gray-500 mt-1"><?php echo e($expiredBookings); ?> expired</p>
            </div>

            <!-- Cancellation Rate -->
            <div class="text-center">
                <div class="text-4xl font-bold text-orange-600 mb-2"><?php echo e(number_format($cancellationRate, 1)); ?>%</div>
                <p class="text-sm text-gray-600">Cancellation Rate</p>
                <p class="text-xs text-gray-500 mt-1"><?php echo e($cancelledBookings); ?> cancelled</p>
            </div>
        </div>
    </div>

    <!-- Bottleneck Alerts -->
    <?php if(count($bottlenecks) > 0): ?>
    <div class="bg-yellow-50 border-l-4 border-yellow-400 rounded-lg p-6">
        <div class="flex items-start">
            <i data-lucide="alert-triangle" class="w-6 h-6 text-yellow-600 mr-3 flex-shrink-0 mt-0.5"></i>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-yellow-800 mb-3">Workflow Bottlenecks Detected</h3>
                <div class="space-y-3">
                    <?php $__currentLoopData = $bottlenecks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bottleneck): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold text-gray-900"><?php echo e($bottleneck['stage']); ?></span>
                            <span class="px-3 py-1 bg-<?php echo e($bottleneck['severity'] === 'high' ? 'red' : 'yellow'); ?>-100 text-<?php echo e($bottleneck['severity'] === 'high' ? 'red' : 'yellow'); ?>-800 rounded-full text-xs font-semibold">
                                <?php echo e(ucfirst($bottleneck['severity'])); ?> Priority
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">Average time: <span class="font-semibold"><?php echo e($bottleneck['avg_hours']); ?> hours</span></p>
                        <p class="text-sm text-gray-700"><strong>Recommendation:</strong> <?php echo e($bottleneck['recommendation']); ?></p>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Staff Performance -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-lgu-headline mb-6">Staff Performance Metrics</h3>
        
        <?php if($staffPerformance->count() > 0): ?>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Staff Member</th>
                        <th class="text-center py-3 px-4 text-sm font-semibold text-gray-700">Total Verified</th>
                        <th class="text-center py-3 px-4 text-sm font-semibold text-gray-700">Approved</th>
                        <th class="text-center py-3 px-4 text-sm font-semibold text-gray-700">Rejected</th>
                        <th class="text-center py-3 px-4 text-sm font-semibold text-gray-700">Approval Rate</th>
                        <th class="text-center py-3 px-4 text-sm font-semibold text-gray-700">Avg. Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $staffPerformance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $performance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $staff = $staffNames->get($performance->staff_verified_by);
                        $approvalRate = $performance->total_verified > 0 ? ($performance->approved_count / $performance->total_verified) * 100 : 0;
                    ?>
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                        <td class="py-4 px-4">
                            <p class="font-medium text-gray-900"><?php echo e($staff->full_name ?? 'Unknown Staff'); ?></p>
                            <p class="text-xs text-gray-500"><?php echo e($staff->email ?? ''); ?></p>
                        </td>
                        <td class="py-4 px-4 text-center font-semibold text-gray-900">
                            <?php echo e($performance->total_verified); ?>

                        </td>
                        <td class="py-4 px-4 text-center text-green-600 font-medium">
                            <?php echo e($performance->approved_count); ?>

                        </td>
                        <td class="py-4 px-4 text-center text-red-600 font-medium">
                            <?php echo e($performance->rejected_count); ?>

                        </td>
                        <td class="py-4 px-4 text-center">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold <?php echo e($approvalRate >= 80 ? 'bg-green-100 text-green-800' : ($approvalRate >= 50 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800')); ?>">
                                <?php echo e(number_format($approvalRate, 1)); ?>%
                            </span>
                        </td>
                        <td class="py-4 px-4 text-center text-sm text-gray-600">
                            <?php echo e(round($performance->avg_verification_hours ?? 0, 1)); ?>h
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="text-center py-8">
            <i data-lucide="users" class="w-12 h-12 mx-auto mb-3 text-gray-400"></i>
            <p class="text-gray-600">No staff verification data available for this period</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Rejection Reasons -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-lgu-headline mb-6">Rejection Reasons Breakdown</h3>
        
        <?php if($rejectionReasons->count() > 0): ?>
        <div class="space-y-3">
            <?php $__currentLoopData = $rejectionReasons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reason): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $percentage = $totalBookings > 0 ? ($reason->count / $totalBookings) * 100 : 0;
            ?>
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700"><?php echo e($reason->rejected_reason); ?></span>
                    <span class="text-sm text-gray-600"><?php echo e($reason->count); ?> (<?php echo e(number_format($percentage, 1)); ?>%)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-red-500 h-2 rounded-full transition-all" style="width: <?php echo e(min($percentage * 10, 100)); ?>%"></div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php else: ?>
        <div class="text-center py-8">
            <i data-lucide="check-circle" class="w-12 h-12 mx-auto mb-3 text-green-400"></i>
            <p class="text-gray-600">No rejections during this period</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// Initialize Lucide icons
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\LGU-PUBLIC-FACILITIES-RESERVATION-SYSTEM\resources\views/admin/analytics/operational-metrics.blade.php ENDPATH**/ ?>