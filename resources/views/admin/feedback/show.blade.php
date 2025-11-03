@extends('layouts.app')

@section('title', 'Feedback Details - ' . $feedback->subject)

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    <div class="bg-lgu-headline rounded-2xl p-6 text-white shadow-lgu-lg overflow-hidden relative">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
                <pattern id="pattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                    <circle cx="10" cy="10" r="1" fill="currentColor"/>
                </pattern>
                <rect width="100%" height="100%" fill="url(#pattern)"/>
            </svg>
        </div>
        
        <div class="relative z-10 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-lgu-highlight/20 rounded-xl flex items-center justify-center backdrop-blur-sm border border-white/10">
                    <svg class="w-6 h-6 text-lgu-highlight" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h4l2 3 2-3h4a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 000 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-white">{{ $feedback->subject }}</h1>
                </div>
            </div>
            <a href="{{ route('admin.feedback.index') }}" class="text-gray-200 hover:text-white transition text-sm">
                ← Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Details and Message (2/3 width) --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">User Details</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-4 text-sm">
                    <div class="sm:col-span-1">
                        <dt class="font-medium text-gray-500">Submitted By</dt>
                        <dd class="mt-1 font-semibold text-gray-900">{{ $feedback->name }}</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="font-medium text-gray-500">Contact Email</dt>
                        <dd class="mt-1 text-lgu-highlight font-medium hover:text-lgu-button transition">
                            <a href="mailto:{{ $feedback->email }}">{{ $feedback->email }}</a>
                        </dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="font-medium text-gray-500">Submission Date</dt>
                        <dd class="mt-1 text-gray-900">{{ $feedback->created_at->format('M d, Y h:i A') }} ({{ $feedback->created_at->diffForHumans() }})</dd>
                    </div>
                    <div class="sm:col-span-1">
                        <dt class="font-medium text-gray-500">Category</dt>
                        <dd class="mt-1 text-gray-900 font-medium">{{ ucfirst(str_replace('_', ' ', $feedback->category)) }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Message Content</h3>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                    <p class="text-gray-700 whitespace-pre-line leading-relaxed">
                        {{ $feedback->message }}
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <span class="mr-2">Official Response</span>
                    @if($feedback->is_responded)
                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                            Responded
                        </span>
                    @else
                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                            Pending
                        </span>
                    @endif
                </h3>
                
                @if($feedback->response)
                    <div class="bg-blue-50 border-l-4 border-blue-600 p-4 rounded-r-lg shadow-sm">
                        <p class="text-sm font-semibold text-blue-800 mb-2">
                            Responded By: {{ $feedback->respondedBy->name ?? 'Admin' }}
                        </p>
                        <p class="text-sm text-blue-700 whitespace-pre-line">
                            {{ $feedback->response }}
                        </p>
                        <p class="mt-3 text-xs text-blue-500">
                            Response Date: {{ $feedback->responded_at->format('M d, Y h:i A') }} ({{ $feedback->responded_at->diffForHumans() }})
                        </p>
                    </div>
                @else
                    <p class="text-gray-500 italic">No official response has been recorded yet.</p>
                @endif
            </div>
        </div>

        {{-- Actions and Status (1/3 width) --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 sticky top-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Quick Actions</h3>
                
                <div class="space-y-3">
                    {{-- Direct Email Link --}}
                    <a href="mailto:{{ $feedback->email }}?subject=Re: {{ $feedback->subject }}" 
                       class="block w-full px-4 py-3 bg-lgu-highlight text-white text-center font-semibold rounded-lg hover:bg-lgu-button transition-colors shadow-md">
                        <svg class="w-5 h-5 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>
                        Send Email Reply
                    </a>
                    
                    {{-- Mark as Responded Action --}}
                    @if(!$feedback->is_responded)
                        <form action="{{ route('admin.feedback.markAsResponded', $feedback->id) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="block w-full px-4 py-3 bg-green-500 text-white text-center font-semibold rounded-lg hover:bg-green-600 transition-colors shadow-md">
                                <svg class="w-5 h-5 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Mark as Responded
                            </button>
                        </form>
                    @endif
                    
                    {{-- Delete Action (Warning) --}}
                    <form action="{{ route('admin.feedback.destroy', $feedback->id) }}" 
                          method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this feedback item?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="block w-full px-4 py-3 bg-red-100 text-red-600 text-center font-medium rounded-lg hover:bg-red-200 transition-colors">
                            <svg class="w-5 h-5 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm4 0a1 1 0 10-2 0v6a1 1 0 102 0V8z" clip-rule="evenodd"/></svg>
                            Delete Feedback
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection