@extends('layouts.admin')

@section('page-title', 'Profile Settings')
@section('page-subtitle', 'Manage your admin profile and credentials')

@section('page-content')
<div class="p-6">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-lgu-headline mb-2">Profile Settings</h1>
        <p class="text-lgu-paragraph">Manage your admin profile, credentials, and LGU configuration</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
            <div class="flex items-center">
                <i data-lucide="check-circle" class="w-5 h-5 text-green-500 mr-3"></i>
                <div>
                    <p class="font-semibold text-green-800">Success!</p>
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Error Messages -->
    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
            <div class="flex items-start">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500 mr-3 mt-0.5"></i>
                <div>
                    <p class="font-semibold text-red-800 mb-2">Update Failed!</p>
                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Profile Settings Card -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-0">
            <!-- Sidebar Navigation -->
            <div class="lg:col-span-1 bg-gray-50 border-r border-gray-200 p-6">
                <nav class="space-y-2">
                    <button class="profile-tab-btn w-full text-left px-4 py-3 rounded-lg font-medium transition flex items-center bg-lgu-button text-lgu-button-text">
                        <i data-lucide="user-circle" class="w-5 h-5 mr-3"></i>
                        Admin Profile
                    </button>
                </nav>
            </div>

            <!-- Content Area -->
            <div class="lg:col-span-3 p-8">
                <!-- Admin Profile Tab -->
                <div id="content-profile" class="profile-tab-content">
                    <h2 class="text-2xl font-bold text-lgu-headline mb-6">Personal Identification</h2>
                    
                    <!-- Profile Photo Section -->
                    <div class="bg-gray-50 rounded-xl p-8 mb-8">
                        @php 
                            $userName = session('user_name', $user->full_name ?? 'Admin User');
                            $userEmail = session('user_email', $user->email ?? 'admin@lgu1.com');
                            $nameParts = explode(' ', $userName);
                            $firstName = $nameParts[0] ?? 'A';
                            $lastName = end($nameParts);
                            $initials = strtoupper(substr($firstName, 0, 1) . (($lastName !== $firstName) ? substr($lastName, 0, 1) : 'D'));
                            $photo = ($user && $user->profile_photo_path) ? asset($user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($initials) . '&background=064e3b&color=fff&size=200';
                        @endphp
                        
                        <div class="flex items-start gap-8">
                            <!-- Avatar -->
                            <div class="flex-shrink-0">
                                <img id="avatar-preview" src="{{ $photo }}" 
                                     class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg">
                            </div>
                            
                            <!-- Info and Actions -->
                            <div class="flex-1 pt-2">
                                <h3 class="text-lg font-semibold text-lgu-headline mb-1">Profile Photo</h3>
                                <p class="text-sm text-lgu-paragraph mb-4">Accepted formats: JPG, PNG. Max 2MB.</p>
                                
                                <div class="flex items-center gap-3">
                                    <!-- Upload Photo (AJAX) -->
                                    <label for="avatar_input" class="px-5 py-2.5 bg-lgu-button text-lgu-button-text rounded-lg cursor-pointer hover:opacity-90 transition inline-flex items-center">
                                        <i data-lucide="camera" class="w-4 h-4 mr-2"></i>
                                        Choose Photo
                                    </label>
                                    <input type="file" id="avatar_input" name="avatar" class="hidden" onchange="uploadPhotoAjax(this)" accept="image/*">
                                    
                                    <!-- Remove Photo (AJAX) -->
                                    <div id="remove-photo-container" class="{{ ($user && $user->profile_photo_path) ? '' : 'hidden' }}">
                                        <button type="button" onclick="confirmRemovePhoto()" class="px-5 py-2.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition inline-flex items-center">
                                            <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Profile Update Form -->
                    <form action="{{ URL::signedRoute('admin.profile.update') }}" method="POST">
                        @csrf

                        <!-- Form Fields -->
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-lgu-headline mb-2">Full Name</label>
                                <input type="text" name="full_name" 
                                       value="{{ session('user_name', $user->full_name ?? '') }}" 
                                       required
                                       class="w-full max-w-md px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-lgu-stroke focus:outline-none">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-lgu-headline mb-2">Email Address (Locked)</label>
                                <input type="email" name="email" 
                                       value="{{ session('user_email', $user->email ?? '') }}" 
                                       readonly
                                       class="w-full max-w-md px-4 py-3 border-2 border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                                <p class="text-sm text-lgu-paragraph mt-1">Contact the Super Admin to change your official email.</p>
                            </div>
                            
                            <div class="pt-4">
                                <button type="submit" class="px-6 py-3 bg-lgu-button text-lgu-button-text font-semibold rounded-lg hover:opacity-90 transition">
                                    <i data-lucide="save" class="w-4 h-4 inline mr-2"></i>
                                    Update Profile Information
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Helper: Update all sidebar avatar elements with a new image URL
    function updateSidebarAvatars(imageUrl) {
        document.querySelectorAll('.sidebar-avatar').forEach(function(container) {
            const isSmall = container.classList.contains('w-10');
            if (imageUrl) {
                container.innerHTML = '<img src="' + imageUrl + '" alt="Avatar" class="w-full h-full object-cover">';
            } else {
                // Revert to initials
                const initials = '{{ $initials }}';
                const fontSize = isSmall ? 'text-base' : 'text-3xl';
                container.innerHTML = '<div class="w-full h-full bg-lgu-highlight flex items-center justify-center"><span class="text-lgu-button-text font-bold ' + fontSize + '">' + initials + '</span></div>';
            }
        });
    }

    // AJAX Photo Upload
    function uploadPhotoAjax(input) {
        if (!input.files || !input.files[0]) return;

        const file = input.files[0];

        // Validate file size (2MB)
        if (file.size > 2048 * 1024) {
            Swal.fire({ icon: 'error', title: 'File Too Large', text: 'Photo must be less than 2MB.', confirmButtonColor: '#0f5b3a' });
            return;
        }

        // Instant preview on the profile page
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatar-preview').src = e.target.result;
        };
        reader.readAsDataURL(file);

        // Build FormData
        const formData = new FormData();
        formData.append('avatar', file);
        formData.append('full_name', '{{ session('user_name', $user->full_name ?? '') }}');

        fetch('{{ URL::signedRoute('admin.profile.update') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update main preview
                if (data.avatar_url) {
                    document.getElementById('avatar-preview').src = data.avatar_url;
                }
                // Update sidebar avatars live
                updateSidebarAvatars(data.avatar_url);
                // Show remove button
                document.getElementById('remove-photo-container').classList.remove('hidden');

                Swal.fire({ icon: 'success', title: 'Photo Updated!', text: data.message, confirmButtonColor: '#0f5b3a' });
            } else {
                Swal.fire({ icon: 'error', title: 'Upload Failed', text: data.message || 'An error occurred.', confirmButtonColor: '#0f5b3a' });
            }
        })
        .catch(error => {
            console.error('Upload error:', error);
            Swal.fire({ icon: 'error', title: 'Upload Failed', text: 'An unexpected error occurred.', confirmButtonColor: '#0f5b3a' });
        });

        // Reset input so the same file can be re-selected
        input.value = '';
    }

    // AJAX Photo Removal with SweetAlert2 confirmation
    function confirmRemovePhoto() {
        Swal.fire({
            title: 'Remove Profile Photo?',
            text: "Are you sure you want to remove your profile photo?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, remove it',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ URL::signedRoute('admin.profile.photo.remove') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Revert preview to initials-based avatar
                        const fallback = 'https://ui-avatars.com/api/?name={{ urlencode($initials) }}&background=064e3b&color=fff&size=200';
                        document.getElementById('avatar-preview').src = fallback;
                        // Update sidebar avatars (no image)
                        updateSidebarAvatars(null);
                        // Hide remove button
                        document.getElementById('remove-photo-container').classList.add('hidden');

                        Swal.fire({ icon: 'success', title: 'Photo Removed!', text: data.message, confirmButtonColor: '#0f5b3a' });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Removal Failed', text: data.message || 'An error occurred.', confirmButtonColor: '#0f5b3a' });
                    }
                })
                .catch(error => {
                    console.error('Remove error:', error);
                    Swal.fire({ icon: 'error', title: 'Removal Failed', text: 'An unexpected error occurred.', confirmButtonColor: '#0f5b3a' });
                });
            }
        });
    }

    // Initialize Lucide icons on page load
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endsection
