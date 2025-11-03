<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - LGU</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    @php
        // Check if Vite assets manifest exists
        $hasVite = file_exists(public_path('build/manifest.json'));
    @endphp

    @if ($hasVite)
        {{-- Load Vite assets for development/production --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        {{-- Fallback to CDN if Vite assets not built (Recommended for quick testing) --}}
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            // Tailwind Configuration for CDN
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            'lgu-bg': '#f2f7f5',
                            'lgu-headline': '#00473e',
                            'lgu-paragraph': '#475d5b',
                            'lgu-button': '#faae2b',
                            'lgu-button-text': '#00473e',
                            'lgu-stroke': '#00332c',
                            'lgu-main': '#f2f7f5',
                            'lgu-highlight': '#faae2b',
                            'lgu-secondary': '#ffa8ba',
                            'lgu-tertiary': '#fa5246'
                        }
                    }
                }
            }
        </script>
    @endif

    {{-- Yield for custom head content (e.g., specific CSS or external scripts) --}}
    @stack('head')

</head>
<body>

    {{-- Include the Admin Sidebar --}}
    @include('partials.sidebar')

    {{-- Main Content Wrapper --}}
    <div class="lg:ml-64 min-h-screen flex flex-col bg-lgu-bg transition-all duration-300 ease-in-out">

        {{-- Main Header/Navbar --}}
        <header class="bg-white shadow-md p-4 lg:p-6 sticky top-0 z-30">
            <div class="flex justify-between items-center">

                {{-- Mobile Sidebar Toggle Button (Only appears on small screens) --}}
                <button id="mobile-sidebar-toggle" class="lg:hidden p-2 text-lgu-headline rounded-lg hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                {{-- Search Bar --}}
                <div class="flex-grow max-w-lg mx-4 hidden sm:block">
                    <div class="relative">
                        <input type="text" placeholder="Search..."
                               class="w-full py-2 pl-10 pr-4 border border-gray-300 rounded-lg focus:outline-none focus:border-lgu-highlight transition duration-150 ease-in-out">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                {{-- User Actions / Notifications --}}
                <div class="flex items-center space-x-4">
                    {{-- Notifications Dropdown/Icon --}}
                    <div class="relative">
                        <button class="p-2 text-lgu-headline rounded-full hover:bg-gray-100 transition duration-150">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            {{-- Notification Badge (Retaining original structure) --}}
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 transform translate-x-1/2 -translate-y-1/2 bg-lgu-tertiary rounded-full">3</span>
                        </button>
                    </div>

                    {{-- User Profile / Admin Name (Placeholder) --}}
                    <div class="hidden sm:flex items-center space-x-2">
                        <div class="text-right">
                            <p class="text-sm font-semibold text-lgu-headline">Administrator</p>
                            <p class="text-xs text-gray-500">Super Admin</p>
                        </div>
                        <div class="w-10 h-10 bg-lgu-button rounded-full flex items-center justify-center text-lgu-button-text font-bold">AD</div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Main Content Area --}}
        <main class="flex-grow p-4 lg:p-6">
            {{-- Content is injected here --}}
            @yield('content')
        </main>

        {{-- Optional Footer (Add if needed) --}}
        {{-- <footer>...</footer> --}}

    </div>

    {{-- Load main app JS if Vite is used (Must be at the end of body for performance) --}}
    @if ($hasVite)
        {{-- The app.js is already included in the head, but this line is often kept for explicit ordering/stacking --}}
        {{-- @vite('resources/js/app.js') --}}
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- Sidebar Toggle Logic (Works in conjunction with partials/sidebar.blade.php) ---
            const mobileToggle = document.getElementById('mobile-sidebar-toggle');
            const sidebar = document.getElementById('admin-sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay'); // From partials/sidebar.blade.php

            if (mobileToggle && sidebar && sidebarOverlay) {
                mobileToggle.addEventListener('click', function() {
                    // Visual feedback on click
                    this.classList.add('scale-95');
                    setTimeout(() => {
                        this.classList.remove('scale-95');
                    }, 100);

                    // Toggle sidebar visibility
                    sidebar.classList.toggle('-translate-x-full');
                    sidebarOverlay.classList.toggle('hidden');
                });
            }

            // --- Search Input Enhancement ---
            const searchInput = document.querySelector('input[placeholder="Search..."]');
            if (searchInput) {
                const searchContainer = searchInput.parentElement;

                // Focus/Blur Ring Effect for better UX
                searchInput.addEventListener('focus', function() {
                    searchContainer.classList.add('ring-2', 'ring-lgu-highlight');
                });

                searchInput.addEventListener('blur', function() {
                    searchContainer.classList.remove('ring-2', 'ring-lgu-highlight');
                });
            }
        });
    </script>

    {{-- Yield for page-specific JavaScript --}}
    @stack('scripts')

</body>
</html>