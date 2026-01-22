

<?php $__env->startSection('page-title', 'Maintenance Schedule'); ?>
<?php $__env->startSection('page-subtitle', 'Manage facility maintenance and downtime'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="space-y-gr-lg">
    
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-h1 font-bold text-lgu-headline mb-gr-xs">Maintenance Schedule</h1>
            <p class="text-body text-lgu-paragraph">Schedule and track facility maintenance periods</p>
        </div>
        <a href="<?php echo e(route('admin.maintenance.create')); ?>" class="inline-flex items-center px-gr-lg py-gr-md bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-opacity-90 transition-colors duration-200">
            <i data-lucide="plus" class="w-5 h-5 mr-gr-xs"></i>
            Schedule Maintenance
        </a>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-gr-md">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-caption text-gray-600 uppercase mb-1">Upcoming Maintenance</div>
                    <div class="text-h1 font-bold text-lgu-headline"><?php echo e($upcomingCount); ?></div>
                </div>
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                    <i data-lucide="calendar-clock" class="w-8 h-8 text-blue-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-caption text-gray-600 uppercase mb-1">Ongoing Maintenance</div>
                    <div class="text-h1 font-bold text-amber-600"><?php echo e($ongoingCount); ?></div>
                </div>
                <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center">
                    <i data-lucide="wrench" class="w-8 h-8 text-amber-600"></i>
                </div>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg">
        <form method="GET" action="<?php echo e(route('admin.maintenance.index')); ?>" class="space-y-gr-md">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-gr-md">
                <div>
                    <label for="facility_id" class="block text-small font-semibold text-lgu-headline mb-gr-xs">Facility</label>
                    <select id="facility_id" name="facility_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                        <option value="">All Facilities</option>
                        <?php $__currentLoopData = $facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($facility->facility_id); ?>" <?php echo e($facilityId == $facility->facility_id ? 'selected' : ''); ?>>
                                <?php echo e($facility->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label for="type" class="block text-small font-semibold text-lgu-headline mb-gr-xs">Type</label>
                    <select id="type" name="type" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                        <option value="">All Types</option>
                        <?php $__currentLoopData = $maintenanceTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e($type == $key ? 'selected' : ''); ?>>
                                <?php echo e($label); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div>
                    <label for="time_filter" class="block text-small font-semibold text-lgu-headline mb-gr-xs">Time Period</label>
                    <select id="time_filter" name="time_filter" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                        <option value="upcoming" <?php echo e($timeFilter == 'upcoming' ? 'selected' : ''); ?>>Upcoming</option>
                        <option value="ongoing" <?php echo e($timeFilter == 'ongoing' ? 'selected' : ''); ?>>Ongoing</option>
                        <option value="past" <?php echo e($timeFilter == 'past' ? 'selected' : ''); ?>>Past</option>
                        <option value="all" <?php echo e($timeFilter == 'all' ? 'selected' : ''); ?>>All</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-gr-sm">
                <button type="submit" class="inline-flex items-center px-gr-md py-gr-sm bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-opacity-90 transition-colors duration-200">
                    <i data-lucide="filter" class="w-5 h-5 mr-gr-xs"></i>
                    Apply Filters
                </button>
                <a href="<?php echo e(route('admin.maintenance.index')); ?>" class="inline-flex items-center px-gr-md py-gr-sm bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors duration-200">
                    <i data-lucide="x" class="w-5 h-5 mr-gr-xs"></i>
                    Clear
                </a>
            </div>
        </form>
    </div>

    
    <?php if($schedules->count() > 0): ?>
        <div class="space-y-gr-md">
            <?php $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $isOngoing = $schedule->start_date <= now()->toDateString() && $schedule->end_date >= now()->toDateString();
                    $isPast = $schedule->end_date < now()->toDateString();
                ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-gr-sm mb-gr-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                    <?php if($isOngoing): ?> bg-amber-100 text-amber-800
                                    <?php elseif($isPast): ?> bg-gray-100 text-gray-600
                                    <?php else: ?> bg-blue-100 text-blue-800
                                    <?php endif; ?>">
                                    <?php if($isOngoing): ?>
                                        <i data-lucide="wrench" class="w-3 h-3 inline mr-1"></i> Ongoing
                                    <?php elseif($isPast): ?>
                                        <i data-lucide="check-circle" class="w-3 h-3 inline mr-1"></i> Completed
                                    <?php else: ?>
                                        <i data-lucide="calendar-clock" class="w-3 h-3 inline mr-1"></i> Upcoming
                                    <?php endif; ?>
                                </span>
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                    <?php echo e($maintenanceTypes[$schedule->maintenance_type] ?? $schedule->maintenance_type); ?>

                                </span>
                            </div>

                            <h3 class="text-h3 font-bold text-lgu-headline mb-gr-xs"><?php echo e($schedule->facility->name); ?></h3>

                            <div class="grid grid-cols-2 md:grid-cols-3 gap-gr-md text-small mb-gr-sm">
                                <div class="flex items-center text-gray-600">
                                    <i data-lucide="calendar" class="w-4 h-4 mr-2"></i>
                                    <span><?php echo e(\Carbon\Carbon::parse($schedule->start_date)->format('M j')); ?> - <?php echo e(\Carbon\Carbon::parse($schedule->end_date)->format('M j, Y')); ?></span>
                                </div>
                                <?php if($schedule->start_time && $schedule->end_time): ?>
                                    <div class="flex items-center text-gray-600">
                                        <i data-lucide="clock" class="w-4 h-4 mr-2"></i>
                                        <span><?php echo e(\Carbon\Carbon::parse($schedule->start_time)->format('g:i A')); ?> - <?php echo e(\Carbon\Carbon::parse($schedule->end_time)->format('g:i A')); ?></span>
                                    </div>
                                <?php else: ?>
                                    <div class="flex items-center text-gray-600">
                                        <i data-lucide="clock" class="w-4 h-4 mr-2"></i>
                                        <span>All Day</span>
                                    </div>
                                <?php endif; ?>
                                <?php if($schedule->is_recurring): ?>
                                    <div class="flex items-center text-gray-600">
                                        <i data-lucide="repeat" class="w-4 h-4 mr-2"></i>
                                        <span>Recurring (<?php echo e(ucfirst($schedule->recurring_pattern)); ?>)</span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <p class="text-body text-gray-700"><?php echo e($schedule->description); ?></p>
                        </div>

                        <form method="POST" action="<?php echo e(route('admin.maintenance.destroy', $schedule->id)); ?>" onsubmit="return confirm('Cancel this maintenance schedule?');">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200" title="Cancel Maintenance">
                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <?php if($schedules->hasPages()): ?>
            <div class="mt-gr-lg">
                <?php echo e($schedules->links()); ?>

            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <i data-lucide="calendar-clock" class="w-16 h-16 text-gray-300 mb-gr-md mx-auto"></i>
            <h3 class="text-h3 font-bold text-lgu-headline mb-gr-xs">No Maintenance Scheduled</h3>
            <p class="text-body text-gray-600 mb-gr-md">Schedule maintenance to block booking slots</p>
            <a href="<?php echo e(route('admin.maintenance.create')); ?>" class="inline-flex items-center px-gr-lg py-gr-md bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-opacity-90 transition-colors duration-200">
                <i data-lucide="plus" class="w-5 h-5 mr-gr-xs"></i>
                Schedule Maintenance
            </a>
        </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\LGU-PUBLIC-FACILITIES-RESERVATION-SYSTEM\resources\views/admin/maintenance/index.blade.php ENDPATH**/ ?>