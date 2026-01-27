<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>
<?php $__env->startSection('page-subtitle', 'Overview and Statistics'); ?>

<?php $__env->startSection('page-content'); ?>
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Welcome back, <?php echo e(session('user_name', 'Citizen')); ?>!</h1>
            <p class="text-gray-600 mt-1">Manage your facility reservations and profile</p>
        </div>
    </div>
    
    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Active Bookings -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-check text-blue-600"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="m9 16 2 2 4-4"/></svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-2xl font-bold text-gray-900"><?php echo e($activeBookings ?? 0); ?></h2>
                    <p class="text-gray-600">Active Bookings</p>
            </div>
        </div>
    </div>
    
        <!-- Completed -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle text-green-600"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-2xl font-bold text-gray-900"><?php echo e($completedBookings ?? 0); ?></h2>
                    <p class="text-gray-600">Completed</p>
            </div>
        </div>
    </div>
    
        <!-- Total Spent -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 flex items-center justify-center">
                    <span class="text-3xl font-bold text-purple-600">₱</span>
                </div>
                <div class="ml-4">
                    <h2 class="text-2xl font-bold text-gray-900">₱<?php echo e(number_format($totalSpent ?? 0, 2)); ?></h2>
                    <p class="text-gray-600">Total Spent</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Available Facilities -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Available Facilities</h2>
            <p class="text-gray-600 mb-6">Browse and book public facilities easily.</p>
            <a href="<?php echo e(route('citizen.browse-facilities')); ?>" class="inline-flex items-center px-6 py-3 bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-lgu-highlight transition shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/></svg>
                Browse Facilities
            </a>
        </div>

        <!-- Facility Calendar -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Facility Calendar</h2>
            <p class="text-gray-600 mb-6">View available dates and existing reservations.</p>
            <a href="<?php echo e(route('citizen.facility-calendar')); ?>" class="inline-flex items-center px-6 py-3 bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:bg-lgu-highlight transition shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/></svg>
                View Calendar
            </a>
        </div>
    </div>

    <!-- Recent Activity / Upcoming Bookings -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Upcoming Bookings -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900">Upcoming Bookings</h2>
                <a href="<?php echo e(route('citizen.reservations')); ?>" class="text-sm text-lgu-button hover:text-lgu-highlight font-medium">View All</a>
            </div>
            
            <?php if(isset($upcomingBookings) && $upcomingBookings->count() > 0): ?>
                <div class="space-y-3">
                    <?php $__currentLoopData = $upcomingBookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                            <div class="flex items-center">
                                <div class="p-2 bg-lgu-bg rounded-lg mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-lgu-button"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">Facility ID: <?php echo e($booking->facility_id); ?></h4>
                                    <p class="text-xs text-gray-600"><?php echo e(\Carbon\Carbon::parse($booking->start_time)->format('M d, Y • h:i A')); ?></p>
                                </div>
                            </div>
                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-lgu-bg text-lgu-headline">
                                <?php echo e(ucfirst($booking->status)); ?>

                            </span>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-x text-gray-400"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="m14 14-4 4"/><path d="m10 14 4 4"/></svg>
                    </div>
                    <p class="text-gray-500 font-medium">No upcoming bookings</p>
                    <p class="text-sm text-gray-400 mt-1">Book a facility to get started</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pending Payments -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-900">Pending Payments</h2>
                <a href="<?php echo e(route('citizen.payment-slips')); ?>" class="text-sm text-lgu-button hover:text-lgu-highlight font-medium">View All</a>
            </div>
            
            <?php if(isset($pendingPayments) && $pendingPayments->count() > 0): ?>
                <div class="space-y-3">
                    <?php $__currentLoopData = $pendingPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between p-3 border border-yellow-200 bg-yellow-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-2 bg-yellow-100 rounded-lg mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-alert-circle text-yellow-600"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900"><?php echo e($payment->slip_number); ?></h4>
                                    <p class="text-xs text-gray-600">₱<?php echo e(number_format($payment->amount_due, 2)); ?> • Due <?php echo e(\Carbon\Carbon::parse($payment->payment_deadline)->diffForHumans()); ?></p>
                                </div>
                            </div>
                            <a href="<?php echo e(route('citizen.payment-slips.show', $payment->id)); ?>" class="text-yellow-600 hover:text-yellow-800 text-sm font-medium">
                                Pay Now
                            </a>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8">
                    <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle text-gray-400"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/></svg>
                    </div>
                    <p class="text-gray-500 font-medium">No pending payments</p>
                    <p class="text-sm text-gray-400 mt-1">All payments are up to date</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- System Announcements -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-megaphone text-blue-600"><path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">System Announcements</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Welcome to the LGU1 Public Facilities Reservation System!</li>
                        <li>All reservations require advance booking and approval.</li>
                        <li>City residents receive a 30% discount when booking facilities in their area.</li>
                        <li>Senior Citizens, PWDs, and Students receive an additional 20% discount.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Citizen Dashboard Loaded');
    
    // Check if profile completion modal should be shown
    <?php if(session('show_profile_completion')): ?>
        showProfileCompletionModal();
        <?php session()->forget('show_profile_completion'); ?>
    <?php endif; ?>
});

