@extends('layouts.app')

@section('title', 'User Feedback & Inquiries')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
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
            <div class="flex items-center space-x-3">
                <div class="w-16 h-16 bg-lgu-highlight/20 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/10">
                    <svg class="w-8 h-8 text-lgu-highlight" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h4l2 3 2-3h4a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 000 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold mb-1 text-white">Feedback Management</h1>
                    <p class="text-gray-200">Review user feedback and inquiries for follow-up.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        @forelse($feedbackItems as $item)
            <div class="bg-white rounded-lg shadow-md border border-gray-200 p-5 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div class="min-w-0 pr-4">
                        {{-- Status Badge --}}
                        <div class="mb-2">
                            @if($item->is_responded)
                                <span class="px-3 py-1 text-xs font-medium leading-5 rounded-full bg-green-100 text-green-800">
                                    Responded
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-medium leading-5 rounded-full bg-yellow-100 text-yellow-800 animate-pulse">
                                    New Inquiry
                                </span>
                            @endif
                        </div>

                        {{-- Subject / Name --}}
                        <p class="text-lg font-semibold text-gray-900 truncate">{{ $item->subject }}</p>
                        <p class="text-sm text-gray-600 truncate">
                            From: <span class="font-medium text-gray-700">{{ $item->name }}</span> ({{ $item->email }})
                        </p>
                        
                        {{-- Category and Date --}}
                        <div class="mt-2 text-xs space-x-3 text-gray-500">
                            <span class="inline-flex items-center">
                                <svg class="w-4 h-4 mr-1 text-lgu-highlight" fill="currentColor" viewBox="0 0 20 20"><path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 6h12a1 1 0 011 1v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a1 1 0 011-1zm3 6a1 1 0 100-2 1 1 0 000 2zm4 0a1 1 0 100-2 1 1 0 000 2zm3 0a1 1 0 100-2 1 1 0 000 2z"/></svg>
                                {{ ucfirst(str_replace('_', ' ', $item->category)) }}
                            </span>
                            <span class="inline-flex items-center">
                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                                Submitted {{ $item->created_at->diffForHumans() }}
                            </span>
                        </div>
                        
                        {{-- Response Info --}}
                        @if($item->is_responded)
                            <div class="mt-1 text-xs text-green-600 font-medium">
                                Responded {{ $item->responded_at->diffForHumans() }} by {{ $item->respondedBy->name ?? 'Admin' }}
                            </div>
                        @endif
                    </div>
                    
                    {{-- Action Button --}}
                    <div class="ml-4 flex-shrink-0">
                        <a href="{{ route('admin.feedback.show', $item->id) }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-lgu-highlight hover:bg-lgu-button transition-colors">
                            <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                            View & Respond
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-12 text-center text-gray-500 bg-white rounded-lg shadow-md border border-gray-200">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
                </svg>
                <p class="text-xl font-semibold text-gray-900">No New Feedback</p>
                <p class="mt-2 text-gray-600">All user inquiries have been addressed.</p>
            </div>
        @endforelse
        
        {{-- Pagination --}}
        <div class="pt-4">
            {{ $feedbackItems->links() }}
        </div>
    </div>
</div>
@endsection