<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <title>Welcome - LGU Facility Reservation System</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        {{-- Use Vite to bundle assets (Standard Laravel practice) --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-100 font-sans">
        
        <div class="relative min-h-screen flex flex-col justify-center items-center py-10 sm:pt-0">

            {{-- Navigation/Header Section --}}
            @if (Route::has('login'))
                <div class="fixed top-0 right-0 p-6 text-right z-10">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500 transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500 transition">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500 transition">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            {{-- Main Content Section --}}
            <main class="max-w-6xl mx-auto sm:px-6 lg:px-8 mt-16">
                <div class="text-center py-12">
                    <h1 class="text-6xl font-extrabold text-lgu-headline leading-tight mb-4">
                        LGU Facility Reservation System
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                        Your official platform for booking public facilities and services efficiently.
                    </p>
                    
                    {{-- Call to Action Buttons --}}
                    <div class="space-x-4">
                        <a href="{{ route('reservations.create') }}" 
                           class="inline-block px-8 py-3 bg-lgu-highlight text-white text-lg font-semibold rounded-lg shadow-lg hover:bg-yellow-400 transition transform hover:scale-105">
                            Book a Facility Now
                        </a>
                        <a href="{{ route('login') }}" 
                           class="inline-block px-8 py-3 bg-white border border-gray-300 text-gray-800 text-lg font-semibold rounded-lg shadow-md hover:bg-gray-50 transition transform hover:scale-105">
                            Admin Login
                        </a>
                    </div>
                </div>

                {{-- Feature Section (Placeholder) --}}
                <div class="mt-16 grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                    <div class="p-6 bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                        <svg class="w-10 h-10 text-lgu-headline mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Real-Time Scheduling</h3>
                        <p class="text-gray-600 text-sm">Check facility availability instantly on the calendar.</p>
                    </div>
                    
                    <div class="p-6 bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                        <svg class="w-10 h-10 text-lgu-headline mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Simplified Approval</h3>
                        <p class="text-gray-600 text-sm">Streamlined approval process for administrative staff.</p>
                    </div>
                    
                    <div class="p-6 bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                        <svg class="w-10 h-10 text-lgu-headline mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Digital Requirements</h3>
                        <p class="text-gray-600 text-sm">Upload all necessary documents directly during booking.</p>
                    </div>
                </div>
                
                {{-- Footer --}}
                <footer class="mt-16 text-center text-sm text-gray-500">
                    &copy; {{ date('Y') }} LGU Facility Reservation System. All rights reserved.
                </footer>
            </main>
        </div>
    </body>
</html>