@extends('layouts.citizen')

@section('title', 'Search Results - Help Center')
@section('page-title', 'Search Results')
@section('page-subtitle', 'Help Center search results')

@section('page-content')
<!-- Header -->
<div class="mb-8">
    <div class="flex items-center mb-4">
        <a href="{{ URL::signedRoute('citizen.help-center.index') }}" 
           class="inline-flex items-center text-lgu-button hover:text-lgu-highlight font-medium">
            <i data-lucide="arrow-left" class="w-5 h-5 mr-2"></i>
            Back to Help Center
        </a>
    </div>
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Search Results</h1>
    <p class="text-gray-600">Showing results for "<strong>{{ $searchTerm }}</strong>"</p>
</div>

<!-- Search Bar -->
<div class="mb-8">
    <form action="{{ URL::signedRoute('citizen.help-center.search') }}" method="GET" class="relative max-w-2xl">
        <input type="text" name="q" value="{{ $searchTerm }}" placeholder="Search for help..." 
            class="w-full px-6 py-3 pr-12 border-2 border-gray-300 rounded-full focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm">
        <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-full transition">
            <i data-lucide="search" class="w-5 h-5"></i>
        </button>
    </form>
</div>

<!-- FAQs Results -->
@if($faqs->count() > 0)
<div class="mb-10">
    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
        <i data-lucide="help-circle" class="w-5 h-5 mr-2 text-blue-600"></i>
        FAQs ({{ $faqs->count() }})
    </h2>
    <div class="space-y-3">
        @foreach($faqs as $faq)
        <div class="bg-white rounded-lg shadow-sm p-5 border border-gray-100 hover:shadow-md transition">
            <h3 class="font-semibold text-gray-800 mb-2">{{ $faq->question }}</h3>
            <p class="text-sm text-gray-600">{{ Str::limit($faq->answer, 200) }}</p>
            @if($faq->category)
            <span class="inline-block mt-2 px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                {{ $faq->category->name }}
            </span>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif

<!-- Articles Results -->
@if($articles->count() > 0)
<div class="mb-10">
    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
        <i data-lucide="file-text" class="w-5 h-5 mr-2 text-blue-600"></i>
        Help Articles ({{ $articles->count() }})
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($articles as $article)
        <a href="{{ URL::signedRoute('citizen.help-center.article', $article->slug) }}" 
           class="bg-white rounded-lg shadow-sm p-5 border border-gray-100 hover:shadow-md transition">
            <h3 class="font-semibold text-gray-800 mb-2">{{ $article->title }}</h3>
            <p class="text-sm text-gray-600 mb-3">{{ Str::limit($article->excerpt, 120) }}</p>
            <div class="flex items-center text-xs text-gray-500">
                @if($article->category)
                <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-full mr-3">
                    {{ ucfirst(str_replace('_', ' ', $article->category)) }}
                </span>
                @endif
                <i data-lucide="eye" class="w-3 h-3 mr-1"></i>
                {{ $article->view_count }} views
            </div>
        </a>
        @endforeach
    </div>
</div>
@endif

<!-- No Results -->
@if($faqs->count() == 0 && $articles->count() == 0)
<div class="bg-white rounded-lg shadow-md p-12 text-center">
    <div class="flex justify-center mb-4">
        <div class="bg-gray-100 rounded-full p-4">
            <i data-lucide="search-x" class="w-12 h-12 text-gray-400"></i>
        </div>
    </div>
    <h3 class="text-xl font-semibold text-gray-800 mb-2">No Results Found</h3>
    <p class="text-gray-600 mb-6">We couldn't find any results for "<strong>{{ $searchTerm }}</strong>". Try different keywords or browse our articles.</p>
    <div class="flex flex-col sm:flex-row gap-3 justify-center">
        <a href="{{ URL::signedRoute('citizen.help-center.articles') }}" 
           class="inline-flex items-center px-6 py-3 bg-white text-blue-600 font-semibold rounded-lg hover:bg-gray-50 transition border border-blue-200">
            <i data-lucide="book-open" class="w-5 h-5 mr-2"></i>
            Browse Articles
        </a>
        <a href="{{ URL::signedRoute('citizen.contact.index') }}" 
           class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
            <i data-lucide="message-circle" class="w-5 h-5 mr-2"></i>
            Contact Support
        </a>
    </div>
</div>
@endif
@endsection
