

<?php $__env->startSection('title', 'View Facilities'); ?>
<?php $__env->startSection('page-title', 'View Facilities'); ?>
<?php $__env->startSection('page-subtitle', 'Browse all available public facilities'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="container mx-auto px-gr-md py-gr-lg">
    
    <div class="mb-gr-lg">
        <h1 class="text-h1 font-bold text-lgu-headline mb-gr-xs">Facilities Directory</h1>
        <p class="text-body text-lgu-paragraph">Browse all available facilities and their details</p>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg mb-gr-lg">
        <form method="GET" action="<?php echo e(route('staff.facilities.index')); ?>" class="space-y-gr-md">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gr-md">
                
                <div>
                    <label for="search" class="block text-small font-semibold text-lgu-headline mb-gr-xs">Search</label>
                    <div class="relative">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                        <input type="text" id="search" name="search" value="<?php echo e($search); ?>" 
                            placeholder="Search facilities..." 
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                    </div>
                </div>

                
                <div>
                    <label for="city_id" class="block text-small font-semibold text-lgu-headline mb-gr-xs">City</label>
                    <select id="city_id" name="city_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                        <option value="">All Cities</option>
                        <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($city->id); ?>" <?php echo e($cityId == $city->id ? 'selected' : ''); ?>>
                                <?php echo e($city->city_name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div>
                    <label for="facility_type" class="block text-small font-semibold text-lgu-headline mb-gr-xs">Type</label>
                    <select id="facility_type" name="facility_type" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                        <option value="">All Types</option>
                        <?php $__currentLoopData = $facilityTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e($facilityType == $key ? 'selected' : ''); ?>>
                                <?php echo e($label); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div>
                    <label for="status" class="block text-small font-semibold text-lgu-headline mb-gr-xs">Status</label>
                    <select id="status" name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                        <option value="">All Statuses</option>
                        <?php $__currentLoopData = $statusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e($status == $key ? 'selected' : ''); ?>>
                                <?php echo e($label); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <div class="flex items-center gap-gr-sm">
                <button type="submit" class="inline-flex items-center px-gr-md py-gr-sm bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-opacity-90 transition-colors duration-200">
                    <i data-lucide="filter" class="w-5 h-5 mr-gr-xs"></i>
                    Apply Filters
                </button>
                <a href="<?php echo e(route('staff.facilities.index')); ?>" class="inline-flex items-center px-gr-md py-gr-sm bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors duration-200">
                    <i data-lucide="x" class="w-5 h-5 mr-gr-xs"></i>
                    Clear
                </a>
            </div>
        </form>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gr-lg">
        <?php $__empty_1 = true; $__currentLoopData = $facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                
                <?php if($facility->image_path): ?>
                    <img src="<?php echo e(Storage::url($facility->image_path)); ?>" alt="<?php echo e($facility->name); ?>" 
                        class="w-full h-48 object-cover">
                <?php else: ?>
                    <div class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                        <i data-lucide="building-2" class="w-16 h-16 text-gray-400"></i>
                    </div>
                <?php endif; ?>

                
                <div class="p-gr-lg">
                    <div class="flex items-start justify-between mb-gr-sm">
                        <div class="flex-1">
                            <h3 class="text-h4 font-bold text-lgu-headline mb-1"><?php echo e($facility->name); ?></h3>
                            <p class="text-small text-gray-600"><?php echo e($facility->city_name); ?></p>
                        </div>
                        <?php if($facility->is_available): ?>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i>
                                Available
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                <i data-lucide="x-circle" class="w-3 h-3 mr-1"></i>
                                Unavailable
                            </span>
                        <?php endif; ?>
                    </div>

                    <p class="text-small text-lgu-paragraph mb-gr-md line-clamp-2">
                        <?php echo e($facility->description ?? 'No description available.'); ?>

                    </p>

                    <div class="grid grid-cols-2 gap-gr-sm mb-gr-md text-small">
                        <div class="flex items-center text-gray-600">
                            <i data-lucide="users" class="w-4 h-4 mr-1"></i>
                            <span><?php echo e(number_format($facility->capacity)); ?> pax</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i data-lucide="tag" class="w-4 h-4 mr-1"></i>
                            <span>₱<?php echo e(number_format($facility->per_person_rate ?? 0, 2)); ?>/person</span>
                        </div>
                    </div>

                    <a href="<?php echo e(route('staff.facilities.show', $facility->facility_id)); ?>" 
                        class="block w-full text-center px-gr-md py-gr-sm bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-opacity-90 transition-colors duration-200">
                        View Details
                    </a>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <i data-lucide="inbox" class="w-16 h-16 text-gray-300 mx-auto mb-gr-md"></i>
                    <p class="text-body font-semibold text-gray-600 mb-gr-xs">No facilities found</p>
                    <p class="text-small text-gray-500">Try adjusting your search or filters</p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    
    <?php if($facilities->hasPages()): ?>
        <div class="mt-gr-lg">
            <?php echo e($facilities->links()); ?>

        </div>
    <?php endif; ?>
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


<?php echo $__env->make('layouts.staff', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\LGU-PUBLIC-FACILITIES-RESERVATION-SYSTEM\resources\views/staff/facilities/index.blade.php ENDPATH**/ ?>