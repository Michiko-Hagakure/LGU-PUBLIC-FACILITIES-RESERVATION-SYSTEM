@extends('layouts.app')

@section('content')
<div class="mb-6">
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
                    <div class="w-16 h-16 bg-lgu-highlight/20 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/10">
                        <svg class="w-8 h-8 text-lgu-highlight" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm3 1h6v4H7V5zm6 6H7v2h6v-2z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold mb-1 text-white">Facility List</h1>
                        <p class="text-gray-200 text-lg">Manage all LGU facilities here</p>
                    </div>
                </div>
            </div>
            <div class="text-right space-y-3">
                <button id="addFacilityBtn"
                    class="inline-flex items-center px-6 py-3 bg-lgu-highlight text-lgu-button-text font-semibold rounded-lg hover:opacity-90 transition-all shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Facility
                </button>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Facility Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Capacity</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Facility Rate</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @if ($facilities->isEmpty())
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                        No facilities found.
                    </td>
                </tr>
            @else
                @foreach($facilities as $facility)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-800 font-medium">{{ $facility->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $facility->location }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $facility->capacity }}</td>
                        {{-- Assuming 'daily_rate' is the base rate mentioned in the modal (Base Rate 3 hours) --}}
                        <td class="px-6 py-4 text-sm text-gray-600">₱{{ number_format($facility->daily_rate, 2) }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{-- Edit button storing all necessary data attributes --}}
                            <button class="edit-btn px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow hover:bg-indigo-700 transition"
                                data-id="{{ $facility->facility_id }}"
                                data-name="{{ $facility->name }}"
                                data-description="{{ $facility->description }}"
                                data-location="{{ $facility->location }}"
                                data-capacity="{{ $facility->capacity }}"
                                data-hourly-rate="{{ $facility->hourly_rate }}"
                                data-base-rate="{{ $facility->daily_rate }}" {{-- Renamed to base-rate for clarity with modal --}}
                                data-facility-type="{{ $facility->facility_type }}"
                                data-image-path="{{ $facility->image_path }}">
                                Edit
                            </button>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>

{{-- Add Facility Modal --}}
<div id="addFacilityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden transition-all ease-in-out duration-300">
    {{-- ... (Add Modal Content remains the same) ... --}}
    <div id="modalAddContent" class="relative top-20 mx-auto p-8 border max-w-xl shadow-lg rounded-md bg-white transition-all ease-in-out duration-300 transform scale-95 opacity-0">
        <h3 class="text-2xl font-bold text-gray-900 mb-6">Add New Facility</h3>

        <form action="{{ route('facilities.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-6">
                <div>
                    <label for="facility_image" class="block text-sm font-medium text-gray-700 mb-2">Facility Image <span class="text-red-500">*</span></label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center bg-gray-50">
                        <input type="file" name="facility_image" id="facility_image" accept="image/*" required class="hidden">
                        <label for="facility_image" class="cursor-pointer">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="mt-4">
                                <p class="text-sm text-gray-600">Click to upload facility image</p>
                                <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 5MB</p>
                            </div>
                        </label>
                    </div>
                    <div id="imagePreview" class="mt-4 hidden">
                        <img id="previewImg" src="" alt="Preview" class="w-full h-32 object-cover rounded-lg">
                        <button type="button" onclick="clearImagePreview()" class="mt-2 text-red-600 text-sm hover:underline">Remove Image</button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Facility Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lgu-headline focus:ring focus:ring-lgu-headline focus:ring-opacity-50">
                    </div>

                    <div>
                        <label for="capacity" class="block text-sm font-medium text-gray-700">Maximum Capacity <span class="text-red-500">*</span></label>
                        <input type="number" name="capacity" id="capacity" required min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lgu-headline focus:ring focus:ring-lgu-headline focus:ring-opacity-50">
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description <span class="text-red-500">*</span></label>
                    <textarea name="description" id="description" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lgu-headline focus:ring focus:ring-lgu-headline focus:ring-opacity-50"></textarea>
                </div>

                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700">Location/Address <span class="text-red-500">*</span></label>
                    <input type="text" name="location" id="location" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lgu-headline focus:ring focus:ring-lgu-headline focus:ring-opacity-50">
                </div>

                <div class="bg-blue-50 p-4 rounded-lg">
                    <h4 class="text-sm font-semibold text-blue-800 mb-3">Pricing Structure (Based on LGU Interview)</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="base_rate" class="block text-sm font-medium text-gray-700">Base Rate (3 hours) <span class="text-red-500">*</span></label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₱</span>
                                </div>
                                {{-- Renamed from 'base_rate' to 'daily_rate' in input name for consistency with database column --}}
                                <input type="number" name="daily_rate" id="base_rate" required min="5000" value="5000" class="block w-full pl-7 pr-12 border-gray-300 rounded-md focus:ring-lgu-headline focus:border-lgu-headline">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Minimum ₱5,000 for 3 hours</p>
                        </div>

                        <div>
                            <label for="hourly_rate" class="block text-sm font-medium text-gray-700">Extension Rate (per hour) <span class="text-red-500">*</span></label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₱</span>
                                </div>
                                <input type="number" name="hourly_rate" id="hourly_rate" required min="2000" value="2000" class="block w-full pl-7 pr-12 border-gray-300 rounded-md focus:ring-lgu-headline focus:border-lgu-headline">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">₱2,000 for each hour beyond 3 hours</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="facility_type" class="block text-sm font-medium text-gray-700">Facility Type <span class="text-red-500">*</span></label>
                    <select name="facility_type" id="facility_type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lgu-headline focus:ring focus:ring-lgu-headline focus:ring-opacity-50">
                        <option value="">Select Type</option>
                        <option value="outdoor">Outdoor Venue</option>
                        <option value="indoor">Indoor Hall</option>
                        <option value="sports">Sports Facility</option>
                        <option value="conference">Conference Room</option>
                        <option value="multipurpose">Multi-purpose Hall</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <button type="button" id="closeAddModalBtn" class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-lgu-highlight text-lgu-button-text font-semibold rounded-lg shadow hover:bg-yellow-400 transition">Save</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Facility Modal --}}
