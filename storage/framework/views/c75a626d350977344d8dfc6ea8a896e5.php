<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LGU Facility Reservation</title>
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('Images/logo.png')); ?>">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="<?php echo e(asset('manifest.json')); ?>">
    <meta name="theme-color" content="#00473e">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="LGU1 PFRS">
    <link rel="apple-touch-icon" href="<?php echo e(asset('assets/images/logo.png')); ?>">
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Offline fallback styles when CDN Tailwind is unavailable -->
    <noscript><style>.offline-hide{display:none}</style></noscript>
    <style id="offline-fallback-css">
        /* Offline fallback: replicate Tailwind utility classes used in this page */
        .ofl-body { font-family: 'Instrument Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; margin: 0; padding: 0; color: #fff; background: #0f172a; overflow-x: hidden; box-sizing: border-box; -webkit-font-smoothing: antialiased; }
        .ofl-body *, .ofl-body *::before, .ofl-body *::after { box-sizing: border-box; }
        .ofl-body a { color: inherit; text-decoration: none; }
        .ofl-body img { max-width: 100%; display: block; }
        .ofl-body button { cursor: pointer; border: none; background: none; color: inherit; }

        /* Nav */
        .ofl-body nav > div:first-child { max-width: 80rem; margin: 0 auto; padding: 0 1.5rem; height: 5rem; display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 110; }
        .ofl-body nav > div:first-child > a:first-child { display: flex; align-items: center; gap: 0.75rem; }
        .ofl-body nav > div:first-child > a:first-child img { height: 2.5rem; width: 2.5rem; object-fit: contain; }
        .ofl-body nav > div:first-child > a:first-child span { font-weight: 700; font-size: 1.5rem; letter-spacing: -0.025em; text-transform: uppercase; }
        .ofl-body nav > div:first-child > a:first-child span span { color: #f97316; }
        /* Nav links */
        .ofl-body nav > div:first-child > div:nth-child(2) { display: flex; gap: 2.5rem; font-size: 10px; font-weight: 900; letter-spacing: 0.25em; }
        .ofl-body nav > div:first-child > div:nth-child(2) a:hover { color: #f97316; }
        /* Nav auth buttons */
        .ofl-body nav > div:first-child > div:nth-child(3) { display: flex; gap: 1.5rem; align-items: center; }
        .ofl-body nav > div:first-child > div:nth-child(3) a:first-child { font-size: 0.875rem; font-weight: 700; text-transform: uppercase; letter-spacing: -0.025em; }
        .ofl-body nav > div:first-child > div:nth-child(3) a:first-child:hover { color: #fb923c; }
        .ofl-body nav > div:first-child > div:nth-child(3) a:last-child { background: #ea580c; color: #fff; padding: 0.75rem 2rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 700; text-transform: uppercase; box-shadow: 0 10px 25px rgba(234,88,12,0.4); }
        .ofl-body nav > div:first-child > div:nth-child(3) a:last-child:hover { background: #c2410c; }
        /* Mobile menu button */
        .ofl-body #menu-btn { display: none; padding: 0.5rem; }
        .ofl-body #menu-btn svg { width: 2rem; height: 2rem; }
        /* Mobile menu */
        .ofl-body #mobile-menu { display: none; }

        /* Hero section */
        .ofl-body #hero { min-height: 100vh; display: flex; align-items: center; padding-top: 8rem; padding-bottom: 5rem; }
        .ofl-body #hero > div { max-width: 80rem; margin: 0 auto; padding: 0 1.5rem; width: 100%; }
        .ofl-body #hero > div > div { max-width: 48rem; }
        .ofl-body #hero > div > div > span:first-child { background: rgba(234,88,12,0.2); color: #fb923c; border: 1px solid rgba(249,115,22,0.3); padding: 0.5rem 1.25rem; border-radius: 9999px; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.2em; margin-bottom: 2rem; display: inline-block; }
        .ofl-body #hero h1 { font-size: clamp(3rem, 7vw, 6rem); font-weight: 900; line-height: 0.95; margin: 0 0 2rem; }
        .ofl-body #hero h1 span { color: #f97316; font-style: italic; }
        .ofl-body #hero h1 br { display: block; }
        .ofl-body #hero p { font-size: 1.125rem; color: rgba(255,255,255,0.7); margin: 0 0 3rem; max-width: 32rem; font-weight: 500; line-height: 1.6; }
        .ofl-body #hero > div > div > div:last-child { display: flex; flex-wrap: wrap; gap: 1.5rem; align-items: center; }
        .ofl-body #hero > div > div > div:last-child > a { background: #fff; color: #0f172a; padding: 1.25rem 3rem; border-radius: 1rem; font-weight: 900; font-size: 1.125rem; box-shadow: 0 25px 50px rgba(0,0,0,0.25); display: inline-block; transition: all 0.3s; }
        .ofl-body #hero > div > div > div:last-child > a:hover { background: #ea580c; color: #fff; transform: translateY(-4px); }
        .ofl-body #hero > div > div > div:last-child > div { display: flex; align-items: center; gap: 1.5rem; padding-left: 2rem; border-left: 2px solid rgba(255,255,255,0.2); }
        .ofl-body #hero > div > div > div:last-child > div > span:first-child { font-size: 3rem; font-weight: 900; color: #f97316; }
        .ofl-body #hero > div > div > div:last-child > div > span:last-child { font-size: 11px; color: rgba(255,255,255,0.5); text-transform: uppercase; font-weight: 900; line-height: 1.3; }

        /* Facilities section */
        .ofl-body #facilities { padding: 8rem 0; background: rgba(15,23,42,0.5); backdrop-filter: blur(24px); border-top: 1px solid rgba(255,255,255,0.05); }
        .ofl-body #facilities > div { max-width: 80rem; margin: 0 auto; padding: 0 1.5rem; }
        .ofl-body #facilities > div > div:first-child { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 4rem; gap: 1.5rem; flex-wrap: wrap; }
        .ofl-body #facilities > div > div:first-child span { color: #f97316; font-weight: 900; letter-spacing: 0.2em; font-size: 0.75rem; text-transform: uppercase; }
        .ofl-body #facilities > div > div:first-child h2 { font-size: clamp(2rem, 4vw, 3rem); font-weight: 900; margin: 0.5rem 0 0; }
        .ofl-body #facilities > div > div:first-child > p { color: rgba(255,255,255,0.5); font-size: 0.875rem; margin: 0; }
        /* Facility cards grid */
        .ofl-body #facilities > div > div:last-child { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; }
        .ofl-body .facility-card { position: relative; border-radius: 2.5rem; overflow: hidden; aspect-ratio: 4/5; background: #1e293b; box-shadow: 0 25px 50px rgba(0,0,0,0.25); }
        .ofl-body .facility-card img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.7s; }
        .ofl-body .facility-card > div:nth-child(2) { position: absolute; inset: 0; background: linear-gradient(to top, #000, rgba(0,0,0,0.2) 50%, transparent); opacity: 0.9; }
        .ofl-body .facility-card > div:last-child { position: absolute; bottom: 0; left: 0; padding: 2rem; }
        .ofl-body .facility-card h3 { font-size: 1.5rem; font-weight: 700; margin: 0 0 0.25rem; }
        .ofl-body .facility-card p { color: rgba(255,255,255,0.6); font-size: 0.75rem; margin: 0 0 1rem; text-transform: uppercase; letter-spacing: 0.2em; }
        .ofl-body .facility-card span { font-size: 10px; font-weight: 700; background: #ea580c; padding: 0.25rem 0.75rem; border-radius: 9999px; text-transform: uppercase; letter-spacing: -0.025em; }

        /* Contact section */
        .ofl-body #contact { padding: 8rem 0; background: rgba(0,0,0,0.4); backdrop-filter: blur(48px); border-top: 1px solid rgba(255,255,255,0.05); }
        .ofl-body #contact > div { max-width: 80rem; margin: 0 auto; padding: 0 1.5rem; }
        .ofl-body #contact > div > div { display: grid; grid-template-columns: 1fr 1fr; gap: 5rem; align-items: center; }
        .ofl-body #contact span.text-orange-500 { color: #f97316; }
        .ofl-body #contact h2 { font-size: clamp(2.5rem, 5vw, 3.75rem); font-weight: 900; margin: 1rem 0 2rem; }
        .ofl-body #contact h2 span { color: #f97316; font-style: italic; }
        .ofl-body #contact > div > div > div:first-child > p { color: rgba(255,255,255,0.5); font-size: 1.125rem; margin-bottom: 3rem; max-width: 28rem; line-height: 1.6; }
        .ofl-body #contact > div > div > div:first-child > div { display: flex; flex-direction: column; gap: 1.5rem; }
        .ofl-body #contact > div > div > div:first-child > div > a { display: flex; align-items: center; gap: 1.5rem; }
        .ofl-body #contact > div > div > div:first-child > div > a > div { height: 3.5rem; width: 3.5rem; border-radius: 1rem; display: flex; align-items: center; justify-content: center; }
        .ofl-body #contact > div > div > div:first-child > div > a:first-child > div { background: rgba(37,99,235,0.1); color: #3b82f6; }
        .ofl-body #contact > div > div > div:first-child > div > a:last-child > div { background: rgba(147,51,234,0.1); color: #a855f7; }
        .ofl-body #contact > div > div > div:first-child > div > a > span { font-weight: 700; color: rgba(255,255,255,0.8); }
        /* Contact form */
        .ofl-body #contact > div > div > div:last-child { background: rgba(255,255,255,0.05); padding: 2rem 3rem; border-radius: 3rem; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 25px 50px rgba(0,0,0,0.25); }
        .ofl-body #contactForm { display: flex; flex-direction: column; gap: 1.5rem; }
        .ofl-body #contactForm > div:first-of-type { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        .ofl-body #contactForm label { font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.2em; color: rgba(255,255,255,0.3); margin-left: 0.25rem; display: block; margin-bottom: 0.5rem; }
        .ofl-body #contactForm input, .ofl-body #contactForm textarea { width: 100%; padding: 1rem 1.5rem; border-radius: 1rem; color: #fff; font-family: inherit; font-size: 1rem; }
        .ofl-body #contactForm textarea { resize: none; }
        .ofl-body #submitBtn { width: 100%; background: #ea580c; color: #fff; padding: 1.25rem; border-radius: 1rem; font-weight: 900; font-size: 1.125rem; text-transform: uppercase; letter-spacing: 0.2em; transition: background 0.3s; box-shadow: 0 10px 25px rgba(234,88,12,0.2); border: none; cursor: pointer; }
        .ofl-body #submitBtn:hover { background: #c2410c; }

        /* Mobile responsive */
        @media (max-width: 1024px) {
            .ofl-body nav > div:first-child > div:nth-child(2) { display: none; }
            .ofl-body #menu-btn { display: block !important; }
            .ofl-body #facilities > div > div:last-child { grid-template-columns: 1fr; max-width: 400px; margin: 0 auto; }
            .ofl-body #contact > div > div { grid-template-columns: 1fr; gap: 3rem; }
        }
        @media (max-width: 768px) {
            .ofl-body nav > div:first-child > div:nth-child(3) { display: none; }
            .ofl-body #hero h1 { font-size: 2.5rem; }
            .ofl-body #hero > div > div > div:last-child > div { border-left: none; padding-left: 0; }
            .ofl-body #contactForm > div:first-of-type { grid-template-columns: 1fr; }
        }
    </style>
    <script>
        // Remove fallback CSS once Tailwind loads successfully
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof tailwind !== 'undefined' || document.querySelector('style[data-tailwind]')) {
                var fb = document.getElementById('offline-fallback-css');
                if (fb) fb.remove();
                document.body.classList.remove('ofl-body');
            } else {
                document.body.classList.add('ofl-body');
            }
        });
    </script>
    
    <style>
        html { scroll-behavior: smooth; }
        .page-bg {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-image: url("<?php echo e(asset('Images/BG_Facilities.png')); ?>");
            background-size: cover;
            background-position: center;
            z-index: -1;
            filter: brightness(0.6); 
            transform: scale(1.05);
        }
        nav {
            position: fixed;
            top: 0; width: 100%; z-index: 100;
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .text-backdrop {
            background: radial-gradient(circle at left, rgba(0,0,0,0.6) 0%, transparent 85%);
            padding: 2rem;
            border-radius: 2.5rem;
        }
        .facility-card:hover img { transform: scale(1.1); }
        .contact-input {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }
        .contact-input:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: #f97316;
            outline: none;
            box-shadow: 0 0 20px rgba(249, 115, 22, 0.2);
        }
        #mobile-menu {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0; pointer-events: none; transform: translateY(-10px);
        }
        #mobile-menu.active { opacity: 1; pointer-events: auto; transform: translateY(0); }
    </style>
</head>
<body class="antialiased font-['Instrument_Sans'] text-white bg-slate-950 overflow-x-hidden ofl-body">

    <div id="mainBg" class="page-bg"></div>

    <nav>
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center relative z-[110]">
            <a href="/" class="flex items-center gap-3 group">
                <img src="<?php echo e(asset('Images/logo.png')); ?>" alt="Logo" class="h-10 w-10 object-contain drop-shadow-lg group-hover:rotate-12 transition">
                <span class="font-bold text-2xl tracking-tighter uppercase">LGU<span class="text-orange-500">Facility Reservation</span></span>
            </a>
            <div class="hidden lg:flex gap-10 text-[10px] font-black tracking-[0.25em]">
                <a href="#hero" class="hover:text-orange-500 transition">HOME</a>
                <a href="#facilities" class="hover:text-orange-500 transition">FACILITIES</a>
                <a href="#contact" class="hover:text-orange-500 transition">CONTACT US</a>
            </div>
            <div class="hidden md:flex gap-6 items-center">
                <a href="<?php echo e(route('login')); ?>" class="text-sm font-bold hover:text-orange-400 transition uppercase tracking-tighter">Log in</a>
                <a href="<?php echo e(route('register')); ?>" class="bg-orange-600 text-white px-8 py-3 rounded-full text-sm font-bold hover:bg-orange-700 transition shadow-xl shadow-orange-900/40 uppercase">REGISTER</a>
            </div>
            <button id="menu-btn" class="lg:hidden text-white p-2">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path id="menu-path" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </div>
        <div id="mobile-menu" class="lg:hidden absolute top-0 left-0 w-full min-h-screen bg-slate-950/98 backdrop-blur-3xl z-[105] flex flex-col items-center justify-center gap-8 px-8">
            <a href="#hero" class="mobile-link text-2xl font-black tracking-widest uppercase">HOME</a>
            <a href="#facilities" class="mobile-link text-2xl font-black tracking-widest uppercase">FACILITIES</a>
            <a href="#contact" class="mobile-link text-2xl font-black tracking-widest uppercase">CONTACT</a>
            <hr class="w-16 border-white/20">
            <a href="<?php echo e(route('login')); ?>" class="mobile-link text-xl font-bold uppercase">LOG IN</a>
            <a href="<?php echo e(route('register')); ?>" class="w-full max-w-xs bg-orange-600 text-white py-5 rounded-2xl font-black text-center text-lg">REGISTER</a>
        </div>
    </nav>

    <main class="relative z-10">
        <section id="hero" class="min-h-screen flex items-center pt-32 pb-20">
            <div class="max-w-7xl mx-auto px-6 w-full">
                <div class="max-w-3xl text-backdrop mx-auto lg:mx-0 text-center lg:text-left">
                    <span class="bg-orange-600/20 text-orange-400 border border-orange-500/30 px-5 py-2 rounded-full text-[10px] font-black uppercase tracking-[0.2em] mb-8 inline-block backdrop-blur-md">Official LGU Portal</span>
                    <h1 class="text-5xl sm:text-7xl lg:text-[6rem] font-black leading-[1.1] lg:leading-[0.9] mb-8">Booking <br class="hidden sm:block"> spaces <span class="text-orange-500 italic">made simple.</span></h1>
                    <p class="text-lg md:text-xl text-white/70 mb-12 max-w-lg mx-auto lg:mx-0 font-medium">Reserve sports complexes, convention centers, and parks in the city with our fast and transparent digital system.</p>
                    <div class="flex flex-col sm:flex-row gap-6 items-center justify-center lg:justify-start">
                        <a href="#facilities" class="w-full sm:w-auto bg-white text-slate-950 px-12 py-5 rounded-2xl font-black text-lg hover:bg-orange-600 hover:text-white transition-all transform hover:-translate-y-1 shadow-2xl">BROWSE VENUES</a>
                        <div class="flex items-center gap-6 px-8 border-l-0 sm:border-l-2 border-white/20">
                            <span class="text-5xl font-black text-orange-500">ALL DAY</span>
                            <span class="text-[11px] text-white/50 uppercase font-black leading-tight text-left">Instant<br>Online Access</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="facilities" class="py-32 bg-slate-950/50 backdrop-blur-xl border-t border-white/5">
            <div class="max-w-7xl mx-auto px-6">
                <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
                    <div>
                        <span class="text-orange-500 font-black tracking-widest text-xs uppercase">Venues</span>
                        <h2 class="text-4xl md:text-5xl font-black mt-2">Featured Facilities</h2>
                    </div>
                    <p class="text-white/50 max-w-xs text-sm">Top-rated public spaces managed by the LGU for your events.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="facility-card relative group rounded-[2.5rem] overflow-hidden aspect-[4/5] bg-slate-900 shadow-2xl">
                        <img src="<?php echo e(asset('Images/Buena_Park_Caloocan.jpg')); ?>" class="w-full h-full object-cover transition duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent opacity-90 transition duration-500"></div>
                        <div class="absolute bottom-0 left-0 p-8">
                            <h3 class="text-2xl font-bold mb-1">Buena Park</h3>
                            <p class="text-white/60 text-xs mb-4 uppercase tracking-widest">Clubhouse & Events</p>
                            <span class="text-[10px] font-bold bg-orange-600 px-3 py-1 rounded-full uppercase tracking-tighter">Available Now</span>
                        </div>
                    </div>
                    <div class="facility-card relative group rounded-[2.5rem] overflow-hidden aspect-[4/5] bg-slate-900 shadow-2xl">
                        <img src="<?php echo e(asset('Images/Caloocan_Sports_Complex.jpg')); ?>" class="w-full h-full object-cover transition duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent opacity-90 transition duration-500"></div>
                        <div class="absolute bottom-0 left-0 p-8">
                            <h3 class="text-2xl font-bold mb-1">Sports Complex</h3>
                            <p class="text-white/60 text-xs mb-4 uppercase tracking-widest">Indoor Courts</p>
                            <span class="text-[10px] font-bold bg-orange-600 px-3 py-1 rounded-full uppercase tracking-tighter">Available Now</span>
                        </div>
                    </div>
                    <div class="facility-card relative group rounded-[2.5rem] overflow-hidden aspect-[4/5] bg-slate-900 shadow-2xl">
                        <img src="<?php echo e(asset('Images/MICE_Center_QC.jpg')); ?>" class="w-full h-full object-cover transition duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent opacity-90 transition duration-500"></div>
                        <div class="absolute bottom-0 left-0 p-8">
                            <h3 class="text-2xl font-bold mb-1">M.I.C.E. Center</h3>
                            <p class="text-white/60 text-xs mb-4 uppercase tracking-widest">Conventions & Expos</p>
                            <span class="text-[10px] font-bold bg-orange-600 px-3 py-1 rounded-full uppercase tracking-tighter">Available Now</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="contact" class="py-32 bg-black/40 backdrop-blur-3xl border-t border-white/5">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                    <div>
                        <span class="text-orange-500 font-black tracking-widest text-xs uppercase">Support</span>
                        <h2 class="text-5xl md:text-6xl font-black mt-4 mb-8">Get in <span class="text-orange-500 italic">touch.</span></h2>
                        <p class="text-white/50 text-lg mb-12 max-w-md leading-relaxed">Have questions about our facilities? We are here to assist you with your inquiries.</p>
                        <div class="space-y-6">
                            <a href="https://facebook.com" target="_blank" class="flex items-center gap-6 group w-fit">
                                <div class="h-14 w-14 rounded-2xl bg-blue-600/10 flex items-center justify-center text-blue-500 group-hover:bg-blue-600 group-hover:text-white transition-all">
                                    <i data-lucide="facebook"></i>
                                </div>
                                <span class="font-bold text-white/80 group-hover:text-white transition">LGU Official</span>
                            </a>
                            <a href="viber://chat?number=yournumber" class="flex items-center gap-6 group w-fit">
                                <div class="h-14 w-14 rounded-2xl bg-purple-600/10 flex items-center justify-center text-purple-500 group-hover:bg-purple-600 group-hover:text-white transition-all">
                                    <i data-lucide="phone-call"></i>
                                </div>
                                <span class="font-bold text-white/80 group-hover:text-white transition">Viber Support Channel</span>
                            </a>
                        </div>
                    </div>

                    <div class="bg-white/5 p-8 md:p-12 rounded-[3rem] border border-white/10 shadow-2xl">
                        <form id="contactForm" class="space-y-6">
                            <input type="hidden" name="access_key" value="b710b690-34b8-4f0d-8d91-727184e8d8db">
                            <input type="checkbox" name="botcheck" class="hidden" style="display: none;">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-white/30 ml-1">Full Name</label>
                                    <input type="text" name="name" required placeholder="Your Name" class="contact-input w-full px-6 py-4 rounded-2xl text-white">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-white/30 ml-1">Email</label>
                                    <input type="email" name="email" required placeholder="email@example.com" class="contact-input w-full px-6 py-4 rounded-2xl text-white">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-white/30 ml-1">Message</label>
                                <textarea name="message" required rows="4" placeholder="How can we help?" class="contact-input w-full px-6 py-4 rounded-2xl text-white resize-none"></textarea>
                            </div>
                            <button type="submit" id="submitBtn" class="w-full bg-orange-600 hover:bg-orange-700 text-white py-5 rounded-2xl font-black text-lg transition shadow-xl shadow-orange-900/20 uppercase tracking-widest">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        if (typeof lucide !== 'undefined') { lucide.createIcons(); }
        const bg = document.getElementById('mainBg');
        const menuBtn = document.getElementById('menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuPath = document.getElementById('menu-path');

        menuBtn.addEventListener('click', () => {
            const isOpen = mobileMenu.classList.contains('active');
            if (isOpen) {
                mobileMenu.classList.remove('active');
                menuPath.setAttribute('d', 'M4 6h16M4 12h16m-7 6h7');
            } else {
                mobileMenu.classList.add('active');
                menuPath.setAttribute('d', 'M6 18L18 6M6 6l12 12');
            }
        });

        document.querySelectorAll('.mobile-link').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('active');
                menuPath.setAttribute('d', 'M4 6h16M4 12h16m-7 6h7');
            });
        });

        window.addEventListener('mousemove', (e) => {
            if (window.innerWidth > 1024) {
                const x = (e.clientX / window.innerWidth - 0.5) * 20;
                const y = (e.clientY / window.innerHeight - 0.5) * 20;
                bg.style.transform = `scale(1.05) translate(${x}px, ${y}px)`;
            }
        });

        const contactForm = document.getElementById('contactForm');
        const submitBtn = document.getElementById('submitBtn');

        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            submitBtn.disabled = true;
            submitBtn.innerText = "Sending...";

            const formData = new FormData(contactForm);
            const object = Object.fromEntries(formData);
            const json = JSON.stringify(object);

            fetch('https://api.web3forms.com/submit', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: json
            })
            .then(async (response) => {
                let res = await response.json();
                if (response.status == 200) {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Your message has been sent successfully.',
                        icon: 'success',
                        confirmButtonColor: '#ea580c',
                        background: '#0f172a',
                        color: '#ffffff'
                    });
                    contactForm.reset();
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: res.message,
                        icon: 'error',
                        confirmButtonColor: '#ea580c'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Oops!',
                    text: 'Something went wrong. Please try again later.',
                    icon: 'error',
                    confirmButtonColor: '#ea580c'
                });
            })
            .then(function() {
                submitBtn.disabled = false;
                submitBtn.innerText = "Send Message";
            });
        });
    </script>

    <!-- Offline Support -->
    <?php echo $__env->make('components.offline-indicator', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <script src="<?php echo e(asset('js/offline-db.js')); ?>"></script>
    <script src="<?php echo e(asset('js/offline-queue.js')); ?>"></script>
    
    <!-- Service Worker Registration -->
    <script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('/sw.js', { scope: '/' })
                .then(function(registration) {
                    console.log('[PWA] Service Worker registered, scope:', registration.scope);
                    registration.addEventListener('updatefound', function() {
                        var newWorker = registration.installing;
                        newWorker.addEventListener('statechange', function() {
                            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                newWorker.postMessage({ type: 'SKIP_WAITING' });
                            }
                        });
                    });
                })
                .catch(function(error) {
                    console.warn('[PWA] SW registration failed:', error);
                });
        });
    }
    </script>
</body>
</html><?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/welcome.blade.php ENDPATH**/ ?>