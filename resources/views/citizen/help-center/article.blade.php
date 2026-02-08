@extends('layouts.citizen')

@section('title', $article->title)
@section('page-title', $article->title)
@section('page-subtitle', 'Help Article')

@section('page-content')
<!-- Breadcrumb -->
<div class="mb-6">
    <div class="flex items-center text-sm text-gray-500 space-x-2">
        <a href="{{ URL::signedRoute('citizen.help-center.index') }}" class="hover:text-lgu-button transition">Help Center</a>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
        <a href="{{ URL::signedRoute('citizen.help-center.articles') }}" class="hover:text-lgu-button transition">Articles</a>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
        <span class="text-gray-700 font-medium">{{ Str::limit($article->title, 40) }}</span>
    </div>
</div>

<!-- Article -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Content -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-md p-8">
            <!-- Category Badge -->
            @if($article->category)
            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full mb-4">
                {{ ucfirst(str_replace('_', ' ', $article->category)) }}
            </span>
            @endif

            <!-- Title -->
            <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $article->title }}</h1>

            <!-- Meta -->
            <div class="flex flex-wrap items-center text-sm text-gray-500 mb-6 gap-4">
                <div class="flex items-center">
                    <i data-lucide="calendar" class="w-4 h-4 mr-1"></i>
                    {{ $article->created_at->format('F j, Y') }}
                </div>
                <div class="flex items-center">
                    <i data-lucide="eye" class="w-4 h-4 mr-1"></i>
                    {{ $article->view_count }} views
                </div>
                @if($article->updated_at && $article->updated_at->gt($article->created_at))
                <div class="flex items-center">
                    <i data-lucide="refresh-cw" class="w-4 h-4 mr-1"></i>
                    Updated {{ $article->updated_at->format('F j, Y') }}
                </div>
                @endif
            </div>

            <!-- Excerpt -->
            @if($article->excerpt)
            <div class="bg-gray-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg">
                <p class="text-gray-700 italic">{{ $article->excerpt }}</p>
            </div>
            @endif

            <!-- Content -->
            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                {!! $article->content !!}
            </div>

            <!-- Video -->
            @if($article->video_url)
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                    <i data-lucide="play-circle" class="w-5 h-5 mr-2 text-blue-600"></i>
                    Video Guide
                </h3>
                <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden">
                    <iframe src="{{ $article->video_url }}" frameborder="0" allowfullscreen class="w-full h-64 rounded-lg"></iframe>
                </div>
            </div>
            @endif

            <!-- Screenshots -->
            @if($article->screenshots && count($article->screenshots) > 0)
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                    <i data-lucide="image" class="w-5 h-5 mr-2 text-blue-600"></i>
                    Screenshots
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($article->screenshots as $screenshot)
                    <img src="{{ asset('storage/' . $screenshot) }}" alt="Screenshot" class="rounded-lg border shadow-sm cursor-pointer hover:shadow-md transition" onclick="openImage(this.src)">
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Tags -->
            @if($article->tags && count($article->tags) > 0)
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex flex-wrap items-center gap-2">
                    <i data-lucide="tag" class="w-4 h-4 text-gray-400"></i>
                    @foreach($article->tags as $tag)
                    <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">{{ $tag }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Helpful -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 font-medium">Was this article helpful?</span>
                    <div class="flex gap-3">
                        <button onclick="markHelpful('article', {{ $article->id }}, 'yes')" 
                            class="inline-flex items-center px-4 py-2 bg-green-50 text-green-700 rounded-lg hover:bg-green-100 transition text-sm font-medium">
                            <i data-lucide="thumbs-up" class="w-4 h-4 mr-2"></i>
                            Yes ({{ $article->helpful_count }})
                        </button>
                        <button onclick="markHelpful('article', {{ $article->id }}, 'no')" 
                            class="inline-flex items-center px-4 py-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition text-sm font-medium">
                            <i data-lucide="thumbs-down" class="w-4 h-4 mr-2"></i>
                            No ({{ $article->not_helpful_count }})
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1">
        <!-- Related Articles -->
        @if($relatedArticles->count() > 0)
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                <i data-lucide="book-open" class="w-5 h-5 mr-2 text-blue-600"></i>
                Related Articles
            </h3>
            <div class="space-y-4">
                @foreach($relatedArticles as $related)
                <a href="{{ URL::signedRoute('citizen.help-center.article', $related->slug) }}" 
                   class="block p-3 rounded-lg hover:bg-gray-50 transition border border-gray-100">
                    <h4 class="font-medium text-gray-800 text-sm mb-1">{{ $related->title }}</h4>
                    <p class="text-xs text-gray-500">{{ Str::limit($related->excerpt, 60) }}</p>
                    <div class="flex items-center text-xs text-gray-400 mt-2">
                        <i data-lucide="eye" class="w-3 h-3 mr-1"></i>
                        {{ $related->view_count }} views
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Back to Help Center -->
        <div class="bg-blue-50 rounded-lg p-6 text-center">
            <i data-lucide="help-circle" class="w-10 h-10 text-blue-600 mx-auto mb-3"></i>
            <h3 class="font-bold text-gray-800 mb-2">Need more help?</h3>
            <p class="text-sm text-gray-600 mb-4">Browse more articles or contact our support team.</p>
            <div class="space-y-2">
                <a href="{{ URL::signedRoute('citizen.help-center.articles') }}" 
                   class="block w-full px-4 py-2 bg-white text-blue-600 font-semibold rounded-lg hover:bg-gray-50 transition text-sm border border-blue-200">
                    Browse All Articles
                </a>
                <a href="{{ URL::signedRoute('citizen.contact.index') }}" 
                   class="block w-full px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition text-sm">
                    Contact Support
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function markHelpful(type, id, helpful) {
    fetch(`{{ URL::signedRoute('citizen.help-center.helpful', ['type' => 'TYPE', 'id' => 'ID']) }}`.replace('TYPE', type).replace('ID', id), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ helpful: helpful })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Thank you!',
                text: 'Your feedback helps us improve.',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
}

function openImage(src) {
    Swal.fire({
        imageUrl: src,
        imageAlt: 'Screenshot',
        showConfirmButton: false,
        showCloseButton: true,
        width: 'auto',
        padding: '1rem',
        customClass: {
            popup: 'rounded-2xl'
        }
    });
}
</script>
@endsection
