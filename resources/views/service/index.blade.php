@extends('layouts.app')

@section('title', 'My Content - Media World')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Media World</h1>
                <div class="flex items-center space-x-6">
                    <a href="{{ route('service.content') }}" class="hover:text-indigo-200">Home</a>
                    <a href="{{ route('service.videos') }}" class="hover:text-indigo-200">Videos</a>
                    <a href="{{ route('service.music') }}" class="hover:text-indigo-200">Music</a>
                    <span class="text-indigo-200">|</span>
                    <span class="text-sm">{{ $user_phone }}</span>
                    <a href="{{ route('service.logout') }}" class="bg-red-500 px-4 py-2 rounded hover:bg-red-600">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container mx-auto px-6 py-12">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-2xl p-12 text-white mb-8">
            <h2 class="text-4xl font-bold mb-4">Welcome Back!</h2>
            <p class="text-xl mb-2">Your subscription is active</p>
            <p class="text-indigo-200">Member since: {{ \Carbon\Carbon::parse($subscribed_at)->format('M d, Y') }}</p>
        </div>

        <!-- Quick Access -->
        <div class="grid md:grid-cols-2 gap-8 mb-8">
            <a href="{{ route('service.videos') }}" 
               class="bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition-shadow group">
                <div class="flex items-center mb-4">
                    <i class="fas fa-play-circle text-5xl text-red-500 group-hover:scale-110 transition-transform"></i>
                    <div class="ml-6">
                        <h3 class="text-2xl font-bold text-gray-800">Video Library</h3>
                        <p class="text-gray-600">Thousands of movies and shows</p>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Browse now</span>
                    <i class="fas fa-arrow-right text-indigo-600"></i>
                </div>
            </a>

            <a href="{{ route('service.music') }}" 
               class="bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition-shadow group">
                <div class="flex items-center mb-4">
                    <i class="fas fa-music text-5xl text-green-500 group-hover:scale-110 transition-transform"></i>
                    <div class="ml-6">
                        <h3 class="text-2xl font-bold text-gray-800">Music Collection</h3>
                        <p class="text-gray-600">Millions of songs available</p>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Listen now</span>
                    <i class="fas fa-arrow-right text-indigo-600"></i>
                </div>
            </a>
        </div>

        <!-- Subscription Info -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Subscription Information</h3>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="text-center">
                    <i class="fas fa-check-circle text-4xl text-green-500 mb-2"></i>
                    <p class="text-gray-600">Status</p>
                    <p class="text-xl font-bold text-gray-800">Active</p>
                </div>
                <div class="text-center">
                    <i class="fas fa-calendar text-4xl text-blue-500 mb-2"></i>
                    <p class="text-gray-600">Plan</p>
                    <p class="text-xl font-bold text-gray-800">Daily - 1 JOD</p>
                </div>
                <div class="text-center">
                    <i class="fas fa-infinity text-4xl text-purple-500 mb-2"></i>
                    <p class="text-gray-600">Access</p>
                    <p class="text-xl font-bold text-gray-800">Unlimited</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

