

<?php $__env->startSection('title', 'Pricing Information'); ?>
<?php $__env->startSection('page-title', 'Pricing Information'); ?>
<?php $__env->startSection('page-subtitle', 'View facility pricing and rates'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="container mx-auto px-gr-md py-gr-lg">
    
    <div class="mb-gr-lg">
        <h1 class="text-h1 font-bold text-lgu-headline mb-gr-xs">Pricing Information</h1>
        <p class="text-body text-lgu-paragraph">View facility rental rates and pricing</p>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-gr-lg mb-gr-lg">
        <form method="GET" action="<?php echo e(route('staff.pricing.index')); ?>" class="space-y-gr-md">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-gr-md">
                
                <div>
                    <label for="search" class="block text-small font-semibold text-lgu-headline mb-gr-xs">Search Facility</label>
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
            </div>

            <div class="flex items-center gap-gr-sm">
                <button type="submit" class="inline-flex items-center px-gr-md py-gr-sm bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-opacity-90 transition-colors duration-200">
                    <i data-lucide="filter" class="w-5 h-5 mr-gr-xs"></i>
                    Apply Filters
                </button>
                <a href="<?php echo e(route('staff.pricing.index')); ?>" class="inline-flex items-center px-gr-md py-gr-sm bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors duration-200">
                    <i data-lucide="x" class="w-5 h-5 mr-gr-xs"></i>
                    Clear
                </a>
            </div>
        </form>
    </div>

    
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-lgu-headline text-white">
                        <th class="px-gr-md py-gr-sm text-left text-small font-semibold uppercase tracking-wider">Facility</th>
                        <th class="px-gr-md py-gr-sm text-left text-small font-semibold uppercase tracking-wider">City</th>
                        <th class="px-gr-md py-gr-sm text-left text-small font-semibold uppercase tracking-wider">Rate Per Person</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php $__empty_1 = true; $__currentLoopData = $facilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-gr-md py-gr-sm">
                                <div>
                                    <p class="font-semibold text-lgu-headline"><?php echo e($facility->name); ?></p>
                                </div>
                            </td>
                            <td class="px-gr-md py-gr-sm">
                                <span class="text-body text-lgu-paragraph"><?php echo e($facility->city_name ?? 'N/A'); ?></span>
                            </td>
                            <td class="px-gr-md py-gr-sm">
                                <p class="font-semibold text-lgu-headline">₱<?php echo e(number_format($facility->per_person_rate ?? 0, 2)); ?></p>
                                <p class="text-xs text-gray-500">per person</p>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="px-gr-md py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i data-lucide="inbox" class="w-16 h-16 text-gray-300 mb-gr-md"></i>
                                    <p class="text-body font-semibold text-gray-600 mb-gr-xs">No pricing information found</p>
                                    <p class="text-small text-gray-500">Try adjusting your search or filters</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
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


<?php echo $__env->make('layouts.staff', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\xampp\htdocs\LGU-PUBLIC-FACILITIES-RESERVATION-SYSTEM\resources\views/staff/pricing/index.blade.php ENDPATH**/ ?>