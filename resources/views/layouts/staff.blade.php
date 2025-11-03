<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Staff Portal - LGU</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Tailwind Configuration (Kept as inline script for simplicity) --}}
    <script>
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

    {{-- Vite Assets Integration (For production/development) --}}
    @if (file_exists(public_path('build/manifest.json')))
        @vite('resources/css/app.css')
    @endif

    {{-- Yield for custom head content --}}
    @stack('head')

</head>
<body>

    {{-- Include the Staff Sidebar --}}
    @include('partials.staff-sidebar')

    {{-- Main Content Wrapper (Adjusted margin for sidebar) --}}
    <div class="lg:ml-64 min-h-screen flex flex-col bg-lgu-bg transition-all duration-300 ease-in-out">

        {{-- Main Header/Navbar (Staff version) --}}
        <header class="bg-white shadow-md p-4 lg:p-6 sticky top-0 z-30">
            <div class="flex justify-between items-center">

                {{-- Mobile Sidebar Toggle (The actual toggle button is in staff-sidebar.blade.php, but the space is here) --}}
                <div class="lg:hidden w-10 h-10">
                    {{-- Space for staff-sidebar-toggle button --}}
                </div>

                {{-- Search Bar (Central feature for Staff Portal) --}}
                <div class="flex-grow max-w-xl mx-4">
                    <div class="relative">
                        <input type="text" id="staffSearchInput" placeholder="Search document IDs, usernames, or templates..."
                               class="w-full py-2 pl-10 pr-4 border border-gray-300 rounded-lg focus:outline-none focus:border-lgu-highlight transition duration-150 ease-in-out">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>

                        {{-- Search Suggestions/Results Dropdown --}}
                        <div id="searchSuggestions" class="absolute w-full mt-2 bg-white border border-gray-200 rounded-lg shadow-xl z-20 hidden">
                            {{-- Placeholder for search results --}}
                            <div class="p-3 text-sm text-gray-500">
                                Start typing to see suggestions...
                            </div>
                        </div>
                    </div>
                </div>

                {{-- User Actions / Notifications --}}
                <div class="flex items-center space-x-4">
                    {{-- Quick Action Button (e.g., Quick Verify) --}}
                    <button class="hidden sm:flex items-center bg-lgu-button text-lgu-button-text px-3 py-1.5 rounded-lg font-medium hover:bg-lgu-highlight/80 transition duration-150">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.27a.999.999 0 00-.022-1.402A9.998 9.998 0 0012 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10a9.998 9.998 0 00-4.378-7.72z"></path>
                        </svg>
                        Quick Verify
                    </button>
                    {{-- Staff Profile Placeholder --}}
                    <div class="flex items-center space-x-2">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-semibold text-lgu-headline">Staff</p>
                            <p class="text-xs text-gray-500">Verification Team</p>
                        </div>
                        <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center text-white font-bold">SM</div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Main Content Area --}}
        <main class="flex-grow p-4 lg:p-6">
            {{-- Content is injected here --}}
            @yield('content')
        </main>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('staffSearchInput');
            const searchSuggestions = document.getElementById('searchSuggestions');
            const sidebarToggle = document.getElementById('staff-sidebar-toggle'); // Located in staff-sidebar.blade.php

            // --- Mobile Sidebar Toggle Enhancement (Visual feedback) ---
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    // Add visual feedback
                    this.classList.add('scale-95');
                    setTimeout(() => {
                        this.classList.remove('scale-95');
                    }, 100);
                });
            }

            // --- Search Functionality Logic ---
            if (searchInput && searchSuggestions) {

                // Show suggestions on focus (simulated for refactor compliance)
                searchInput.addEventListener('focus', function() {
                    searchSuggestions.classList.remove('hidden');
                });
                
                // Enhanced Search Functionality (Enter Key)
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault(); // Prevent default form submission if any
                        const searchTerm = this.value.trim();
                        if (searchTerm) {
                            // 1. Visual Feedback
                            this.style.backgroundColor = '#f0fdf4';
                            searchSuggestions.classList.add('hidden'); // Hide suggestions on search

                            // 2. Perform Search/Show Alert (Retaining original logic)
                            setTimeout(() => {
                                this.style.backgroundColor = 'white';
                                // Retaining the original alert message as requested (no code removal)
                                alert(`🔍 Searching for: \"${searchTerm}\"\n\nSearch functionality is coming soon! This will search through:\n• Pending verifications\n• Document templates\n• Staff records\n• Verification history`);
                            }, 200);
                        }
                    }
                });

                // ESC key to close suggestions
                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        searchSuggestions.classList.add('hidden');
                        this.blur(); // Remove focus
                    }
                });
            }

            // Click outside to close suggestions
            document.addEventListener('click', function(e) {
                if (searchSuggestions && searchInput && 
                    !searchInput.contains(e.target) && 
                    !searchSuggestions.contains(e.target)) {
                    searchSuggestions.classList.add('hidden');
                }
            });
        });
    </script>

    {{-- Yield for page-specific JavaScript --}}
    @stack('scripts')

</body>
</html>