<div id="editFacilityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden transition-all ease-in-out duration-300">
    {{-- ... (Edit Modal Content remains the same) ... --}}
    <div id="modalEditContent" class="relative top-20 mx-auto p-8 border max-w-xl shadow-lg rounded-md bg-white transition-all ease-in-out duration-300 transform scale-95 opacity-0">
        <h3 class="text-2xl font-bold text-gray-900 mb-6">Edit Facility</h3>

        <form id="editForm" action="#" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label for="edit_facility_image" class="block text-sm font-medium text-gray-700 mb-2">Facility Image</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center bg-gray-50">
                        <input type="file" name="facility_image" id="edit_facility_image" accept="image/*" class="hidden">
                        <label for="edit_facility_image" class="cursor-pointer">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="mt-4">
                                <p class="text-sm text-gray-600">Click to upload new facility image</p>
                                <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 5MB</p>
                            </div>
                        </label>
                    </div>
                    <div id="editImagePreview" class="mt-4 hidden">
                        <img id="editPreviewImg" src="" alt="Preview" class="w-full h-32 object-cover rounded-lg">
                        <button type="button" onclick="clearEditImagePreview()" class="mt-2 text-red-600 text-sm hover:underline">Remove Image</button>
                    </div>
                    <div id="currentImage" class="mt-4 hidden">
                        <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                        <img id="currentImageDisplay" src="" alt="Current Image" class="w-full h-32 object-cover rounded-lg">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="edit_name" class="block text-sm font-medium text-gray-700">Facility Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="edit_name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lgu-headline focus:ring focus:ring-lgu-headline focus:ring-opacity-50">
                    </div>

                    <div>
                        <label for="edit_capacity" class="block text-sm font-medium text-gray-700">Maximum Capacity <span class="text-red-500">*</span></label>
                        <input type="number" name="capacity" id="edit_capacity" required min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lgu-headline focus:ring focus:ring-lgu-headline focus:ring-opacity-50">
                    </div>
                </div>

                <div>
                    <label for="edit_description" class="block text-sm font-medium text-gray-700">Description <span class="text-red-500">*</span></label>
                    <textarea name="description" id="edit_description" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lgu-headline focus:ring focus:ring-lgu-headline focus:ring-opacity-50"></textarea>
                </div>

                <div>
                    <label for="edit_location" class="block text-sm font-medium text-gray-700">Location/Address <span class="text-red-500">*</span></label>
                    <input type="text" name="location" id="edit_location" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lgu-headline focus:ring focus:ring-lgu-headline focus:ring-opacity-50">
                </div>

                <div class="bg-blue-50 p-4 rounded-lg">
                    <h4 class="text-sm font-semibold text-blue-800 mb-3">Pricing Structure (Based on LGU Interview)</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_base_rate" class="block text-sm font-medium text-gray-700">Base Rate (3 hours) <span class="text-red-500">*</span></label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₱</span>
                                </div>
                                {{-- Renamed from 'base_rate' to 'daily_rate' in input name for consistency with database column --}}
                                <input type="number" name="daily_rate" id="edit_base_rate" required min="5000" value="5000" class="block w-full pl-7 pr-12 border-gray-300 rounded-md focus:ring-lgu-headline focus:border-lgu-headline">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Minimum ₱5,000 for 3 hours</p>
                        </div>

                        <div>
                            <label for="edit_hourly_rate" class="block text-sm font-medium text-gray-700">Extension Rate (per hour) <span class="text-red-500">*</span></label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₱</span>
                                </div>
                                <input type="number" name="hourly_rate" id="edit_hourly_rate" required min="2000" value="2000" class="block w-full pl-7 pr-12 border-gray-300 rounded-md focus:ring-lgu-headline focus:border-lgu-headline">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">₱2,000 for each hour beyond 3 hours</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="edit_facility_type" class="block text-sm font-medium text-gray-700">Facility Type <span class="text-red-500">*</span></label>
                    <select name="facility_type" id="edit_facility_type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lgu-headline focus:ring focus:ring-lgu-headline focus:ring-opacity-50">
                        <option value="">Select Type</option>
                        <option value="outdoor">Outdoor Venue</option>
                        <option value="indoor">Indoor Hall</option>
                        <option value="sports">Sports Facility</option>
                        <option value="conference">Conference Room</option>
                        <option value="multipurpose">Multi-purpose Hall</option>
                    </select>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <button type="button" id="closeEditModalBtn" class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-lgu-highlight text-lgu-button-text font-semibold rounded-lg shadow hover:bg-yellow-400 transition">Update</button>
            </div>
        </form>
    </div>
