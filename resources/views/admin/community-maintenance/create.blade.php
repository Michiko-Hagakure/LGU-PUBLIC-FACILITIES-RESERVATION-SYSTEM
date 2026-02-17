@extends('layouts.admin')

@section('page-title', 'Request Facility Maintenance')
@section('page-subtitle', 'Submit maintenance request to Community Infrastructure Management')

@section('page-content')
<div class="max-w-4xl mx-auto">
    {{-- Success/Error Messages --}}
    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
        <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <div>
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start gap-3">
        <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <div>
            <p class="text-red-800 font-medium">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <div>
                <p class="text-red-800 font-medium mb-2">Please fix the following errors:</p>
                <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    {{-- Integration Info Card --}}
    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <div class="flex items-start gap-3">
            <i data-lucide="info" class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5"></i>
            <div>
                <p class="text-blue-800 font-medium">Community Infrastructure Maintenance Integration</p>
                <p class="text-blue-700 text-sm mt-1">This form submits general maintenance requests to the Community Infrastructure Maintenance Management system. Select the appropriate category and issue type for your request.</p>
            </div>
        </div>
    </div>

    <form action="{{ URL::signedRoute('admin.community-maintenance.store') }}" method="POST" enctype="multipart/form-data" id="maintenanceRequestForm">
        @csrf

        {{-- Section 1: Facility & Category Selection --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-lgu-headline to-lgu-stroke px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                    <i data-lucide="building-2" class="w-5 h-5"></i>
                    Facility & Category
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <div>
                    <label for="facility_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Select Facility <span class="text-red-500">*</span>
                    </label>
                    <select id="facility_id" name="facility_id" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors" required>
                        <option value="">Choose a facility...</option>
                        @foreach($facilities as $facility)
                        <option value="{{ $facility->facility_id }}" 
                            data-address="{{ $facility->name }}, {{ $facility->full_address ?? $facility->address }}"
                            {{ old('facility_id') == $facility->facility_id ? 'selected' : '' }}>
                            {{ $facility->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <select id="category" name="category" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors" required>
                            <option value="">Select a category...</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category', 'Facilities') == $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="issue_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Issue Type <span class="text-red-500">*</span>
                        </label>
                        <select id="issue_type" name="issue_type" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors" required>
                            <option value="">Select an issue type...</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                        Location <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="location" name="location" 
                        value="{{ old('location') }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors"
                        placeholder="e.g., City Hall Building A, 3rd Floor" required>
                </div>
            </div>
        </div>

        {{-- Section 2: Reporter Information --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-lgu-headline to-lgu-stroke px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                    <i data-lucide="user" class="w-5 h-5"></i>
                    Reporter Information
                </h2>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="reporter_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Reporter Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="reporter_name" name="reporter_name" 
                        value="{{ old('reporter_name', session('user_name')) }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors"
                        placeholder="Your full name" required>
                </div>

                <div>
                    <label for="reporter_contact" class="block text-sm font-medium text-gray-700 mb-2">
                        Contact Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="reporter_contact" name="reporter_contact" 
                        value="{{ old('reporter_contact') }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors"
                        placeholder="e.g., 09171234567" required>
                </div>
            </div>
        </div>

        {{-- Section 3: Request Details --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-lgu-headline to-lgu-stroke px-6 py-4">
                <h2 class="text-lg font-semibold text-white flex items-center gap-2">
                    <i data-lucide="file-text" class="w-5 h-5"></i>
                    Request Details
                </h2>
            </div>
            <div class="p-6 space-y-6">
                <div>
                    <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                        Priority Level <span class="text-red-500">*</span>
                    </label>
                    <select id="priority" name="priority" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors" required>
                        @foreach($priorityLevels as $level)
                        <option value="{{ $level['value'] }}" {{ old('priority', 'Medium') == $level['value'] ? 'selected' : '' }}>
                            {{ $level['label'] }} - {{ $level['description'] }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Detailed Description <span class="text-red-500">*</span>
                    </label>
                    <textarea id="description" name="description" rows="5" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-lgu-highlight focus:border-lgu-highlight transition-colors resize-none"
                        placeholder="Provide a detailed description of the maintenance issue, including when it started, severity of damage, and any safety concerns..." required>{{ old('description') }}</textarea>
                </div>

                <div>
                    <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">
                        Photo (Optional)
                    </label>
                    <div class="flex items-center gap-4">
                        <label class="flex-1 flex items-center justify-center px-4 py-6 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer hover:border-lgu-highlight hover:bg-gray-50 transition-colors" id="photoDropZone">
                            <div class="text-center">
                                <i data-lucide="camera" class="w-8 h-8 text-gray-400 mx-auto mb-2"></i>
                                <p class="text-sm text-gray-600" id="photoLabel">Click to upload a photo of the issue</p>
                                <p class="text-xs text-gray-400 mt-1">JPG, PNG, GIF up to 5MB</p>
                            </div>
                            <input type="file" id="photo" name="photo" accept="image/*" class="hidden">
                        </label>
                    </div>
                    <div id="photoPreview" class="mt-3 hidden">
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                            <img id="photoPreviewImg" class="w-16 h-16 object-cover rounded" src="" alt="Preview">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-700" id="photoFileName"></p>
                                <p class="text-xs text-gray-500" id="photoFileSize"></p>
                            </div>
                            <button type="button" id="removePhoto" class="text-red-500 hover:text-red-700">
                                <i data-lucide="x-circle" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Priority Indicator --}}
        <div class="mb-6 p-4 rounded-lg border" id="priorityIndicator" style="display: none;">
            <div class="flex items-center gap-3">
                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                <div>
                    <p class="font-medium" id="priorityIndicatorTitle"></p>
                    <p class="text-sm" id="priorityIndicatorDesc"></p>
                </div>
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="flex justify-end gap-4">
            <a href="{{ URL::signedRoute('admin.community-maintenance.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                View My Requests
            </a>
            <button type="submit" id="submitBtn" class="px-8 py-3 bg-lgu-highlight text-white rounded-lg hover:bg-lgu-stroke transition-colors font-medium flex items-center gap-2">
                <i data-lucide="send" class="w-5 h-5"></i>
                Submit Maintenance Request
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Issue types by category from the General Request API
    const issueTypesByCategory = @json($issueTypesByCategory);

    // Priority indicator settings
    const prioritySettings = {
        'Low': { bg: 'bg-green-50', border: 'border-green-200', text: 'text-green-800', icon: 'text-green-600', title: 'Low Priority', desc: 'This issue will be addressed during regular maintenance schedules.' },
        'Medium': { bg: 'bg-yellow-50', border: 'border-yellow-200', text: 'text-yellow-800', icon: 'text-yellow-600', title: 'Medium Priority', desc: 'This issue will be reviewed and addressed soon.' },
        'High': { bg: 'bg-orange-50', border: 'border-orange-200', text: 'text-orange-800', icon: 'text-orange-600', title: 'High Priority', desc: 'This issue requires prompt attention and will be prioritized.' },
        'Urgent': { bg: 'bg-red-50', border: 'border-red-200', text: 'text-red-800', icon: 'text-red-600', title: 'Urgent Priority', desc: 'Emergency response team will be notified immediately.' }
    };

    // Category selection - populate issue types dynamically
    const categorySelect = document.getElementById('category');
    const issueTypeSelect = document.getElementById('issue_type');
    const oldIssueType = '{{ old("issue_type") }}';

    function updateIssueTypes() {
        const category = categorySelect.value;
        issueTypeSelect.innerHTML = '<option value="">Select an issue type...</option>';

        if (category && issueTypesByCategory[category]) {
            issueTypesByCategory[category].forEach(function(type) {
                const option = document.createElement('option');
                option.value = type;
                option.textContent = type;
                if (type === oldIssueType) {
                    option.selected = true;
                }
                issueTypeSelect.appendChild(option);
            });
        }
    }

    categorySelect.addEventListener('change', updateIssueTypes);
    // Initialize on page load
    updateIssueTypes();

    // Auto-fill location when facility is selected
    const facilitySelect = document.getElementById('facility_id');
    const locationInput = document.getElementById('location');
    
    facilitySelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const address = selectedOption.getAttribute('data-address');
        if (address && !locationInput.value) {
            locationInput.value = address;
        }
    });

    // Update priority indicator
    const prioritySelect = document.getElementById('priority');
    const priorityIndicator = document.getElementById('priorityIndicator');
    
    function updatePriorityIndicator(value) {
        const settings = prioritySettings[value];
        if (settings) {
            priorityIndicator.className = `mb-6 p-4 rounded-lg border ${settings.bg} ${settings.border}`;
            priorityIndicator.querySelector('i').className = `w-5 h-5 ${settings.icon}`;
            document.getElementById('priorityIndicatorTitle').className = `font-medium ${settings.text}`;
            document.getElementById('priorityIndicatorTitle').textContent = settings.title;
            document.getElementById('priorityIndicatorDesc').className = `text-sm ${settings.text}`;
            document.getElementById('priorityIndicatorDesc').textContent = settings.desc;
            priorityIndicator.style.display = 'block';
        }
    }

    prioritySelect.addEventListener('change', function() {
        updatePriorityIndicator(this.value);
    });

    // Initialize priority indicator
    updatePriorityIndicator(prioritySelect.value);

    // Photo upload handling
    const photoInput = document.getElementById('photo');
    const photoPreview = document.getElementById('photoPreview');
    const photoPreviewImg = document.getElementById('photoPreviewImg');
    const photoFileName = document.getElementById('photoFileName');
    const photoFileSize = document.getElementById('photoFileSize');
    const photoLabel = document.getElementById('photoLabel');
    const removePhoto = document.getElementById('removePhoto');

    photoInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                photoPreviewImg.src = e.target.result;
                photoFileName.textContent = file.name;
                photoFileSize.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
                photoPreview.classList.remove('hidden');
                photoLabel.textContent = 'Photo selected - click to change';
            };
            reader.readAsDataURL(file);
        }
    });

    removePhoto.addEventListener('click', function() {
        photoInput.value = '';
        photoPreview.classList.add('hidden');
        photoLabel.textContent = 'Click to upload a photo of the issue';
    });

    // Form submission
    const form = document.getElementById('maintenanceRequestForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Submitting...';
    });

    // Initialize Lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});
</script>
@endpush
