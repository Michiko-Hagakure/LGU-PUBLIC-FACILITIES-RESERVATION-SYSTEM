<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <title><?php echo $__env->yieldContent('title', 'LGU1 Public Facilities'); ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo e(asset('assets/images/logo.png')); ?>">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="<?php echo e(asset('manifest.json')); ?>">
    <meta name="theme-color" content="#00473e">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="LGU1 PFRS">
    <link rel="apple-touch-icon" href="<?php echo e(asset('assets/images/logo.png')); ?>">
    
    <!-- Poppins Font (PROJECT_DESIGN_RULES.md requirement) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <!-- Alpine.js -->
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.14.3/cdn.min.js"></script>
    
    <!-- ApexCharts for dashboard graphs (per ARCHITECTURE.md) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.45.0/apexcharts.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.14.5/sweetalert2.all.min.js"></script>
    
    <!-- Lucide Icons (PROJECT_DESIGN_RULES.md requirement) -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Sidebar Link Styles -->
    <style>
        #admin-sidebar .sidebar-link,
        #staff-sidebar .sidebar-link,
        #citizen-sidebar .sidebar-link,
        #treasurer-sidebar .sidebar-link,
        #cbd-sidebar .sidebar-link {
            color: #9CA3AF !important;
            text-decoration: none !important;
        }
        
        #admin-sidebar .sidebar-link:hover,
        #staff-sidebar .sidebar-link:hover,
        #citizen-sidebar .sidebar-link:hover,
        #treasurer-sidebar .sidebar-link:hover,
        #cbd-sidebar .sidebar-link:hover {
            color: #FFFFFF !important;
            background-color: #00332c !important;
        }
        
        #admin-sidebar .sidebar-link.active,
        #staff-sidebar .sidebar-link.active,
        #citizen-sidebar .sidebar-link.active,
        #treasurer-sidebar .sidebar-link.active,
        #cbd-sidebar .sidebar-link.active {
            color: #faae2b !important;
            background-color: #00332c !important;
            border-left: 3px solid #faae2b !important;
        }
    </style>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="h-full">
    <?php echo $__env->make('components.offline-indicator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="min-h-full">
        <?php echo $__env->yieldContent('content'); ?>
    </div>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>

    <!-- Initialize Lucide Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>

    <!-- 2-MINUTE SESSION TIMEOUT (CRITICAL FOR DEFENSE - PROJECT_DESIGN_RULES.md) -->
    <!-- Silent logout after 2 minutes of inactivity - no warnings, no modals -->
    <!-- Only runs when user is authenticated (custom session check) -->
    <?php if(session('user_id')): ?>
    <script>
        (function() {
            // Extra safety: Don't run on auth pages (login, register, password reset)
            const currentPath = window.location.pathname;
            if (currentPath.includes('/login') || currentPath.includes('/register') || currentPath.includes('/password')) {
                return; // Exit immediately
            }

            let sessionTimeout;
            const SESSION_DURATION = <?php echo e(config('session.lifetime')); ?> * 60 * 1000; // Session timeout in milliseconds

            function resetSessionTimer() {
                clearTimeout(sessionTimeout);
                
                // Silent logout after session timeout
                sessionTimeout = setTimeout(() => {
                    // Create a form and submit POST request to logout
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/logout';
                    
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (csrfToken) {
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = csrfToken.content;
                        form.appendChild(csrfInput);
                    }
                    
                    document.body.appendChild(form);
                    form.submit();
                }, SESSION_DURATION);
            }

            ['click', 'mousemove', 'keypress', 'scroll', 'touchstart'].forEach(event => {
                document.addEventListener(event, resetSessionTimer);
            });

            document.addEventListener('DOMContentLoaded', resetSessionTimer);
        })();
    </script>
    <?php endif; ?>

    <!-- Offline Support: IndexedDB Cache + Write Queue -->
    <script src="<?php echo e(asset('js/offline-db.js')); ?>"></script>
    <script src="<?php echo e(asset('js/offline-queue.js')); ?>"></script>

    <!-- Service Worker Registration -->
    <script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('/sw.js', { scope: '/' })
                .then(function(registration) {
                    console.log('[PWA] Service Worker registered, scope:', registration.scope);

                    // Check for updates periodically
                    setInterval(function() {
                        registration.update();
                    }, 60 * 60 * 1000); // Check every hour

                    // Handle SW updates
                    registration.addEventListener('updatefound', function() {
                        var newWorker = registration.installing;
                        newWorker.addEventListener('statechange', function() {
                            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                console.log('[PWA] New version available');
                                newWorker.postMessage({ type: 'SKIP_WAITING' });
                            }
                        });
                    });
                })
                .catch(function(error) {
                    console.warn('[PWA] Service Worker registration failed:', error);
                });
        });
    }
    </script>
</body>
</html>

<?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/layouts/master.blade.php ENDPATH**/ ?>