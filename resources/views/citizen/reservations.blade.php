@extends('citizen.layouts.app-sidebar')

@section('title', 'Make a Reservation - LGU1 Citizen Portal')
@section('page-title', 'New Reservation')
@section('page-description', 'Book a facility for your event')

@section('content')
<div class="space-y-6">
    <div class="flex justify-end">
        <div class="flex items-center text-sm text-blue-600 bg-blue-50 px-3 py-2 rounded-lg">
            <i class="fas fa-info-circle mr-1"></i>
            Reservations require approval
        </div>
    </div>


    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div id="progressStep1" class="flex items-center">
                <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-medium">
                    1
                </div>
                <span class="ml-2 text-sm font-medium text-gray-900">Facility & Details</span>
            </div>
            
            <div id="progressLine1" class="flex-1 mx-4 h-0.5 bg-gray-300"></div>
            
            <div id="progressStep2" class="flex items-center">
                <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">
                    2
                </div>
                <span class="ml-2 text-sm font-medium text-gray-600">Documents & Sign</span>
            </div>
            
            <div id="progressLine2" class="flex-1 mx-4 h-0.5 bg-gray-300"></div>

            <div id="progressStep3" class="flex items-center">
                <div class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-medium">
                    3
                </div>
                <span class="ml-2 text-sm font-medium text-gray-600">Review & Submit</span>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <form id="reservationForm" method="POST" action="{{ route('citizen.reservations.store') }}" enctype="multipart/form-data">
            @csrf

            <div id="step1" class="space-y-6">
                <h2 class="text-2xl font-bold text-gray-900 border-b pb-3">Step 1: Facility & Event Details</h2>
                
                <div>
                    <label for="facility_id" class="block text-sm font-medium text-gray-700 mb-2">Facility <span class="text-red-500">*</span></label>
                    <select id="facility_id" name="facility_id" required onchange="checkFacilityAvailability(this.value)"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('facility_id') border-red-500 @enderror">
                        <option value="" disabled selected>Select a facility</option>
                        {{-- Assume $facilities is passed from the controller --}}
                        @foreach($facilities as $facility)
                            <option value="{{ $facility->id }}" data-fee="{{ $facility->fee ?? 0 }}" {{ old('facility_id') == $facility->id ? 'selected' : '' }}>
                                {{ $facility->name }} (Fee: ₱{{ number_format($facility->fee ?? 0, 2) }}/hr)
                            </option>
                        @endforeach
                    </select>
                    @error('facility_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="event_title" class="block text-sm font-medium text-gray-700 mb-2">Event Title <span class="text-red-500">*</span></label>
                    <input type="text" id="event_title" name="event_title" value="{{ old('event_title') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('event_title') border-red-500 @enderror"
                           placeholder="e.g., Barangay Assembly, Community Sportsfest">
                    @error('event_title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Start Date & Time <span class="text-red-500">*</span></label>
                        <input type="datetime-local" id="start_time" name="start_time" value="{{ old('start_time') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('start_time') border-red-500 @enderror">
                        @error('start_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">End Date & Time <span class="text-red-500">*</span></label>
                        <input type="datetime-local" id="end_time" name="end_time" value="{{ old('end_time') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('end_time') border-red-500 @enderror">
                        @error('end_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div id="availability-message" class="mt-4 p-3 rounded-lg text-sm hidden"></div>

                <div class="flex justify-end border-t pt-4">
                    <button type="button" onclick="nextStep(1)" id="nextStep1Btn" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                        Next: Documents <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>

            <div id="step2" class="space-y-6 hidden">
                <h2 class="text-2xl font-bold text-gray-900 border-b pb-3">Step 2: Documents & Signature</h2>
                
                <p class="text-sm text-red-500">Note: Selfie with ID is required for first-time bookers or if your ID verification status is 'pending'.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Required Documents</h3>
                        
                        <div class="mb-4">
                            <label for="valid_id" class="block text-sm font-medium text-gray-700 mb-2">Valid ID (Front & Back) <span class="text-red-500">*</span></label>
                            <input type="file" id="valid_id" name="valid_id" accept="image/*,application/pdf" required onchange="previewFile('valid_id', 'valid_id_preview')"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @error('valid_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <div id="valid_id_preview" class="mt-2 text-sm text-gray-600"></div>
                        </div>

                        <div class="mb-4">
                            <label for="id_selfie" class="block text-sm font-medium text-gray-700 mb-2">Selfie with ID <span class="text-red-500">*</span></label>
                            <input type="file" id="id_selfie" name="id_selfie" accept="image/*" required onchange="previewFile('id_selfie', 'id_selfie_preview')"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            @error('id_selfie')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <div id="id_selfie_preview" class="mt-2 text-sm text-gray-600"></div>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Other Documents (Optional/Conditional)</h3>
                        
                        <div class="mb-4">
                            <label for="authorization_letter" class="block text-sm font-medium text-gray-700 mb-2">Authorization Letter (If applicable)</label>
                            <input type="file" id="authorization_letter" name="authorization_letter" accept="image/*,application/pdf" onchange="previewFile('authorization_letter', 'authorization_letter_preview')"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                            <p class="mt-1 text-sm text-gray-500">Required if reserving on behalf of an organization/individual.</p>
                            <div id="authorization_letter_preview" class="mt-2 text-sm text-gray-600"></div>
                        </div>

                        <div class="mb-4">
                            <label for="event_proposal" class="block text-sm font-medium text-gray-700 mb-2">Event Proposal (Optional)</label>
                            <input type="file" id="event_proposal" name="event_proposal" accept="image/*,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" onchange="previewFile('event_proposal', 'event_proposal_preview')"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                            <p class="mt-1 text-sm text-gray-500">Highly recommended for complex/large-scale events.</p>
                            <div id="event_proposal_preview" class="mt-2 text-sm text-gray-600"></div>
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">Digital Signature <span class="text-red-500">*</span></h3>
                    
                    <div class="flex items-center space-x-4 mb-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="signature_method" value="draw" checked onchange="toggleSignatureMethod('draw')" class="form-radio text-blue-600">
                            <span class="ml-2 text-gray-700">Draw Signature</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="signature_method" value="upload" onchange="toggleSignatureMethod('upload')" class="form-radio text-blue-600">
                            <span class="ml-2 text-gray-700">Upload Signature Image</span>
                        </label>
                    </div>

                    <input type="hidden" id="signature_data" name="signature_data">
                    
                    <div id="signature_draw_section">
                        <canvas id="signatureCanvas" class="border border-gray-300 rounded-lg bg-gray-50 w-full" style="height: 150px;"></canvas>
                        <div class="mt-2 flex space-x-2">
                            <button type="button" onclick="clearSignature()" class="px-4 py-2 bg-red-100 text-red-700 text-sm font-medium rounded-lg hover:bg-red-200">
                                <i class="fas fa-eraser mr-1"></i> Clear
                            </button>
                            <p class="text-sm text-gray-500 py-2">Sign above using your mouse or finger.</p>
                        </div>
                    </div>
                    
                    <div id="signature_upload_section" class="hidden">
                        <input type="file" id="signature_upload" name="signature_upload" accept="image/*" onchange="previewFile('signature_upload', 'signature_upload_preview')"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200">
                        <div id="signature_upload_preview" class="mt-2 text-sm text-gray-600"></div>
                    </div>
                </div>

                <div class="flex justify-between border-t pt-4">
                    <button type="button" onclick="prevStep(2)" class="px-6 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Previous
                    </button>
                    <button type="button" onclick="nextStep(2)" id="nextStep2Btn" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                        Next: Review <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>

            <div id="step3" class="space-y-6 hidden">
                <h2 class="text-2xl font-bold text-gray-900 border-b pb-3">Step 3: Review and Submit</h2>
                
                <div id="reviewContent" class="p-4 border border-gray-200 rounded-lg bg-gray-50 space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Reservation Summary</h3>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" id="terms_conditions" name="terms_conditions" required class="form-checkbox text-blue-600 h-5 w-5 rounded">
                    <label for="terms_conditions" class="ml-2 text-sm text-gray-700">
                        I agree to the <a href="{{ route('citizen.terms') }}" target="_blank" class="text-blue-600 hover:text-blue-800 font-medium">Terms and Conditions</a> and all LGU rules and regulations. <span class="text-red-500">*</span>
                    </label>
                </div>
                @error('terms_conditions')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                
                <div class="flex justify-between border-t pt-4">
                    <button type="button" onclick="prevStep(3)" class="px-6 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i> Previous
                    </button>
                    <button type="submit" id="submitBtn" class="px-6 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors shadow-lg">
                        <i class="fas fa-paper-plane mr-2"></i> Submit Reservation
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// --- GLOBAL VARIABLES ---
const steps = [document.getElementById('step1'), document.getElementById('step2'), document.getElementById('step3')];
const progressSteps = [document.getElementById('progressStep1'), document.getElementById('progressStep2'), document.getElementById('progressStep3')];
const progressLines = [document.getElementById('progressLine1'), document.getElementById('progressLine2')];
let currentStep = 1;
let signatureCanvas, signatureContext;
let isDrawing = false;
let hasSignature = false;

// --- INITIALIZATION ---
document.addEventListener('DOMContentLoaded', () => {
    // Initialize Signature Canvas
    signatureCanvas = document.getElementById('signatureCanvas');
    if (signatureCanvas) {
        // Set canvas width to parent width
        signatureCanvas.width = signatureCanvas.offsetWidth; 
        signatureCanvas.height = 150; // Fixed height
        signatureContext = signatureCanvas.getContext('2d');
        signatureContext.lineWidth = 2;
        signatureContext.lineCap = 'round';
        signatureContext.strokeStyle = '#000';

        // Event Listeners for Drawing
        signatureCanvas.addEventListener('mousedown', startDrawing);
        signatureCanvas.addEventListener('mousemove', draw);
        signatureCanvas.addEventListener('mouseup', stopDrawing);
        signatureCanvas.addEventListener('mouseleave', stopDrawing);
        
        // Touch events for mobile
        signatureCanvas.addEventListener('touchstart', handleTouch);
        signatureCanvas.addEventListener('touchmove', handleTouch);
        signatureCanvas.addEventListener('touchend', stopDrawing);
    }
    
    // Check for validation errors on page load and navigate to the correct step
    @if($errors->any())
        // Assuming validation errors mostly occur in the first step unless specified
        // A more robust solution would check which fields failed. For simplicity, we assume step 1 or 2.
        @if($errors->hasAny(['valid_id', 'id_selfie', 'authorization_letter', 'event_proposal', 'signature_data', 'signature_upload']))
            // Errors likely in Step 2, move to Step 2
            navigateToStep(2);
        @elseif($errors->hasAny(['event_title', 'start_time', 'end_time', 'facility_id']))
            // Errors in Step 1, stay on Step 1
            navigateToStep(1);
        @else
            navigateToStep(1);
        @endif
    @else
        navigateToStep(1);
    @endif
});

// --- NAVIGATION FUNCTIONS ---

/**
 * Validates the current step's required fields before moving next.
 * @param {number} step - The current step number.
 * @returns {boolean} - True if validation passes, false otherwise.
 */
function validateStep(step) {
    if (step === 1) {
        const facilityId = document.getElementById('facility_id').value;
        const eventTitle = document.getElementById('event_title').value;
        const startTime = document.getElementById('start_time').value;
        const endTime = document.getElementById('end_time').value;

        if (!facilityId || !eventTitle.trim() || !startTime || !endTime) {
            Swal.fire('Validation Error', 'Please fill in all required fields for Facility & Event Details.', 'error');
            return false;
        }

        const startDate = new Date(startTime);
        const endDate = new Date(endTime);
        if (endDate <= startDate) {
            Swal.fire('Time Error', 'End Date & Time must be later than Start Date & Time.', 'error');
            return false;
        }
        
        // Add more checks here, e.g., if a facility is available (if not checked via AJAX yet)
        // For simplicity, we assume this is handled server-side or via an onchange/onblur event.
        
        return true;

    } else if (step === 2) {
        const validId = document.getElementById('valid_id').files.length > 0;
        const idSelfie = document.getElementById('id_selfie').files.length > 0;
        const signatureMethod = document.querySelector('input[name="signature_method"]:checked')?.value;

        if (!validId || !idSelfie) {
             Swal.fire('Document Error', 'Valid ID and Selfie with ID are required for reservation.', 'error');
             return false;
        }
        
        if (!signatureMethod) {
            Swal.fire('Signature Error', 'Please select a signature method.', 'error');
            return false;
        }

        if (signatureMethod === 'draw') {
            if (isSignatureCanvasEmpty()) {
                Swal.fire('Signature Error', 'Please draw your signature on the canvas.', 'error');
                return false;
            }
            // Capture signature data before moving on
            document.getElementById('signature_data').value = signatureCanvas.toDataURL();
            document.getElementById('signature_upload').required = false;
        } else if (signatureMethod === 'upload') {
            const signatureUpload = document.getElementById('signature_upload').files.length > 0;
            if (!signatureUpload) {
                Swal.fire('Signature Error', 'Please upload your signature image.', 'error');
                return false;
            }
            document.getElementById('signature_data').value = 'UPLOADED'; // Flag for server-side
            document.getElementById('signature_upload').required = true;
        }
        
        return true;
    }
    return true; // Step 3 has minimal client-side validation
}

/**
 * Handles moving to the next step.
 * @param {number} current - The current step number.
 */
function nextStep(current) {
    if (!validateStep(current)) {
        return;
    }

    if (current < steps.length) {
        navigateToStep(current + 1);
        if (current + 1 === 3) {
            updateReviewContent();
        }
    }
}

/**
 * Handles moving to the previous step.
 * @param {number} current - The current step number.
 */
function prevStep(current) {
    if (current > 1) {
        navigateToStep(current - 1);
    }
}

/**
 * Navigates to a specific step.
 * @param {number} step - The step number to navigate to (1, 2, or 3).
 */
function navigateToStep(step) {
    currentStep = step;
    steps.forEach((s, index) => {
        if (index + 1 === step) {
            s.classList.remove('hidden');
        } else {
            s.classList.add('hidden');
        }
    });

    // Update progress indicator
    progressSteps.forEach((p, index) => {
        const stepNum = index + 1;
        const circle = p.querySelector('div');
        const text = p.querySelector('span');
        
        if (stepNum < step) {
            // Completed steps
            circle.classList.remove('bg-gray-300', 'text-gray-600');
            circle.classList.add('bg-green-600', 'text-white');
            text.classList.remove('text-gray-600');
            text.classList.add('text-green-600');
        } else if (stepNum === step) {
            // Current step
            circle.classList.remove('bg-gray-300', 'text-gray-600', 'bg-green-600');
            circle.classList.add('bg-blue-600', 'text-white');
            text.classList.remove('text-gray-600', 'text-green-600');
            text.classList.add('text-gray-900');
        } else {
            // Future steps
            circle.classList.remove('bg-blue-600', 'text-white', 'bg-green-600');
            circle.classList.add('bg-gray-300', 'text-gray-600');
            text.classList.remove('text-gray-900', 'text-green-600');
            text.classList.add('text-gray-600');
        }
    });
    
    progressLines.forEach((l, index) => {
        if (index + 1 < step) {
            l.classList.remove('bg-gray-300');
            l.classList.add('bg-green-600');
        } else {
            l.classList.remove('bg-green-600');
            l.classList.add('bg-gray-300');
        }
    });
}

// --- FILE UPLOAD PREVIEW ---

/**
 * Displays a preview of the selected file.
 * @param {string} inputId - ID of the file input element.
 * @param {string} previewId - ID of the element to display the preview.
 */
function previewFile(inputId, previewId) {
    const input = document.getElementById(inputId);
    const previewContainer = document.getElementById(previewId);
    
    // Clear previous content
    previewContainer.innerHTML = ''; 

    if (input.files.length > 0) {
        const file = input.files[0];
        const fileSize = (file.size / 1024).toFixed(2); // in KB
        
        const fileInfo = document.createElement('p');
        fileInfo.className = 'text-xs mt-1 text-blue-600';
        fileInfo.textContent = `File Selected: ${file.name} (${fileSize} KB)`;
        previewContainer.appendChild(fileInfo);
        
        // Optional: Add image preview for image files
        if (file.type.startsWith('image/') && inputId !== 'signature_upload') {
            const img = document.createElement('img');
            img.className = 'mt-2 max-h-32 rounded-lg border border-gray-200';
            img.src = URL.createObjectURL(file);
            previewContainer.appendChild(img);
        } else if (inputId === 'signature_upload') {
             const img = document.createElement('img');
            img.className = 'mt-2 max-h-24 rounded-lg border border-gray-200';
            img.src = URL.createObjectURL(file);
            previewContainer.appendChild(img);
        }
    }
}

/**
 * Removes the selected file from the input and clears the preview.
 */
function removeFile(inputId, previewId) {
    const input = document.getElementById(inputId);
    const previewContainer = document.getElementById(previewId);
    
    input.value = ''; // Clear file input
    previewContainer.innerHTML = ''; // Clear preview
}


// --- SIGNATURE PAD LOGIC ---

function getMousePos(canvas, evt) {
    const rect = canvas.getBoundingClientRect();
    return {
        x: evt.clientX - rect.left,
        y: evt.clientY - rect.top
    };
}

function startDrawing(e) {
    e.preventDefault();
    isDrawing = true;
    const pos = getMousePos(signatureCanvas, e);
    signatureContext.beginPath();
    signatureContext.moveTo(pos.x, pos.y);
}

function draw(e) {
    if (!isDrawing) return;
    e.preventDefault();
    const pos = getMousePos(signatureCanvas, e);
    signatureContext.lineTo(pos.x, pos.y);
    signatureContext.stroke();
    hasSignature = true;
}

function stopDrawing() {
    if (isDrawing) {
        isDrawing = false;
        signatureContext.beginPath();
    }
}

function handleTouch(e) {
    e.preventDefault();
    const touch = e.touches[0];
    const mouseEvent = new MouseEvent(e.type === 'touchstart' ? 'mousedown' : 
                                     e.type === 'touchmove' ? 'mousemove' : 'mouseup', {
        clientX: touch.clientX,
        clientY: touch.clientY
    });
    signatureCanvas.dispatchEvent(mouseEvent);
}

function clearSignature() {
    if (signatureContext) {
        signatureContext.clearRect(0, 0, signatureCanvas.width, signatureCanvas.height);
        hasSignature = false;
    }
}

function isSignatureCanvasEmpty() {
    return !hasSignature;
}

function toggleSignatureMethod(method) {
    const drawSection = document.getElementById('signature_draw_section');
    const uploadSection = document.getElementById('signature_upload_section');
    
    if (method === 'draw') {
        drawSection.classList.remove('hidden');
        uploadSection.classList.add('hidden');
        // Clear upload if switching to draw
        document.getElementById('signature_upload').value = '';
        removeFile('signature_upload', 'signature_upload_preview');
        // Set draw as required
        document.getElementById('signature_data').required = true;
        document.getElementById('signature_upload').required = false;
    } else {
        drawSection.classList.add('hidden');
        uploadSection.classList.remove('hidden');
        // Clear canvas if switching to upload
        clearSignature();
        // Set upload as required
        document.getElementById('signature_upload').required = true;
        document.getElementById('signature_data').required = false;
    }
}

// --- REVIEW CONTENT GENERATION ---
function updateReviewContent() {
    const reviewContent = document.getElementById('reviewContent');
    const facilitySelect = document.getElementById('facility_id');
    const selectedFacility = facilitySelect.options[facilitySelect.selectedIndex].text;
    const eventTitle = document.getElementById('event_title').value;
    const startTime = new Date(document.getElementById('start_time').value);
    const endTime = new Date(document.getElementById('end_time').value);
    const signatureMethod = document.querySelector('input[name="signature_method"]:checked')?.value;
    
    // Formatting Dates and Times
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
    const formatTime = (date) => date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true });
    const formatDate = (date) => date.toLocaleDateString('en-US', options);
    
    // Get file names
    const getFileName = (id) => document.getElementById(id).files[0]?.name || 'Not provided';
    const getFileStatus = (id) => document.getElementById(id).files.length > 0 ? '✅ Uploaded' : '⚪ Not Uploaded';

    let signatureDisplay = 'Not selected';
    if (signatureMethod === 'draw') {
        signatureDisplay = document.getElementById('signature_data').value ? '✅ Provided (Digital Draw)' : '❌ Required';
    } else if (signatureMethod === 'upload') {
        signatureDisplay = getFileName('signature_upload') !== 'Not provided' ? `✅ Provided (Upload: ${getFileName('signature_upload')})` : '❌ Required';
    }
    
    reviewContent.innerHTML = `
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Reservation Summary</h3>
        
        <h4 class="font-medium text-gray-900 mb-2">Event Details</h4>
        <div class="text-sm space-y-1 mb-4">
            <div><span class="text-gray-600">Facility:</span> <span class="font-semibold text-blue-700">${selectedFacility}</span></div>
            <div><span class="text-gray-600">Event Title:</span> <span>${eventTitle}</span></div>
            <div><span class="text-gray-600">Start Time:</span> <span>${formatDate(startTime)} - ${formatTime(startTime)}</span></div>
            <div><span class="text-gray-600">End Time:</span> <span>${formatDate(endTime)} - ${formatTime(endTime)}</span></div>
        </div>
        
        <h4 class="font-medium text-gray-900 mb-2">Required Documents</h4>
        <div class="text-sm space-y-1 mb-4">
            <div>${getFileStatus('valid_id')}: Valid ID (Front & Back) - ${getFileName('valid_id')}</div>
            <div>${getFileStatus('id_selfie')}: Selfie with ID - ${getFileName('id_selfie')}</div>
        </div>
        
        <h4 class="font-medium text-gray-900 mb-2">Other Documents</h4>
        <div class="text-sm space-y-1 mb-4">
            <div>${getFileStatus('authorization_letter')}: Authorization Letter - ${getFileName('authorization_letter')}</div>
            <div>${getFileStatus('event_proposal')}: Event Proposal - ${getFileName('event_proposal')}</div>
        </div>
        
        <h4 class="font-medium text-gray-900 mb-2">Signature</h4>
        <div class="text-sm space-y-1">
            <div><span class="text-gray-600">Method:</span> ${signatureMethod === 'draw' ? 'Digital Drawing' : signatureMethod === 'upload' ? 'Image Upload' : 'Not selected'}</div>
            <div><span class="text-gray-600">Status:</span> ${signatureDisplay}</div>
        </div>
    `;
}

// --- AVAILABILITY CHECK (Simulated) ---
function checkFacilityAvailability(facilityId) {
    const startTime = document.getElementById('start_time').value;
    const endTime = document.getElementById('end_time').value;
    const messageEl = document.getElementById('availability-message');
    
    messageEl.classList.add('hidden');
    messageEl.classList.remove('bg-green-100', 'text-green-800', 'bg-red-100', 'text-red-800');
    messageEl.innerHTML = '';

    if (!facilityId || !startTime || !endTime) {
        return;
    }
    
    // Simple client-side check for demonstration (Actual check should be server-side)
    const startDate = new Date(startTime);
    const endDate = new Date(endTime);
    if (endDate <= startDate) {
        messageEl.textContent = '🛑 Time Slot Conflict: End time must be later than start time.';
        messageEl.classList.remove('hidden');
        messageEl.classList.add('bg-red-100', 'text-red-800');
        return;
    }

    // Simulate API call to check availability
    // Replace with actual fetch to your Laravel route for checking conflicts
    // fetch(`/citizen/api/check-availability?facility_id=${facilityId}&start_time=${startTime}&end_time=${endTime}`)
    // .then(response => response.json())
    // .then(data => {
    //     if (data.is_available) {
    //         messageEl.innerHTML = '<i class="fas fa-check-circle mr-1"></i> The facility is available for this time slot.';
    //         messageEl.classList.add('bg-green-100', 'text-green-800');
    //     } else {
    //         messageEl.innerHTML = `<i class="fas fa-times-circle mr-1"></i> Conflict: ${data.message || 'The facility is already booked for this time slot.'}`;
    //         messageEl.classList.add('bg-red-100', 'text-red-800');
    //     }
    //     messageEl.classList.remove('hidden');
    // });
    
    // Temporary client-side success message
    messageEl.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Time slot looks available. Final check is done upon submission.';
    messageEl.classList.add('bg-green-100', 'text-green-800');
    messageEl.classList.remove('hidden');

}

// Attach availability check to relevant inputs
document.getElementById('facility_id').addEventListener('change', () => checkFacilityAvailability(document.getElementById('facility_id').value));
document.getElementById('start_time').addEventListener('change', () => checkFacilityAvailability(document.getElementById('facility_id').value));
document.getElementById('end_time').addEventListener('change', () => checkFacilityAvailability(document.getElementById('facility_id').value));

</script>
@endpush
@endsection