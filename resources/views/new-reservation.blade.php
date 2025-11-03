@extends('layouts.app')

@section('title', 'New Reservation - LGU Facility Reservation System')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">New Facility Reservation</h1>
            <p class="text-gray-600">Reserve public facilities and equipment for your events</p>
            <p class="text-sm text-blue-600 mt-2">Based on South Caloocan City General Services Department Requirements</p>
        </div>

        <div class="mb-8">
            <div class="flex items-center justify-center space-x-4">
                <div class="flex items-center" id="progressStep1">
                    <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-medium">1</div>
                    <span class="ml-2 text-sm font-medium text-blue-600">Facility & Details</span>
                </div>
                <div class="w-16 h-1 bg-gray-200" id="progressLine1"></div>
                <div class="flex items-center" id="progressStep2">
                    <div class="w-8 h-8 bg-gray-400 text-white rounded-full flex items-center justify-center text-sm font-medium" id="step2Circle">2</div>
                    <span class="ml-2 text-sm font-medium text-gray-400" id="step2Text">Requirements</span>
                </div>
                <div class="w-16 h-1 bg-gray-200" id="progressLine2"></div>
                <div class="flex items-center" id="progressStep3">
                    <div class="w-8 h-8 bg-gray-400 text-white rounded-full flex items-center justify-center text-sm font-medium" id="step3Circle">3</div>
                    <span class="ml-2 text-sm font-medium text-gray-400" id="step3Text">Summary & Signature</span>
                </div>
            </div>
        </div>

        </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
/**
 * Updates the content of the summary step with the latest form data.
 * @returns {void}
 */
function updateSummaryContent() {
    // Collect data from form elements
    const facilityName = document.getElementById('facility_id').options[document.getElementById('facility_id').selectedIndex]?.text || 'Not selected';
    const eventDate = document.getElementById('event_date')?.value || 'Not set';
    const startTime = document.getElementById('start_time')?.value || 'Not set';
    const endTime = document.getElementById('end_time')?.value || 'Not set';
    const purpose = document.getElementById('purpose')?.value || 'Not provided';
    
    // Helper function to check if a file input has a file
    const getFileName = (id, defaultText) => document.getElementById(id)?.files[0]?.name || defaultText;
    const isFileUploaded = (id) => !!document.getElementById(id)?.files[0];
    
    // Get signature method
    const signatureMethod = document.querySelector('input[name="signature_method"]:checked')?.value;
    let signatureMethodText = 'Not selected';
    if (signatureMethod === 'draw') {
        signatureMethodText = 'Digital Drawing';
    } else if (signatureMethod === 'upload') {
        signatureMethodText = 'Image Upload';
    }
    
    // Check if signature data is present (for drawing or upload)
    const signatureProvided = !!document.getElementById('signature_data')?.value;
    
    // Construct the HTML for the summary view
    document.getElementById('summary-content').innerHTML = `
        <h3 class="text-xl font-bold text-gray-900 mb-4">Reservation Summary</h3>
        
        <h4 class="font-medium text-gray-900 mb-2">Booking Details</h4>
        <div class="text-sm space-y-1 mb-4 border p-3 rounded-lg bg-gray-50">
            <div><span class="text-gray-600">Facility:</span> **${facilityName}**</div>
            <div><span class="text-gray-600">Date:</span> **${eventDate}**</div>
            <div><span class="text-gray-600">Time:</span> **${startTime}** - **${endTime}**</div>
            <div><span class="text-gray-600">Purpose:</span> ${purpose}</div>
        </div>
        
        <h4 class="font-medium text-gray-900 mb-2">Required Documents</h4>
        <div class="text-sm space-y-1 mb-4 border p-3 rounded-lg bg-gray-50">
            <div>${isFileUploaded('valid_id') ? '✅' : '❌'} Valid ID: ${getFileName('valid_id', 'Required - Not uploaded')}</div>
            <div>${isFileUploaded('id_selfie') ? '✅' : '❌'} Selfie with ID: ${getFileName('id_selfie', 'Required - Not uploaded')}</div>
        </div>
        
        <h4 class="font-medium text-gray-900 mb-2">Supporting Documents</h4>
        <div class="text-sm space-y-1 mb-4 border p-3 rounded-lg bg-gray-50">
            <div>${isFileUploaded('authorization_letter') ? '✅' : '⚪'} Authorization Letter: ${getFileName('authorization_letter', 'Optional - Not provided')}</div>
            <div>${isFileUploaded('event_proposal') ? '✅' : '⚪'} Event Proposal: ${getFileName('event_proposal', 'Optional - Not provided')}</div>
        </div>
        
        <h4 class="font-medium text-gray-900 mb-2">Signature</h4>
        <div class="text-sm space-y-1 border p-3 rounded-lg bg-gray-50">
            <div><span class="text-gray-600">Method:</span> ${signatureMethodText}</div>
            <div>${signatureProvided ? '✅ Provided' : '❌ Required'}</div>
        </div>
    `;
}
</script>
@endpush@extends('layouts.app')

