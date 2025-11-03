@extends('layouts.staff')

@section('content')
<div class="space-y-6">
    <div class="bg-lgu-headline rounded-2xl p-8 text-white shadow-lgu-lg overflow-hidden relative">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
                <pattern id="pattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                    <circle cx="10" cy="10" r="1" fill="currentColor"/>
                </pattern>
                <rect width="100%" height="100%" fill="url(#pattern)"/>
            </svg>
        </div>

        <div class="relative z-10 flex items-center justify-between">
            <div class="space-y-3">
                <div class="flex items-center space-x-3">
                    {{-- Icon for Help & Support --}}
                    <div class="w-16 h-16 bg-lgu-highlight/20 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/10">
                        <svg class="w-8 h-8 text-lgu-highlight" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold mb-1">Help & Support</h1>
                        <p class="text-gray-200">Get assistance, view FAQs, and check system status</p>
                    </div>
                </div>
            </div>
            
            {{-- Contact Button --}}
            <button class="bg-lgu-button text-lgu-button-text font-semibold px-6 py-3 rounded-lg shadow-md hover:bg-lgu-highlight/80 transition duration-300 hidden sm:block">
                Contact Admin
            </button>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Support Sections --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Frequently Asked Questions (FAQ) Section --}}
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Frequently Asked Questions (FAQ)</h2>
                
                {{-- FAQ Item 1 --}}
                <div class="border-b py-3">
                    <button class="flex justify-between items-center w-full text-left font-medium text-lgu-headline hover:text-lgu-highlight transition duration-150">
                        How do I flag a document for revision?
                        <svg class="w-5 h-5 transform rotate-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div class="mt-2 text-gray-600 text-sm hidden">
                        On the Verification page, select the 'Send for Revision' option and provide a detailed reason in the mandatory comment box before submitting.
                    </div>
                </div>
                
                {{-- FAQ Item 2 --}}
                <div class="border-b py-3">
                    <button class="flex justify-between items-center w-full text-left font-medium text-lgu-headline hover:text-lgu-highlight transition duration-150">
                        What documents require a cross-check with the national registry?
                        <svg class="w-5 h-5 transform rotate-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div class="mt-2 text-gray-600 text-sm hidden">
                        All national ID, business permit, and large facility booking applications must be cross-checked with the relevant external systems (link provided in the task details).
                    </div>
                </div>
                
                {{-- FAQ Item 3 --}}
                <div class="py-3">
                    <button class="flex justify-between items-center w-full text-left font-medium text-lgu-headline hover:text-lgu-highlight transition duration-150">
                        Where can I find my personal statistics?
                        <svg class="w-5 h-5 transform rotate-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div class="mt-2 text-gray-600 text-sm hidden">
                        Navigate to the 'My Statistics' link in the sidebar or click the 'My Statistics' quick action card on your Dashboard.
                    </div>
                </div>

            </div>

            {{-- Contact Form (Placeholder) --}}
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Submit a Support Ticket</h2>
                <form action="#" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                        <input type="text" id="subject" name="subject" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 focus:border-lgu-highlight focus:ring-lgu-highlight" placeholder="e.g., Issue with login or document viewer">
                    </div>
                    <div>
                        <label for="issue_details" class="block text-sm font-medium text-gray-700">Detailed Description of Issue</label>
                        <textarea id="issue_details" name="issue_details" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2 focus:border-lgu-highlight focus:ring-lgu-highlight"></textarea>
                    </div>
                    <button type="submit" class="bg-red-500 text-white font-semibold px-6 py-2 rounded-lg shadow-md hover:bg-red-600 transition duration-300">
                        Submit Ticket
                    </button>
                </form>
            </div>
        </div>

        {{-- System Status & Contact Info Sidebar --}}
        <div class="space-y-6">
            <h3 class="text-lg font-semibold text-gray-800">System Status</h3>

            {{-- Status Cards --}}
            <div class="space-y-4">
                {{-- Verification Service Status --}}
                <div class="border border-green-200 rounded-lg p-4 bg-green-50">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-semibold text-gray-900">Verification Service</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                            Online
                        </span>
                    </div>
                    <p class="text-sm text-gray-600">All systems operational</p>
                </div>

                {{-- Database Status --}}
                <div class="border border-green-200 rounded-lg p-4 bg-green-50">
                    <div class="flex items-center justify-between mb-2">
                        <span class="font-semibold text-gray-900">Database</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                            Online
                        </span>
                    </div>
                    <p class="text-sm text-gray-600">All systems operational</p>
                </div>
            </div>

            {{-- Important Notice --}}
            <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-sm text-blue-900">
                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    For **urgent matters** (e.g., system downtime, unauthorized access), please call the IT Support Hotline immediately: **(02) 8XXX-XXXX**.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection