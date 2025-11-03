@extends('citizen.layouts.app-sidebar')

@section('title', 'Help & FAQ - LGU1 Citizen Portal')
@section('page-title', 'Help & Support')
@section('page-description', 'Get answers to your questions or submit a new inquiry')

@section('content')
<div class="space-y-6">
    
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-start">
            <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-lg p-6 border-2 border-blue-200">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Submit an Inquiry/Feedback</h2>
        <p class="text-gray-600 mb-6">Can't find an answer? Send us your question directly.</p>

        <form method="POST" action="{{ route('citizen.feedback.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select id="category" name="category" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('category') border-red-500 @enderror">
                    <option value="" disabled selected>Select a category</option>
                    <option value="reservation_issue" {{ old('category') == 'reservation_issue' ? 'selected' : '' }}>Reservation Issue</option>
                    <option value="payment_query" {{ old('category') == 'payment_query' ? 'selected' : '' }}>Payment Query</option>
                    <option value="technical_support" {{ old('category') == 'technical_support' ? 'selected' : '' }}>Technical Support</option>
                    <option value="general_inquiry" {{ old('category') == 'general_inquiry' ? 'selected' : '' }}>General Inquiry</option>
                    <option value="feedback_suggestion" {{ old('category') == 'feedback_suggestion' ? 'selected' : '' }}>Feedback/Suggestion</option>
                </select>
                @error('category')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('subject') border-red-500 @enderror"
                       placeholder="Brief summary of your question">
                @error('subject')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                <textarea id="message" name="message" rows="5" required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('message') border-red-500 @enderror"
                          placeholder="Provide details of your inquiry or feedback">{{ old('message') }}</textarea>
                @error('message')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="pt-4">
                <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-lg">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Submit Inquiry
                </button>
            </div>
        </form>
    </div>

    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
        <h3 class="text-lg font-bold text-yellow-800 mb-4 flex items-center">
            <i class="fas fa-exclamation-circle mr-2"></i>
            Important Reminders
        </h3>
        <ul class="space-y-3 text-sm text-yellow-700 list-none pl-0">
            <li class="flex items-start">
                <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <span><strong>Reservation Process:</strong> All facility reservations must be submitted through the "New Reservation" page and are subject to LGU staff approval.</span>
            </li>
            <li class="flex items-start">
                <svg class="w-5 h-5 text-red-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <span><strong>Payment deadline:</strong> Pay within 7 days after approval or your reservation will be cancelled</span>
            </li>
            <li class="flex items-start">
                <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <span><strong>Cancellation Policy:</strong> Cancellations must be made at least 48 hours before the event date to be eligible for a refund (subject to terms).</span>
            </li>
        </ul>
    </div>
</div>
@endsection