@section('title', 'New Reservation - LGU Facility Reservation System')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">New Facility Reservation</h1>
            <p class="text-gray-600">Reserve public facilities and equipment for your events</p>
            <p class="text-sm text-blue-600 mt-2">Based on South Caloocan City General Services Department Requirements</p>
        </div>

        <div class="mb-8">
            <div class="flex items-center justify-center space-x-4">
                <div class="flex items-center" id="progressStep1">
                    <div class="w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-medium">1</div>
                    <span class="ml-2 text-sm font-medium text-blue-600">Facility & Details</span>
                </div>
                <div class="w-16 h-1 bg-gray-200" id="progressLine1"></div>
                <div class="flex items-center" id="progressStep2">
                    <div class="w-8 h-8 bg-gray-400 text-white rounded-full flex items-center justify-center text-sm font-medium" id="step2Circle">2</div>
                    <span class="ml-2 text-sm font-medium text-gray-400" id="step2Text">Requirements</span>
                </div>
                <div class="w-16 h-1 bg-gray-200" id="progressLine2"></div>
                <div class="flex items-center" id="progressStep3">
                    <div class="w-8 h-8 bg-gray-400 text-white rounded-full flex items-center justify-center text-sm font-medium" id="step3Circle">3</div>
                    <span class="ml-2 text-sm font-medium text-gray-400" id="step3Text">Summary & Signature</span>
                </div>
            </div>
        </div>

        </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
/**
 * Updates the content of the summary step with the latest form data.
 * @returns {void}
 */
function updateSummaryContent() {
    // Collect data from form elements
    const facilityName = document.getElementById('facility_id').options[document.getElementById('facility_id').selectedIndex]?.text || 'Not selected';
    const eventDate = document.getElementById('event_date')?.value || 'Not set';
    const startTime = document.getElementById('start_time')?.value || 'Not set';
    const endTime = document.getElementById('end_time')?.value || 'Not set';
    const purpose = document.getElementById('purpose')?.value || 'Not provided';
    
    // Helper function to check if a file input has a file
    const getFileName = (id, defaultText) => document.getElementById(id)?.files[0]?.name || defaultText;
    const isFileUploaded = (id) => !!document.getElementById(id)?.files[0];
    
    // Get signature method
    const signatureMethod = document.querySelector('input[name="signature_method"]:checked')?.value;
    let signatureMethodText = 'Not selected';
    if (signatureMethod === 'draw') {
        signatureMethodText = 'Digital Drawing';
    } else if (signatureMethod === 'upload') {
        signatureMethodText = 'Image Upload';
    }
    
    // Check if signature data is present (for drawing or upload)
    const signatureProvided = !!document.getElementById('signature_data')?.value;
    
    // Construct the HTML for the summary view
    document.getElementById('summary-content').innerHTML = `
        <h3 class="text-xl font-bold text-gray-900 mb-4">Reservation Summary</h3>
        
        <h4 class="font-medium text-gray-900 mb-2">Booking Details</h4>
        <div class="text-sm space-y-1 mb-4 border p-3 rounded-lg bg-gray-50">
            <div><span class="text-gray-600">Facility:</span> **${facilityName}**</div>
            <div><span class="text-gray-600">Date:</span> **${eventDate}**</div>
            <div><span class="text-gray-600">Time:</span> **${startTime}** - **${endTime}**</div>
            <div><span class="text-gray-600">Purpose:</span> ${purpose}</div>
        </div>
        
        <h4 class="font-medium text-gray-900 mb-2">Required Documents</h4>
        <div class="text-sm space-y-1 mb-4 border p-3 rounded-lg bg-gray-50">
            <div>${isFileUploaded('valid_id') ? '✅' : '❌'} Valid ID: ${getFileName('valid_id', 'Required - Not uploaded')}</div>
            <div>${isFileUploaded('id_selfie') ? '✅' : '❌'} Selfie with ID: ${getFileName('id_selfie', 'Required - Not uploaded')}</div>
        </div>
        
        <h4 class="font-medium text-gray-900 mb-2">Supporting Documents</h4>
        <div class="text-sm space-y-1 mb-4 border p-3 rounded-lg bg-gray-50">
            <div>${isFileUploaded('authorization_letter') ? '✅' : '⚪'} Authorization Letter: ${getFileName('authorization_letter', 'Optional - Not provided')}</div>
            <div>${isFileUploaded('event_proposal') ? '✅' : '⚪'} Event Proposal: ${getFileName('event_proposal', 'Optional - Not provided')}</div>
        </div>
        
        <h4 class="font-medium text-gray-900 mb-2">Signature</h4>
        <div class="text-sm space-y-1 border p-3 rounded-lg bg-gray-50">
            <div><span class="text-gray-600">Method:</span> ${signatureMethodText}</div>
            <div>${signatureProvided ? '✅ Provided' : '❌ Required'}</div>
        </div>
    `;
}
</script>
@endpush