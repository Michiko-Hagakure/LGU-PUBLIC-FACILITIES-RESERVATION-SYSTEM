@extends('citizen.layouts.app-sidebar')

@section('title', 'Bulletin Board - LGU1 Citizen Portal')
@section('page-title', 'Bulletin Board')
@section('page-description', 'Stay updated with the latest announcements and notifications')

@section('content')
<div class="space-y-6">
    @if($pinnedAnnouncements->count() > 0)
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-400 rounded-lg p-6">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0">
                    <i class="fas fa-thumbtack text-blue-600 text-xl"></i>
                </div>
                <h3 class="ml-3 text-lg font-medium text-blue-900">Pinned Announcements</h3>
            </div>
            
            <div class="grid gap-4">
                @foreach($pinnedAnnouncements as $announcement)
                    <div class="bg-white rounded-lg shadow-sm border border-blue-200 p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <h4 class="font-semibold text-gray-900">{{ $announcement->title }}</h4>
                                    @php
                                        $labelClass = $announcement->is_urgent ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $labelClass }}">
                                        {{ $announcement->is_urgent ? 'URGENT' : 'NEW' }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-2">
                                    <i class="fas fa-calendar-alt mr-1"></i> Posted: {{ $announcement->created_at->format('M d, Y') }}
                                </p>
                                
                                <p class="text-gray-700 text-sm leading-relaxed mb-3">
                                    {{-- Use str_limit or a similar helper for initial snippet --}}
                                    {{ Str::limit(strip_tags($announcement->content), 150, '...') }}
                                </p>

                                @if(strlen(strip_tags($announcement->content)) > 150)
                                    <div id="full-content-{{ $announcement->id }}" class="hidden">
                                        <div class="text-gray-700 text-sm leading-relaxed mb-3">
                                            {!! $announcement->content !!}
                                        </div>
                                    </div>
                                    <button onclick="toggleContent({{ $announcement->id }})" id="toggle-btn-{{ $announcement->id }}" 
                                            class="text-blue-600 text-sm font-medium hover:text-blue-800 transition-colors">
                                        Read more
                                    </button>
                                @else
                                    <div class="text-gray-700 text-sm leading-relaxed mb-3">
                                        {!! $announcement->content !!}
                                    </div>
                                @endif

                                @if($announcement->attachment_path)
                                    <div class="mt-3">
                                        <a href="{{ Storage::url($announcement->attachment_path) }}" target="_blank"
                                           class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-full hover:bg-gray-200 transition-colors">
                                            <i class="fas fa-file-download mr-1"></i> Download Attachment
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-6 border-b pb-3">Other Announcements</h3>
        
        <div class="space-y-4">
            @forelse($otherAnnouncements as $announcement)
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 mb-1">{{ $announcement->title }}</h4>
                            <p class="text-sm text-gray-600 mb-2">
                                <i class="fas fa-calendar-alt mr-1"></i> Posted: {{ $announcement->created_at->format('M d, Y') }}
                            </p>
                            
                            <p class="text-gray-700 text-sm leading-relaxed mb-3">
                                {{ Str::limit(strip_tags($announcement->content), 150, '...') }}
                            </p>

                            @if(strlen(strip_tags($announcement->content)) > 150)
                                <div id="full-content-{{ $announcement->id }}" class="hidden">
                                    <div class="text-gray-700 text-sm leading-relaxed mb-3">
                                        {!! $announcement->content !!}
                                    </div>
                                </div>
                                <button onclick="toggleContent({{ $announcement->id }})" id="toggle-btn-{{ $announcement->id }}" 
                                        class="text-blue-600 text-sm font-medium hover:text-blue-800 transition-colors">
                                    Read more
                                </button>
                            @else
                                <div class="text-gray-700 text-sm leading-relaxed mb-3">
                                    {!! $announcement->content !!}
                                </div>
                            @endif

                            @if($announcement->attachment_path)
                                <div class="mt-3">
                                    <a href="{{ Storage::url($announcement->attachment_path) }}" target="_blank"
                                       class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 text-xs font-medium rounded-full hover:bg-gray-200 transition-colors">
                                        <i class="fas fa-file-download mr-1"></i> Download Attachment
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <p class="mt-2 text-sm">No other announcements available at this time.</p>
                </div>
            @endforelse
        </div>
        
        <div class="mt-6">
            {{ $otherAnnouncements->links() }}
        </div>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Stay Informed</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Check the bulletin board regularly for important updates</li>
                        <li>Pinned announcements contain urgent or important information</li>
                        <li>Download attachments for detailed information or forms</li>
                        <li>Contact our office if you have questions about any announcement</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleContent(announcementId) {
    const fullContent = document.getElementById(`full-content-${announcementId}`);
    const toggleBtn = document.getElementById(`toggle-btn-${announcementId}`);
    
    if (fullContent.classList.contains('hidden')) {
        fullContent.classList.remove('hidden');
        toggleBtn.textContent = 'Read less';
    } else {
        fullContent.classList.add('hidden');
        toggleBtn.textContent = 'Read more';
    }
}
</script>
@endpush