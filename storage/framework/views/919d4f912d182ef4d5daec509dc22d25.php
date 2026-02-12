<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'LGU1 - Authentication'); ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo e(asset('assets/images/logo.png')); ?>">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="<?php echo e(asset('manifest.json')); ?>">
    <meta name="theme-color" content="#00473e">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="LGU1 PFRS">
    <link rel="apple-touch-icon" href="<?php echo e(asset('assets/images/logo.png')); ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Fonts (PROJECT_DESIGN_RULES.md) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Merriweather:wght@700&display=swap" rel="stylesheet">
    
    <!-- Vite Assets (CSS + Alpine.js) -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <!-- Inline fallback: replicate Bootstrap + Bootstrap Icons when CDN unavailable (offline-first) -->
    <style id="bootstrap-fallback-css">
        /* === Bootstrap Reset & Base === */
        *, *::before, *::after { box-sizing: border-box; }
        body { margin: 0; font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-size: 1rem; line-height: 1.5; color: #212529; background-color: #fff; -webkit-font-smoothing: antialiased; }
        a { color: #0d6efd; text-decoration: underline; }
        img { vertical-align: middle; max-width: 100%; }
        h1, h2, h3, h4, h5, h6 { margin-top: 0; margin-bottom: 0.5rem; font-weight: 500; line-height: 1.2; }
        p { margin-top: 0; margin-bottom: 1rem; }
        label { display: inline-block; margin-bottom: 0.5rem; }
        button, input, textarea { margin: 0; font-family: inherit; font-size: inherit; line-height: inherit; }
        button { cursor: pointer; }

        /* === Bootstrap Grid === */
        .container { width: 100%; padding-right: 0.75rem; padding-left: 0.75rem; margin-right: auto; margin-left: auto; }
        @media (min-width: 576px) { .container { max-width: 540px; } }
        @media (min-width: 768px) { .container { max-width: 720px; } }
        @media (min-width: 992px) { .container { max-width: 960px; } }
        @media (min-width: 1200px) { .container { max-width: 1140px; } }
        .row { display: flex; flex-wrap: wrap; margin-right: -0.75rem; margin-left: -0.75rem; }
        .row > * { flex-shrink: 0; width: 100%; max-width: 100%; padding-right: 0.75rem; padding-left: 0.75rem; }
        @media (min-width: 992px) {
            .col-lg-6 { flex: 0 0 auto; width: 50%; }
            .col-lg-5 { flex: 0 0 auto; width: 41.666667%; }
            .gx-lg-5 > * { padding-right: 1.5rem; padding-left: 1.5rem; }
            .text-lg-start { text-align: left !important; }
            .mb-lg-0 { margin-bottom: 0 !important; }
        }

        /* === Bootstrap Utilities === */
        .text-center { text-align: center; }
        .text-muted { color: #6c757d !important; }
        .fw-bold { font-weight: 700 !important; }
        .mb-0 { margin-bottom: 0 !important; }
        .mb-1 { margin-bottom: 0.25rem !important; }
        .mb-2 { margin-bottom: 0.5rem !important; }
        .mb-3 { margin-bottom: 1rem !important; }
        .mb-4 { margin-bottom: 1.5rem !important; }
        .mb-5 { margin-bottom: 3rem !important; }
        .mt-2 { margin-top: 0.5rem !important; }
        .mt-3 { margin-top: 1rem !important; }
        .my-5 { margin-top: 3rem !important; margin-bottom: 3rem !important; }
        .px-4 { padding-left: 1.5rem !important; padding-right: 1.5rem !important; }
        .py-5 { padding-top: 3rem !important; padding-bottom: 3rem !important; }
        .p-8 { padding: 2rem !important; }
        @media (min-width: 768px) { .px-md-5 { padding-left: 3rem !important; padding-right: 3rem !important; } }
        .w-100 { width: 100% !important; }
        .d-block { display: block !important; }
        .position-absolute { position: absolute !important; }
        .position-relative { position: relative !important; }
        .rounded-circle { border-radius: 50% !important; }
        .overflow-hidden { overflow: hidden !important; }
        .align-items-center { align-items: center !important; }
        .display-5 { font-size: calc(1.425rem + 2.1vw); font-weight: 300; line-height: 1.2; }
        .ls-tight { letter-spacing: -0.02em; }

        /* === Bootstrap Card === */
        .card { position: relative; display: flex; flex-direction: column; min-width: 0; word-wrap: break-word; background-color: #fff; background-clip: border-box; border: 1px solid rgba(0,0,0,.125); border-radius: 0.75rem; box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15); }
        .card-body { flex: 1 1 auto; padding: 1.5rem; }
        @media (min-width: 768px) { .card-body.px-md-5 { padding-left: 3rem !important; padding-right: 3rem !important; } }

        /* === Bootstrap Forms === */
        .form-label { margin-bottom: 0.5rem; font-size: 0.875rem; font-weight: 600; color: #00473e; }
        .form-control { display: block; width: 100%; padding: 0.625rem 0.75rem; font-size: 1rem; font-weight: 400; line-height: 1.5; color: #212529; background-color: #fff; border: 2px solid #e9ecef; border-radius: 0.375rem; transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out; appearance: none; outline: none; }
        .form-control:focus { border-color: #faae2b; box-shadow: 0 0 0 0.2rem rgba(250,174,43,0.2); }
        .form-control::placeholder { color: #6c757d; opacity: 1; }
        .input-group { position: relative; display: flex; flex-wrap: wrap; align-items: stretch; width: 100%; }
        .input-group > .form-control { position: relative; flex: 1 1 auto; width: 1%; min-width: 0; }
        .input-group > .form-control:not(:first-child) { border-top-left-radius: 0; border-bottom-left-radius: 0; }
        .input-group-text { display: flex; align-items: center; padding: 0.375rem 0.75rem; font-size: 1rem; font-weight: 400; line-height: 1.5; color: #00473e; text-align: center; white-space: nowrap; background-color: #f2f7f5; border: 2px solid #e9ecef; border-right: none; border-radius: 0.375rem 0 0 0.375rem; }

        /* === Bootstrap Buttons === */
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; font-weight: 400; line-height: 1.5; text-align: center; text-decoration: none; vertical-align: middle; cursor: pointer; user-select: none; border: 1px solid transparent; padding: 0.625rem 1.25rem; font-size: 1rem; border-radius: 0.375rem; transition: all 0.15s ease-in-out; }
        .btn-outline-secondary { color: #6c757d; border-color: #6c757d; background-color: transparent; }
        .btn-outline-secondary:hover { color: #fff; background-color: #6c757d; }
        .btn-outline-warning { color: #faae2b; border-color: #faae2b; background-color: transparent; }
        .btn-outline-warning:hover { color: #000; background-color: #faae2b; }

        /* === Bootstrap Alert === */
        .alert { position: relative; padding: 1rem; margin-bottom: 1rem; border: 1px solid transparent; border-radius: 0.375rem; }
        .alert-danger { color: #842029; background-color: #f8d7da; border-color: #f5c2c7; }
        .alert-warning { color: #664d03; background-color: #fff3cd; border-color: #ffecb5; }
        .btn-close { box-sizing: content-box; width: 1em; height: 1em; padding: 0.25em; color: #000; background: transparent url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23000'%3e%3cpath d='M.293.293a1 1 0 0 1 1.414 0L8 6.586 14.293.293a1 1 0 1 1 1.414 1.414L9.414 8l6.293 6.293a1 1 0 0 1-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 0 1-1.414-1.414L6.586 8 .293 1.707a1 1 0 0 1 0-1.414z'/%3e%3c/svg%3e") center/1em auto no-repeat; border: 0; border-radius: 0.375rem; opacity: .5; cursor: pointer; position: absolute; top: 0.75rem; right: 0.75rem; }

        /* === Bootstrap Icons Fallback (inline SVG via CSS) === */
        .bi { display: inline-block; width: 1em; height: 1em; vertical-align: -0.125em; }
        .bi-envelope::before { content: "✉"; font-style: normal; }
        .bi-lock::before { content: "🔒"; font-style: normal; font-size: 0.85em; }
        .bi-eye::before { content: "👁"; font-style: normal; font-size: 0.85em; }
        .bi-eye-slash::before { content: "🚫"; font-style: normal; font-size: 0.85em; }
        .bi-box-arrow-in-right::before { content: "→"; font-style: normal; }
        .bi-shield-check::before, .bi-shield-check-fill::before { content: "🛡"; font-style: normal; }
        .bi-shield-lock::before, .bi-shield-lock-fill::before { content: "🔐"; font-style: normal; }
        .bi-check-circle::before { content: "✓"; font-style: normal; }
        .bi-exclamation-triangle::before { content: "⚠"; font-style: normal; }
        .bi-exclamation-circle::before { content: "⚠"; font-style: normal; }
        .bi-arrow-clockwise::before { content: "↻"; font-style: normal; }
        .bi-arrow-left::before { content: "←"; font-style: normal; }
        .bi-key::before, .bi-key-fill::before { content: "🔑"; font-style: normal; font-size: 0.85em; }
        .bi-person-plus::before { content: "👤"; font-style: normal; }
        .bi-clock-history::before { content: "⏱"; font-style: normal; }
        .bi-hourglass-split::before { content: "⏳"; font-style: normal; }
    </style>
    <script>
        // Remove fallback CSS once Bootstrap loads successfully
        document.addEventListener('DOMContentLoaded', function() {
            // Check if Bootstrap CSS actually loaded by testing a Bootstrap-specific computed style
            var testEl = document.createElement('div');
            testEl.className = 'visually-hidden'; // Bootstrap-only class
            testEl.style.position = 'absolute';
            document.body.appendChild(testEl);
            var cs = window.getComputedStyle(testEl);
            // Bootstrap's .visually-hidden sets clip, width:1px, height:1px, etc.
            var loaded = (cs.width === '1px' && cs.height === '1px') || (cs.overflow === 'hidden' && cs.clip !== 'auto');
            document.body.removeChild(testEl);
            if (loaded) {
                var fb = document.getElementById('bootstrap-fallback-css');
                if (fb) fb.remove();
            }
        });
    </script>

    <style>
        :root {
            --bg-color: #f2f7f5;
            --headline: #00473e;
            --paragraph: #475d5b;
            --button: #faae2b;
            --button-text: #00473e;
            --stroke: #00332c;
            --highlight: #faae2b;
            --secondary: #ffa8ba;
            --tertiary: #fa5246;
        }
        .background-radial-gradient {
            background-color: var(--bg-color);
            background-image: radial-gradient(650px circle at 0% 0%,
                rgba(0, 71, 62, 0.3) 15%,
                rgba(0, 71, 62, 0.2) 35%,
                rgba(242, 247, 245, 0.8) 75%,
                rgba(242, 247, 245, 0.9) 80%,
                transparent 100%),
              radial-gradient(1250px circle at 100% 100%,
                rgba(250, 174, 43, 0.2) 15%,
                rgba(255, 168, 186, 0.15) 35%,
                rgba(242, 247, 245, 0.8) 75%,
                rgba(242, 247, 245, 0.9) 80%,
                transparent 100%);
            min-height: 100vh;
            font-family: 'Poppins', Arial, sans-serif;
            overflow: hidden;
        }
        #radius-shape-1 {
            height: 220px;
            width: 220px;
            top: -60px;
            left: -130px;
            background: radial-gradient(var(--highlight), var(--secondary));
            overflow: hidden;
        }
        #radius-shape-2 {
            border-radius: 38% 62% 63% 37% / 70% 33% 67% 30%;
            bottom: -60px;
            right: -110px;
            width: 300px;
            height: 300px;
            background: radial-gradient(var(--highlight), var(--secondary));
            overflow: hidden;
        }
        .bg-glass {
            background-color: hsla(0, 0%, 100%, 0.9) !important;
            backdrop-filter: saturate(200%) blur(25px);
        }
        .hero-text {
            color: var(--headline);
        }
        .hero-text span {
            color: var(--highlight);
        }
        .hero-description {
            color: var(--paragraph);
            opacity: 0.8;
        }
        .form-label {
            color: var(--headline);
            font-family: 'Merriweather', serif;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .form-control {
            transition: all 0.3s ease;
            border: 2px solid #e9ecef !important;
            background-color: white !important;
        }
        .form-control:focus {
            border-color: var(--highlight) !important;
            box-shadow: 0 0 0 0.2rem var(--highlight)33, 0 0 20px rgba(250, 174, 43, 0.1);
            transform: translateY(-2px);
            background-color: white !important;
        }
        /* Validation styles for auth forms */
        form.was-validated .form-control:invalid {
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23ef4444'%3e%3ccircle cx='12' cy='12' r='10'/%3e%3cpath fill='white' d='M12 14a1 1 0 0 1-1-1V8a1 1 0 1 1 2 0v5a1 1 0 0 1-1 1zm0 4a1 1 0 1 1 0-2 1 1 0 0 1 0 2z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 20px 20px;
            padding-right: 40px;
        }
        form.was-validated .form-control:valid {
            border-color: #22c55e !important;
        }
        .input-group-text {
            background: var(--bg-color);
            color: var(--headline);
            border: none;
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--button) 0%, var(--highlight) 100%) !important;
            color: var(--button-text) !important;
            border: none !important;
            font-weight: 700 !important;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(250, 174, 43, 0.3) !important;
            transition: all 0.3s ease;
            font-family: 'Merriweather', serif;
            position: relative;
            overflow: hidden;
            border-radius: 0.375rem !important;
            padding: 0.75rem 1.5rem !important;
            text-align: center !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
        }
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        .btn-primary:hover::before {
            left: 100%;
        }
        .btn-primary:hover, .btn-primary:focus {
            background: linear-gradient(135deg, var(--highlight) 0%, var(--secondary) 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(250, 174, 43, 0.4);
            transform: translateY(-2px);
        }
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.5rem 0 0.5rem 0;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1.5px solid #e0e0e0;
        }
        .divider:not(:empty)::before {
            margin-right: .75em;
        }
        .divider:not(:empty)::after {
            margin-left: .75em;
        }
        .register-link {
            color: var(--headline);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }
        .register-link:hover {
            color: var(--tertiary);
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            .hero-text {
                font-size: 2rem !important;
                text-align: center;
            }
            .hero-description {
                text-align: center;
            }
            #radius-shape-1, #radius-shape-2 {
                display: none;
            }
        }
    </style>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="antialiased">
    <?php echo $__env->make('components.offline-indicator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <section class="background-radial-gradient overflow-hidden">
        <div class="container px-4 py-5 px-md-5 text-center text-lg-start my-5">
            <div class="row gx-lg-5 align-items-center mb-5">
                <div class="col-lg-6 mb-5 mb-lg-0" style="z-index: 10">
                    <h1 class="my-5 display-5 fw-bold ls-tight hero-text">
                        Local Government Unit 1 <br />
                        <span><?php echo $__env->yieldContent('hero-title', 'Authentication Portal'); ?></span>
                    </h1>
                    <p class="mb-4 hero-description">
                        <?php echo $__env->yieldContent('hero-description', 'Secure access to LGU1 services and systems. Your gateway to efficient government services and digital infrastructure management.'); ?>
                    </p>
                </div>

                <div class="col-lg-6 mb-5 mb-lg-0 position-relative">
                    <div id="radius-shape-1" class="position-absolute rounded-circle shadow-5-strong"></div>
                    <div id="radius-shape-2" class="position-absolute shadow-5-strong"></div>

                    <div class="card bg-glass">
                        <div class="card-body px-4 py-5 px-md-5">
                            <?php echo $__env->yieldContent('content'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>

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
                })
                .catch(function(error) {
                    console.warn('[PWA] Service Worker registration failed:', error);
                });
        });
    }
    </script>
</body>
</html>

<?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/layouts/auth.blade.php ENDPATH**/ ?>