@extends('citizen.layouts.app-sidebar')

@section('title', 'Profile - LGU1 Citizen Portal')
@section('page-title', 'My Profile')
@section('page-description', 'Manage your account information and security')

@section('content')
<div class="space-y-8">
    
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-6 border-b pb-3">Personal Information</h3>
        
        <div class="flex items-center space-x-4 mb-6">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 text-2xl">
                <i class="fas fa-user-circle"></i>
            </div>
            <div>
                <h4 class="text-lg font-semibold text-gray-900">{{ $user->name ?? 'N/A' }}</h4>
                <span class="text-sm text-gray-500">{{ $user->email ?? 'N/A' }}</span>
            </div>
            <div class="ml-auto">
                @if($user->is_verified)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-2"></i> Verified
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        <i class="fas fa-clock mr-2"></i> Verification Pending
                    </span>
                @endif
            </div>
        </div>
        
        <form method="POST" action="{{ route('citizen.profile.update') }}" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div class="max-w-2xl space-y-4">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name ?? '') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror" readonly>
                    <p class="mt-1 text-sm text-gray-500">Email cannot be changed.</p>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <textarea id="address" name="address" rows="3" required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('address') border-red-500 @enderror">{{ old('address', $user->address ?? '') }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('citizen.dashboard') }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <i class="fas fa-save mr-2"></i>
                        Update Profile
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-6 border-b pb-3">Change Password</h3>
        
        <form method="POST" action="{{ route('citizen.password.update') }}" class="space-y-4">
            @csrf
            @method('PUT')
            
            <div class="max-w-xl space-y-4">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                    <input type="password" id="current_password" name="current_password" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('current_password') border-red-500 @enderror">
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>
            
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="flex justify-end">
                    <button type="submit" 
                            class="px-6 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        Change Password
                    </button>
                </div>
            </div>
        </form>
    </div>

</div>
@endsection