// Birthdate calendar state
let birthdateCalendarDate = new Date();
let selectedBirthdate = null;

function showProfileCompletionModal() {
    Swal.fire({
        title: '<i class="bi bi-person-fill-check text-lgu-button"></i> Complete Your Profile',
        html: `
            <p class="text-gray-600 mb-4">Please provide the following information to complete your registration.</p>
            <form id="profileCompletionForm" class="text-left">
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mobile Number <span class="text-red-500">*</span></label>
                    <input type="tel" id="swal_mobile" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-button focus:border-lgu-button" placeholder="09XX XXX XXXX" required pattern="^09[0-9]{9}$">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Birthdate <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="text" id="swal_birthdate_display" readonly 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg cursor-pointer hover:border-lgu-button focus:ring-2 focus:ring-lgu-button focus:border-lgu-button bg-white" 
                               placeholder="Click to select birthdate">
                        <input type="hidden" id="swal_birthdate" value="">
                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400">
                                <path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gender <span class="text-red-500">*</span></label>
                    <select id="swal_gender" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-button focus:border-lgu-button" required>
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Address <span class="text-red-500">*</span></label>
                    <textarea id="swal_address" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-button focus:border-lgu-button" rows="2" placeholder="House No., Street, Barangay, City" required></textarea>
                </div>
            </form>
        `,
        showCancelButton: false,
        confirmButtonText: 'Save Profile',
        confirmButtonColor: '#faae2b',
        allowOutsideClick: false,
        allowEscapeKey: false,
        customClass: {
            popup: 'rounded-xl',
            title: 'text-lgu-headline',
            confirmButton: 'px-6 py-2 font-semibold rounded-lg'
        },
        didOpen: () => {
            // Add click handler for birthdate field
            document.getElementById('swal_birthdate_display').addEventListener('click', openBirthdateCalendar);
        },
        preConfirm: () => {
            const mobile = document.getElementById('swal_mobile').value;
            const birthdate = document.getElementById('swal_birthdate').value;
            const gender = document.getElementById('swal_gender').value;
            const address = document.getElementById('swal_address').value;
            
            // Validation
            if (!mobile || !birthdate || !gender || !address) {
                Swal.showValidationMessage('Please fill in all required fields');
                return false;
            }
            
            if (!/^09[0-9]{9}$/.test(mobile)) {
                Swal.showValidationMessage('Please enter a valid Philippine mobile number (09XX XXX XXXX)');
                return false;
            }
            
            return { mobile, birthdate, gender, address };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Saving...',
                text: 'Please wait while we update your profile.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Submit to server
            fetch('<?php echo e(route("citizen.complete-profile")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify(result.value)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Profile Complete!',
                        text: 'Your profile has been updated successfully.',
                        confirmButtonColor: '#faae2b'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to update profile. Please try again.',
                        confirmButtonColor: '#faae2b'
                    }).then(() => {
                        showProfileCompletionModal();
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred. Please try again.',
                    confirmButtonColor: '#faae2b'
                }).then(() => {
                    showProfileCompletionModal();
                });
            });
        }
    });
}

