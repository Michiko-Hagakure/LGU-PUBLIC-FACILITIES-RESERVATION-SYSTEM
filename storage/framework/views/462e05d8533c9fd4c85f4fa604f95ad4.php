

<?php $__env->startSection('page-title', 'Schedule Maintenance'); ?>
<?php $__env->startSection('page-subtitle', 'Block facility dates for maintenance'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="max-w-4xl mx-auto space-y-gr-lg">
    
    <div>
        <a href="<?php echo e(route('admin.maintenance.index')); ?>" class="inline-flex items-center text-lgu-button hover:underline mb-gr-sm">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
            Back to Maintenance Schedule
        </a>
        <h1 class="text-h1 font-bold text-lgu-headline mb-gr-xs">Schedule Maintenance</h1>
        <p class="text-body text-lgu-paragraph">Block facility dates for maintenance or repairs</p>
    </div>

    
    <form method="POST" action="<?php echo e(route('admin.maintenance.store')); ?>" class="space-y-gr-lg">
        <?php echo csrf_field(); ?>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg space-y-gr-md">
            <h2 class="text-h2 font-bold text-lgu-headline">Maintenance Details</h2>

            
            <div>
                <label for="facility_id" class="block text-body font-semibold text-lgu-headline mb-gr-xs">
                    Facility <span class="text-lgu-tertiary">*</span>
                </label>
                <select id="facility_id" name="facility_id" required class="w-full px-4 py-3 border-2 <?php $__errorArgs = ['facility_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-lgu-tertiary <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                    <option value="">Select facility...</option>
                    <?php $__currentLoopData = $facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($facility->facility_id); ?>" <?php echo e(old('facility_id', request('facility_id')) == $facility->facility_id ? 'selected' : ''); ?>>
                            <?php echo e($facility->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['facility_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-lgu-tertiary text-small mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-gr-md">
                <div>
                    <label for="start_date" class="block text-body font-semibold text-lgu-headline mb-gr-xs">
                        Start Date <span class="text-lgu-tertiary">*</span>
                    </label>
                    <input type="date" id="start_date" name="start_date" required value="<?php echo e(old('start_date')); ?>" min="<?php echo e(now()->toDateString()); ?>"
                        class="w-full px-4 py-3 border-2 <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-lgu-tertiary <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                    <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-lgu-tertiary text-small mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="end_date" class="block text-body font-semibold text-lgu-headline mb-gr-xs">
                        End Date <span class="text-lgu-tertiary">*</span>
                    </label>
                    <input type="date" id="end_date" name="end_date" required value="<?php echo e(old('end_date')); ?>" min="<?php echo e(now()->toDateString()); ?>"
                        class="w-full px-4 py-3 border-2 <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-lgu-tertiary <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                    <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-lgu-tertiary text-small mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-gr-md">
                <div>
                    <label for="start_time" class="block text-body font-semibold text-lgu-headline mb-gr-xs">
                        Start Time <span class="text-small text-gray-600">(Optional - leave empty for all-day)</span>
                    </label>
                    <input type="time" id="start_time" name="start_time" value="<?php echo e(old('start_time')); ?>"
                        class="w-full px-4 py-3 border-2 <?php $__errorArgs = ['start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-lgu-tertiary <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                    <?php $__errorArgs = ['start_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-lgu-tertiary text-small mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="end_time" class="block text-body font-semibold text-lgu-headline mb-gr-xs">
                        End Time <span class="text-small text-gray-600">(Optional)</span>
                    </label>
                    <input type="time" id="end_time" name="end_time" value="<?php echo e(old('end_time')); ?>"
                        class="w-full px-4 py-3 border-2 <?php $__errorArgs = ['end_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-lgu-tertiary <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                    <?php $__errorArgs = ['end_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-lgu-tertiary text-small mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            
            <div>
                <label for="maintenance_type" class="block text-body font-semibold text-lgu-headline mb-gr-xs">
                    Maintenance Type <span class="text-lgu-tertiary">*</span>
                </label>
                <select id="maintenance_type" name="maintenance_type" required class="w-full px-4 py-3 border-2 <?php $__errorArgs = ['maintenance_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-lgu-tertiary <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                    <option value="">Select type...</option>
                    <?php $__currentLoopData = $maintenanceTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>" <?php echo e(old('maintenance_type') == $key ? 'selected' : ''); ?>>
                            <?php echo e($label); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['maintenance_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-lgu-tertiary text-small mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div>
                <label for="description" class="block text-body font-semibold text-lgu-headline mb-gr-xs">
                    Description <span class="text-lgu-tertiary">*</span>
                </label>
                <textarea id="description" name="description" required rows="4" placeholder="Describe the maintenance work..."
                    class="w-full px-4 py-3 border-2 <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-lgu-tertiary <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent"><?php echo e(old('description')); ?></textarea>
                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-lgu-tertiary text-small mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div>
                <label for="notes" class="block text-body font-semibold text-lgu-headline mb-gr-xs">
                    Internal Notes <span class="text-small text-gray-600">(Optional)</span>
                </label>
                <textarea id="notes" name="notes" rows="3" placeholder="Additional notes for internal reference..."
                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent"><?php echo e(old('notes')); ?></textarea>
            </div>

            
            <div class="border-t border-gray-200 pt-gr-md">
                <label class="flex items-start gap-gr-sm cursor-pointer">
                    <input type="checkbox" id="is_recurring" name="is_recurring" value="1" <?php echo e(old('is_recurring') ? 'checked' : ''); ?>

                        class="mt-1 rounded border-gray-300 text-lgu-button focus:ring-lgu-highlight"
                        onchange="document.getElementById('recurring-options').classList.toggle('hidden')">
                    <div>
                        <span class="text-body font-semibold text-lgu-headline">Recurring Maintenance</span>
                        <p class="text-small text-gray-600">Schedule this maintenance to repeat automatically</p>
                    </div>
                </label>

                <div id="recurring-options" class="<?php echo e(old('is_recurring') ? '' : 'hidden'); ?> mt-gr-md ml-gr-lg">
                    <label for="recurring_pattern" class="block text-body font-semibold text-lgu-headline mb-gr-xs">
                        Repeat Pattern
                    </label>
                    <select id="recurring_pattern" name="recurring_pattern" class="w-full md:w-1/2 px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                        <option value="">Select pattern...</option>
                        <option value="daily" <?php echo e(old('recurring_pattern') == 'daily' ? 'selected' : ''); ?>>Daily</option>
                        <option value="weekly" <?php echo e(old('recurring_pattern') == 'weekly' ? 'selected' : ''); ?>>Weekly</option>
                        <option value="monthly" <?php echo e(old('recurring_pattern') == 'monthly' ? 'selected' : ''); ?>>Monthly</option>
                        <option value="yearly" <?php echo e(old('recurring_pattern') == 'yearly' ? 'selected' : ''); ?>>Yearly</option>
                    </select>
                </div>
            </div>
        </div>

        
        <div class="flex items-center justify-between">
            <a href="<?php echo e(route('admin.maintenance.index')); ?>" class="inline-flex items-center px-gr-lg py-gr-md bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors duration-200">
                <i data-lucide="x" class="w-5 h-5 mr-gr-xs"></i>
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center px-gr-lg py-gr-md bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-opacity-90 transition-colors duration-200">
                <i data-lucide="check" class="w-5 h-5 mr-gr-xs"></i>
                Schedule Maintenance
            </button>
        </div>
    </form>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
if (typeof lucide !== 'undefined') {
    lucide.createIcons();
}

// Auto-update end date when start date changes
document.getElementById('start_date').addEventListener('change', function() {
    const endDateInput = document.getElementById('end_date');
    if (!endDateInput.value || endDateInput.value < this.value) {
        endDateInput.value = this.value;
        endDateInput.min = this.value;
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\LGU-PUBLIC-FACILITIES-RESERVATION-SYSTEM\resources\views/admin/maintenance/create.blade.php ENDPATH**/ ?>