</div>

{{-- Main Modal Logic --}}
<script>
    /** Modal Elements */
    const addFacilityBtn = document.getElementById('addFacilityBtn');
    const addFacilityModal = document.getElementById('addFacilityModal');
    const closeAddModalBtn = document.getElementById('closeAddModalBtn');
    const modalAddContent = document.getElementById('modalAddContent');

    const editButtons = document.querySelectorAll('.edit-btn');
    const editFacilityModal = document.getElementById('editFacilityModal');
    const closeEditModalBtn = document.getElementById('closeEditModalBtn');
    const modalEditContent = document.getElementById('modalEditContent');
    const editForm = document.getElementById('editForm');

    /**
     * Toggles the visibility of a modal with transition effects.
     * @param {HTMLElement} modal - The main modal container.
     * @param {HTMLElement} content - The modal content element.
     * @param {boolean} show - True to show, false to hide.
     */
    function toggleModal(modal, content, show) {
        if (show) {
            modal.classList.remove('hidden');
            // Timeout to allow DOM render before starting transition
            setTimeout(() => {
                modal.classList.add('bg-opacity-50');
                content.classList.remove('opacity-0', 'scale-95');
            }, 10);
        } else {
            content.classList.add('opacity-0', 'scale-95');
            modal.classList.remove('bg-opacity-50');
            // Timeout to wait for transition end before hiding
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    }

    // --- Modal Event Listeners ---
    addFacilityBtn.addEventListener('click', () => toggleModal(addFacilityModal, modalAddContent, true));
    closeAddModalBtn.addEventListener('click', () => toggleModal(addFacilityModal, modalAddContent, false));

    // Edit Modal Listener
    editButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            const data = event.currentTarget.dataset; // Use currentTarget for better reliability
            
            // Set form values
            document.getElementById('edit_name').value = data.name || '';
            document.getElementById('edit_description').value = data.description || '';
            document.getElementById('edit_location').value = data.location || '';
            document.getElementById('edit_capacity').value = data.capacity || '';
            // Use the data-base-rate and data-hourly-rate attributes
            document.getElementById('edit_base_rate').value = data.baseRate || 5000;
            document.getElementById('edit_hourly_rate').value = data.hourlyRate || 2000;
            document.getElementById('edit_facility_type').value = data.facilityType || 'multipurpose';

            // Clear any previous image previews and reset file input
            clearEditImagePreview();
            
            // Handle current image display
            const imagePath = data.imagePath;
            const currentImageDiv = document.getElementById('currentImage');
            const currentImageDisplay = document.getElementById('currentImageDisplay');
            
            if (imagePath && imagePath !== 'null' && imagePath !== '') {
                currentImageDisplay.src = `/storage/${imagePath}`;
                currentImageDiv.classList.remove('hidden');
            } else {
                currentImageDiv.classList.add('hidden');
            }

            // Set the form action URL
            editForm.action = `/admin/facilities/${data.id}`;

            // Show modal
            toggleModal(editFacilityModal, modalEditContent, true);
        });
    });

    closeEditModalBtn.addEventListener('click', () => toggleModal(editFacilityModal, modalEditContent, false));

    // --- Image Preview Functions ---

    /**
     * Handles the file change event, validates the image, and shows a preview.
     * @param {Event} e - The change event from the file input.
     * @param {string} previewImgId - ID of the image element for preview.
     * @param {string} previewDivId - ID of the div container for preview.
     * @param {string} [currentImageDivId] - Optional ID of the current image div to hide.
     * @param {number} maxSizeMB - Maximum file size in megabytes.
     */
    function handleImageChange(e, previewImgId, previewDivId, currentImageDivId, maxSizeMB = 5) {
        const fileInput = e.target;
        const file = fileInput.files[0];
        
        if (file) {
            const maxSize = maxSizeMB * 1024 * 1024;
            // Validate file size
            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: `File size must be less than ${maxSizeMB}MB.`,
                });
                fileInput.value = ''; // Clear file input
                return;
            }

            // Validate file type
            if (!file.type.match(/^image\/(jpg|jpeg|png)$/)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File Type',
                    text: 'Only JPG, JPEG, and PNG files are allowed.',
                });
                fileInput.value = '';
                return;
            }

            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(previewImgId).src = e.target.result;
                document.getElementById(previewDivId).classList.remove('hidden');
                if (currentImageDivId) {
                    document.getElementById(currentImageDivId).classList.add('hidden'); // Hide current image when new one is selected
                }
            };
            reader.readAsDataURL(file);
        }
    }

    /** Clears the image preview for the Add Facility modal. */
    function clearImagePreview() {
        document.getElementById('facility_image').value = '';
        document.getElementById('imagePreview').classList.add('hidden');
        document.getElementById('previewImg').src = '';
    }

    /** Clears the image preview for the Edit Facility modal. */
    function clearEditImagePreview() {
        document.getElementById('edit_facility_image').value = '';
        document.getElementById('editImagePreview').classList.add('hidden');
        document.getElementById('editPreviewImg').src = '';
        // Optionally show current image again if file input is cleared
        if (document.getElementById('currentImageDisplay').src) {
             document.getElementById('currentImage').classList.remove('hidden');
        }
    }

    // Attach image change listeners using the refactored handler
    document.getElementById('facility_image').addEventListener('change', (e) => handleImageChange(e, 'previewImg', 'imagePreview', null));
    document.getElementById('edit_facility_image').addEventListener('change', (e) => handleImageChange(e, 'editPreviewImg', 'editImagePreview', 'currentImage'));
</script>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Session Success Notification ---
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                // Simple page reload to ensure list is updated
                window.location.href = window.location.pathname;
            });
        @endif

        // --- Session Edit Focus/Modal Opener ---
        @if(session('edit_facility'))
            const facilityId = '{{ session('edit_facility') }}';
            const editButton = document.querySelector(`.edit-btn[data-id="${facilityId}"]`);

            if (editButton) {
                // Automatically open the edit modal for the newly edited facility
                // Use a short delay to ensure all DOM manipulation is complete before clicking.
                setTimeout(() => {
                    editButton.click();
                }, 100);
            }
        @endif
    });
</script>
@endpush