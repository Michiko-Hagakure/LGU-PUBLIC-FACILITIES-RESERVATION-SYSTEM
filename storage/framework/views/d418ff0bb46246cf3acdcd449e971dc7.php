

<?php $__env->startSection('page-title', 'Activity Log'); ?>
<?php $__env->startSection('page-subtitle', 'Complete history of your actions and verifications'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="space-y-gr-lg">
    
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-h1 font-bold text-lgu-headline mb-gr-xs">Activity Log</h1>
            <p class="text-body text-lgu-paragraph">Track all your actions and verifications</p>
        </div>
        <div class="flex items-center gap-gr-sm">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 px-gr-md py-gr-sm">
                <div class="text-caption text-gray-600 uppercase mb-1">Total Activities</div>
                <div class="text-h2 font-bold text-lgu-headline"><?php echo e(number_format($totalActivities)); ?></div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 px-gr-md py-gr-sm">
                <div class="text-caption text-gray-600 uppercase mb-1">Today</div>
                <div class="text-h2 font-bold text-lgu-button"><?php echo e($todayActivities); ?></div>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg">
        <form method="GET" action="<?php echo e(route('staff.activity-log.index')); ?>" class="space-y-gr-md">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-gr-md">
                <div>
                    <label for="search" class="block text-small font-semibold text-lgu-headline mb-gr-xs">Search</label>
                    <input type="text" 
                           id="search" 
                           name="search" 
                           value="<?php echo e($search); ?>" 
                           placeholder="Search activities..."
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                </div>
                <div>
                    <label for="action" class="block text-small font-semibold text-lgu-headline mb-gr-xs">Action Type</label>
                    <select id="action" 
                            name="action" 
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                        <option value="">All Actions</option>
                        <?php $__currentLoopData = $availableActions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $availableAction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($availableAction); ?>" <?php echo e($action == $availableAction ? 'selected' : ''); ?>>
                                <?php echo e(ucfirst($availableAction)); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label for="date_from" class="block text-small font-semibold text-lgu-headline mb-gr-xs">Date From</label>
                    <input type="date" 
                           id="date_from" 
                           name="date_from" 
                           value="<?php echo e($dateFrom); ?>"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                </div>
                <div>
                    <label for="date_to" class="block text-small font-semibold text-lgu-headline mb-gr-xs">Date To</label>
                    <input type="date" 
                           id="date_to" 
                           name="date_to" 
                           value="<?php echo e($dateTo); ?>"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                </div>
            </div>
            <div class="flex items-center gap-gr-sm">
                <button type="submit" 
                        class="inline-flex items-center px-gr-md py-gr-sm bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-opacity-90 transition-colors duration-200">
                    <i data-lucide="filter" class="w-5 h-5 mr-gr-xs"></i>
                    Apply Filters
                </button>
                <?php if($search || $action || $dateFrom || $dateTo): ?>
                    <a href="<?php echo e(route('staff.activity-log.index')); ?>" 
                       class="inline-flex items-center px-gr-md py-gr-sm bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors duration-200">
                        <i data-lucide="x" class="w-5 h-5 mr-gr-xs"></i>
                        Clear Filters
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    
    <?php if($activities->count() > 0): ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg">
            <div class="space-y-gr-md">
                <?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex gap-gr-md p-gr-md rounded-lg border border-gray-100 hover:border-lgu-button hover:shadow-md transition-all duration-200">
                        <!-- Action Icon -->
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center
                                <?php echo e($activity->action === 'verify' ? 'bg-blue-100 text-blue-600' : ''); ?>

                                <?php echo e($activity->action === 'approve' ? 'bg-green-100 text-green-600' : ''); ?>

                                <?php echo e($activity->action === 'reject' ? 'bg-red-100 text-red-600' : ''); ?>

                                <?php echo e($activity->action === 'update' ? 'bg-amber-100 text-amber-600' : ''); ?>

                                <?php echo e($activity->action === 'create' ? 'bg-purple-100 text-purple-600' : ''); ?>

                                <?php echo e(!in_array($activity->action, ['verify', 'approve', 'reject', 'update', 'create']) ? 'bg-gray-100 text-gray-600' : ''); ?>">
                                <?php if($activity->action === 'verify'): ?>
                                    <i data-lucide="check-circle" class="w-6 h-6"></i>
                                <?php elseif($activity->action === 'approve'): ?>
                                    <i data-lucide="thumbs-up" class="w-6 h-6"></i>
                                <?php elseif($activity->action === 'reject'): ?>
                                    <i data-lucide="thumbs-down" class="w-6 h-6"></i>
                                <?php elseif($activity->action === 'update'): ?>
                                    <i data-lucide="edit" class="w-6 h-6"></i>
                                <?php elseif($activity->action === 'create'): ?>
                                    <i data-lucide="plus-circle" class="w-6 h-6"></i>
                                <?php else: ?>
                                    <i data-lucide="activity" class="w-6 h-6"></i>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Activity Details -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-gr-sm mb-2">
                                <div>
                                    <h3 class="text-body font-bold text-gray-900">
                                        <?php echo e(ucfirst($activity->action)); ?> <?php echo e($activity->model); ?>

                                    </h3>
                                    <p class="text-small text-gray-600">
                                        <?php echo e(\Carbon\Carbon::parse($activity->created_at)->format('M j, Y g:i A')); ?>

                                        <span class="text-gray-400">•</span>
                                        <?php echo e(\Carbon\Carbon::parse($activity->created_at)->diffForHumans()); ?>

                                    </p>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    <?php echo e($activity->action === 'approve' ? 'bg-green-100 text-green-800' : ''); ?>

                                    <?php echo e($activity->action === 'reject' ? 'bg-red-100 text-red-800' : ''); ?>

                                    <?php echo e($activity->action === 'verify' ? 'bg-blue-100 text-blue-800' : ''); ?>

                                    <?php echo e($activity->action === 'update' ? 'bg-amber-100 text-amber-800' : ''); ?>

                                    <?php echo e($activity->action === 'create' ? 'bg-purple-100 text-purple-800' : ''); ?>

                                    <?php echo e(!in_array($activity->action, ['verify', 'approve', 'reject', 'update', 'create']) ? 'bg-gray-100 text-gray-800' : ''); ?>">
                                    <?php echo e(ucfirst($activity->action)); ?>

                                </span>
                            </div>

                            <!-- Booking Info if Available -->
                            <?php if(isset($activity->booking)): ?>
                                <div class="bg-gray-50 rounded-lg p-gr-sm mb-gr-sm">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-gr-sm text-small">
                                        <div>
                                            <span class="text-gray-600 font-medium">Booking:</span>
                                            <span class="text-gray-900 font-semibold ml-1"><?php echo e($activity->booking->booking_reference); ?></span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600 font-medium">Facility:</span>
                                            <span class="text-gray-900 ml-1"><?php echo e($activity->booking->facility_name); ?></span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600 font-medium">Status:</span>
                                            <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold
                                                <?php echo e($activity->booking->status === 'confirmed' ? 'bg-green-100 text-green-800' : ''); ?>

                                                <?php echo e($activity->booking->status === 'rejected' ? 'bg-red-100 text-red-800' : ''); ?>

                                                <?php echo e($activity->booking->status === 'pending' ? 'bg-amber-100 text-amber-800' : ''); ?>

                                                <?php echo e($activity->booking->status === 'staff_verified' ? 'bg-blue-100 text-blue-800' : ''); ?>">
                                                <?php echo e(ucfirst(str_replace('_', ' ', $activity->booking->status))); ?>

                                            </span>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Changes Details -->
                            <?php if($activity->changes_array && count($activity->changes_array) > 0): ?>
                                <div class="text-small">
                                    <button type="button" 
                                            onclick="toggleChanges(<?php echo e($activity->id); ?>)"
                                            class="text-lgu-button hover:text-lgu-highlight font-semibold flex items-center gap-1">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                        View Changes
                                    </button>
                                    <div id="changes-<?php echo e($activity->id); ?>" class="hidden mt-2 p-gr-sm bg-blue-50 rounded border border-blue-100">
                                        <pre class="text-xs text-gray-700 whitespace-pre-wrap overflow-x-auto"><?php echo e(json_encode($activity->changes_array, JSON_PRETTY_PRINT)); ?></pre>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- IP Address and User Agent (collapsed by default) -->
                            <div class="mt-2 text-xs text-gray-500">
                                <span class="font-medium">IP:</span> <?php echo e($activity->ip_address); ?>

                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <?php if($activities->hasPages()): ?>
            <div class="mt-gr-lg">
                <?php echo e($activities->links()); ?>

            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <i data-lucide="inbox" class="w-16 h-16 text-gray-300 mb-gr-md mx-auto"></i>
            <h3 class="text-h3 font-bold text-lgu-headline mb-gr-xs">No Activities Found</h3>
            <p class="text-body text-gray-600">No activities match your current filters.</p>
            <?php if($search || $action || $dateFrom || $dateTo): ?>
                <a href="<?php echo e(route('staff.activity-log.index')); ?>" 
                   class="inline-flex items-center mt-gr-md px-gr-md py-gr-sm bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-opacity-90 transition-colors duration-200">
                    <i data-lucide="refresh-cw" class="w-5 h-5 mr-gr-xs"></i>
                    Clear Filters
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}

function toggleChanges(activityId) {
    const changesDiv = document.getElementById('changes-' + activityId);
    if (changesDiv.classList.contains('hidden')) {
        changesDiv.classList.remove('hidden');
    } else {
        changesDiv.classList.add('hidden');
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.staff', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\LGU-PUBLIC-FACILITIES-RESERVATION-SYSTEM\resources\views/staff/activity-log/index.blade.php ENDPATH**/ ?>