function openBirthdateCalendar() {
    // Set initial date to 18 years ago for adult selection
    if (!selectedBirthdate) {
        birthdateCalendarDate = new Date();
        birthdateCalendarDate.setFullYear(birthdateCalendarDate.getFullYear() - 18);
    }
    
    // Generate year options (from 100 years ago to current year)
    const currentYear = new Date().getFullYear();
    const startYear = currentYear - 100;
    let yearOptions = '';
    for (let y = currentYear; y >= startYear; y--) {
        yearOptions += `<option value="${y}">${y}</option>`;
    }
    
    const calendarHtml = `
        <div class="birthdate-calendar-container">
            <div class="flex items-center justify-between mb-4 gap-2">
                <button type="button" id="bdPrevMonth" class="p-2 hover:bg-gray-100 rounded-lg transition flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                </button>
                <div class="flex items-center gap-2">
                    <select id="bdMonthSelect" class="px-2 py-1 border border-gray-300 rounded-lg text-sm font-medium focus:ring-2 focus:ring-lgu-button focus:border-lgu-button cursor-pointer">
                        <option value="0">January</option>
                        <option value="1">February</option>
                        <option value="2">March</option>
                        <option value="3">April</option>
                        <option value="4">May</option>
                        <option value="5">June</option>
                        <option value="6">July</option>
                        <option value="7">August</option>
                        <option value="8">September</option>
                        <option value="9">October</option>
                        <option value="10">November</option>
                        <option value="11">December</option>
                    </select>
                    <select id="bdYearSelect" class="px-2 py-1 border border-gray-300 rounded-lg text-sm font-medium focus:ring-2 focus:ring-lgu-button focus:border-lgu-button cursor-pointer">
                        ${yearOptions}
                    </select>
                </div>
                <button type="button" id="bdNextMonth" class="p-2 hover:bg-gray-100 rounded-lg transition flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                </button>
            </div>
            <div class="grid grid-cols-7 gap-1 mb-2">
                <div class="text-center text-xs font-bold text-gray-500 py-2">Su</div>
                <div class="text-center text-xs font-bold text-gray-500 py-2">Mo</div>
                <div class="text-center text-xs font-bold text-gray-500 py-2">Tu</div>
                <div class="text-center text-xs font-bold text-gray-500 py-2">We</div>
                <div class="text-center text-xs font-bold text-gray-500 py-2">Th</div>
                <div class="text-center text-xs font-bold text-gray-500 py-2">Fr</div>
                <div class="text-center text-xs font-bold text-gray-500 py-2">Sa</div>
            </div>
            <div id="bdCalendarDays" class="grid grid-cols-7 gap-1"></div>
        </div>
    `;
    
    Swal.fire({
        title: 'Select Birthdate',
        html: calendarHtml,
        showCancelButton: true,
        showConfirmButton: false,
        cancelButtonText: 'Cancel',
        cancelButtonColor: '#6b7280',
        allowOutsideClick: false,
        allowEscapeKey: false,
        customClass: {
            popup: 'rounded-xl',
            title: 'text-lgu-headline text-lg'
        },
        didOpen: () => {
            renderBirthdateCalendar();
            
            // Arrow navigation
            document.getElementById('bdPrevMonth').addEventListener('click', () => {
                birthdateCalendarDate.setMonth(birthdateCalendarDate.getMonth() - 1);
                renderBirthdateCalendar();
            });
            document.getElementById('bdNextMonth').addEventListener('click', () => {
                birthdateCalendarDate.setMonth(birthdateCalendarDate.getMonth() + 1);
                renderBirthdateCalendar();
            });
            
            // Dropdown navigation
            document.getElementById('bdMonthSelect').addEventListener('change', (e) => {
                birthdateCalendarDate.setMonth(parseInt(e.target.value));
                renderBirthdateCalendar();
            });
            document.getElementById('bdYearSelect').addEventListener('change', (e) => {
                birthdateCalendarDate.setFullYear(parseInt(e.target.value));
                renderBirthdateCalendar();
            });
        }
    }).then((result) => {
        if (result.dismiss === Swal.DismissReason.cancel) {
            showProfileCompletionModal();
        }
    });
}

function renderBirthdateCalendar() {
    const year = birthdateCalendarDate.getFullYear();
    const month = birthdateCalendarDate.getMonth();
    
    // Update dropdown values to match current calendar date
    document.getElementById('bdMonthSelect').value = month;
    document.getElementById('bdYearSelect').value = year;
    
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    const calendarDays = document.getElementById('bdCalendarDays');
    calendarDays.innerHTML = '';
    
    // Empty cells before first day
    for (let i = 0; i < firstDay; i++) {
        const emptyCell = document.createElement('div');
        calendarDays.appendChild(emptyCell);
    }
    
    // Day cells
    for (let day = 1; day <= daysInMonth; day++) {
        const date = new Date(year, month, day);
        date.setHours(0, 0, 0, 0);
        const dayCell = document.createElement('button');
        dayCell.type = 'button';
        dayCell.textContent = day;
        dayCell.className = 'aspect-square p-2 text-sm rounded-full font-medium transition-all duration-200';
        
        const isFuture = date > today;
        const isSelected = selectedBirthdate && date.getTime() === selectedBirthdate.getTime();
        
        if (isSelected) {
            dayCell.className += ' bg-lgu-button text-white font-bold shadow-lg';
        } else if (isFuture) {
            dayCell.className += ' bg-gray-100 text-gray-300 cursor-not-allowed';
            dayCell.disabled = true;
        } else {
            dayCell.className += ' bg-white hover:bg-lgu-highlight text-gray-700 border border-gray-200 hover:border-lgu-button';
            dayCell.addEventListener('click', () => selectBirthdate(date));
        }
        
        calendarDays.appendChild(dayCell);
    }
}

function selectBirthdate(date) {
    selectedBirthdate = date;
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                       'July', 'August', 'September', 'October', 'November', 'December'];
    
    // Close calendar and reopen profile modal with date filled
    Swal.close();
    
    // Small delay to let modal close
    setTimeout(() => {
        showProfileCompletionModal();
        // Set the values after modal opens
        setTimeout(() => {
            document.getElementById('swal_birthdate').value = `${year}-${month}-${day}`;
            document.getElementById('swal_birthdate_display').value = `${monthNames[date.getMonth()]} ${day}, ${year}`;
        }, 100);
    }, 100);
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.citizen', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/citizen/dashboard.blade.php ENDPATH**/ ?>