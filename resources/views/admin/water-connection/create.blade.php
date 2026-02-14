@extends('layouts.admin')

@section('page-title', 'Request Water Connection')
@section('page-subtitle', 'Submit a new water connection request to Utility Billing & Management')

@push('styles')
<style>
    .form-input {
        width: 100%;
        padding: 0.625rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        transition: all 0.2s;
    }
    .form-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.25rem;
    }
    .form-label .required {
        color: #ef4444;
    }
</style>
@endpush

@section('page-content')
<div class="max-w-4xl mx-auto space-y-gr-lg">

    {{-- Integration Info --}}
    <div class="p-gr-md bg-blue-50 border border-blue-200 rounded-xl">
        <div class="flex items-start gap-gr-sm">
            <i data-lucide="droplets" class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5"></i>
            <div>
                <p class="text-blue-800 font-semibold">Utility Billing & Management Integration</p>
                <p class="text-blue-700 text-small mt-1">Submit water connection and utility service requests to the Utility Billing system. Your request will be tracked and status updates will sync automatically.</p>
            </div>
        </div>
    </div>

    {{-- Request Form --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-cyan-600 px-gr-md py-gr-sm">
            <h3 class="text-white font-semibold flex items-center gap-2">
                <i data-lucide="file-plus" class="w-5 h-5"></i>
                New Service Request
            </h3>
        </div>

        <form action="{{ route('admin.water-connection.store') }}" method="POST" class="p-gr-md space-y-6">
            @csrf

            {{-- Service Details --}}
            <div class="border-b border-gray-200 pb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="settings" class="w-5 h-5 text-blue-600"></i>
                    Service Details
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="service_type" class="form-label">Service Type <span class="required">*</span></label>
                        <select name="service_type" id="service_type" required class="form-input">
                            <option value="">Select service type...</option>
                            @foreach($serviceTypes as $value => $label)
                                <option value="{{ $value }}" {{ old('service_type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('service_type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="property_type" class="form-label">Property Type <span class="required">*</span></label>
                        <select name="property_type" id="property_type" required class="form-input">
                            <option value="">Select property type...</option>
                            @foreach($propertyTypes as $value => $label)
                                <option value="{{ $value }}" {{ old('property_type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('property_type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Consumer Information --}}
            <div class="border-b border-gray-200 pb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="user" class="w-5 h-5 text-blue-600"></i>
                    Consumer Information
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="consumer_name" class="form-label">Consumer Name <span class="required">*</span></label>
                        <input type="text" name="consumer_name" id="consumer_name" required maxlength="255"
                               value="{{ old('consumer_name') }}"
                               placeholder="Full name of the consumer"
                               class="form-input">
                        @error('consumer_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label for="installation_address" class="form-label">Installation Address <span class="required">*</span></label>
                        <textarea name="installation_address" id="installation_address" required rows="2" maxlength="500"
                                  placeholder="Complete address where the service will be installed"
                                  class="form-input resize-none">{{ old('installation_address') }}</textarea>
                        @error('installation_address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Contact Information --}}
            <div class="border-b border-gray-200 pb-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="phone" class="w-5 h-5 text-blue-600"></i>
                    Contact Information
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="contact_person" class="form-label">Contact Person <span class="required">*</span></label>
                        <input type="text" name="contact_person" id="contact_person" required maxlength="255"
                               value="{{ old('contact_person') }}"
                               placeholder="Name of the contact person"
                               class="form-input">
                        @error('contact_person')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="contact_phone" class="form-label">Contact Phone <span class="required">*</span></label>
                        <input type="text" name="contact_phone" id="contact_phone" required maxlength="50"
                               value="{{ old('contact_phone') }}"
                               placeholder="+63-912-345-6789"
                               class="form-input">
                        @error('contact_phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="contact_email" class="form-label">Contact Email <span class="required">*</span></label>
                        <input type="email" name="contact_email" id="contact_email" required maxlength="255"
                               value="{{ old('contact_email') }}"
                               placeholder="email@example.com"
                               class="form-input">
                        @error('contact_email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Additional Notes --}}
            <div>
                <h4 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <i data-lucide="message-square" class="w-5 h-5 text-blue-600"></i>
                    Additional Information
                </h4>
                <div>
                    <label for="notes" class="form-label">Notes</label>
                    <textarea name="notes" id="notes" rows="3" maxlength="2000"
                              placeholder="Any additional notes or special instructions..."
                              class="form-input resize-none">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.water-connection.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
