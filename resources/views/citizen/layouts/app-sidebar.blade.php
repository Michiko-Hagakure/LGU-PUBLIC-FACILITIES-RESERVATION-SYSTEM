<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LGU1 Citizen Portal')</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    @php($hasVite = file_exists(public_path('build/manifest.json')))\
    @if ($hasVite)
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
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
                            'lgu-red': '#cc3333',
                            'lgu-green': '#33cc33',
                            'lgu-yellow': '#ffc300',
                            // New colors for better contrast and branding
                            'primary': '#00473e', // Deep Teal
                            'secondary': '#faae2b', // Yellow Highlight
                            'accent': '#475d5b', // Muted Gray-Teal
                        }
                    }
                }
            }
        </script>
    @endif
    
    @stack('styles')
</head>
<body class="bg-lgu-bg min-h-screen">
    <div class="flex h-screen bg-gray-100">
        
        <div class="fixed inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 transition duration-200 ease-in-out bg-primary w-64 z-30" id="sidebar">
            <div class="flex items-center justify-between h-16 px-4 border-b border-primary/50">
                <h1 class="text-white text-xl font-bold">🏛️ LGU1 Portal</h1>
                <button id="close-sidebar" class="text-white md:hidden">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <nav class="flex flex-col flex-1 overflow-y-auto">
                <a href="{{ route('citizen.dashboard') }}" class="flex items-center px-4 py-3 text-white hover:bg-primary/80 {{ request()->routeIs('citizen.dashboard') ? 'bg-primary/70 border-l-4 border-secondary' : '' }}">
                    <i class="fas fa-tachometer-alt w-5 h-5 mr-3"></i>
                    Dashboard
                </a>
                <a href="{{ route('citizen.reservations.create') }}" class="flex items-center px-4 py-3 text-white hover:bg-primary/80 {{ request()->routeIs('citizen.reservations.create') ? 'bg-primary/70 border-l-4 border-secondary' : '' }}">
                    <i class="fas fa-calendar-plus w-5 h-5 mr-3"></i>
                    Make a Reservation
                </a>
                <a href="{{ route('citizen.availability.index') }}" class="flex items-center px-4 py-3 text-white hover:bg-primary/80 {{ request()->routeIs('citizen.availability.index') ? 'bg-primary/70 border-l-4 border-secondary' : '' }}">
                    <i class="fas fa-calendar-alt w-5 h-5 mr-3"></i>
                    Facility Availability
                </a>
                <a href="{{ route('citizen.history.index') }}" class="flex items-center px-4 py-3 text-white hover:bg-primary/80 {{ request()->routeIs('citizen.history.index') ? 'bg-primary/70 border-l-4 border-secondary' : '' }}">
                    <i class="fas fa-history w-5 h-5 mr-3"></i>
                    Reservation History
                </a>
                <a href="{{ route('citizen.bulletin-board') }}" class="flex items-center px-4 py-3 text-white hover:bg-primary/80 {{ request()->routeIs('citizen.bulletin-board') ? 'bg-primary/70 border-l-4 border-secondary' : '' }}">
                    <i class="fas fa-clipboard-list w-5 h-5 mr-3"></i>
                    Bulletin Board
                </a>
                <a href="{{ route('citizen.profile.index') }}" class="flex items-center px-4 py-3 text-white hover:bg-primary/80 {{ request()->routeIs('citizen.profile.index') ? 'bg-primary/70 border-l-4 border-secondary' : '' }}">
                    <i class="fas fa-user-circle w-5 h-5 mr-3"></i>
                    My Profile
                </a>
                <a href="{{ route('citizen.help-faq') }}" class="flex items-center px-4 py-3 text-white hover:bg-primary/80 {{ request()->routeIs('citizen.help-faq') ? 'bg-primary/70 border-l-4 border-secondary' : '' }}">
                    <i class="fas fa-question-circle w-5 h-5 mr-3"></i>
                    Help & Support
                </a>
                
                <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-3 text-white hover:bg-lgu-red/80 bg-lgu-red/70">
                        <i class="fas fa-sign-out-alt w-5 h-5 mr-3"></i>
                        Logout
                    </button>
                </form>
            </nav>
        </div>
        
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="flex items-center justify-between bg-white shadow p-4 md:p-6">
                <div class="flex items-center">
                    <button id="open-sidebar" class="text-lgu-headline mr-4 md:hidden">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div class="flex flex-col">
                        <h2 class="text-xl font-bold text-gray-900">@yield('page-title', 'Page Title')</h2>
                        <p class="text-sm text-gray-600">@yield('page-description', 'Page Description')</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" placeholder="Search facilities..." class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-lgu-highlight focus:border-transparent">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <div class="relative">
                        <button class="p-2 text-lgu-paragraph hover:text-lgu-headline relative">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                            </svg>
                            </button>
                    </div>
                    <a href="{{ route('citizen.profile.index') }}" class="flex items-center space-x-2 text-lgu-headline hover:text-primary transition-colors">
                        <img class="h-8 w-8 rounded-full object-cover" src="{{ auth()->user()->profile_photo_url ?? asset('images/default-avatar.png') }}" alt="{{ auth()->user()->name ?? 'User' }}">
                        <span class="hidden sm:inline text-sm font-medium">{{ auth()->user()->name ?? 'Citizen' }}</span>
                    </a>
                </div>
            </header>
            
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 md:p-6 bg-lgu-bg">
                @yield('content')
            </main>
        </div>
    </div>
    
    @stack('scripts')

    <script>
        // ... JavaScript for sidebar toggle and user profile dropdown
    </script>
</body>
</html>