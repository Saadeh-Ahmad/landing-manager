@extends('layouts.app')

@section('title', 'Videos - Media World')

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg">
        <div class="container mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Media World</h1>
                <div class="flex items-center space-x-6">
                    <a href="{{ route('service.content') }}" class="hover:text-indigo-200">Home</a>
                    <a href="{{ route('service.videos') }}" class="hover:text-indigo-200 border-b-2 border-white">Videos</a>
                    <a href="{{ route('service.music') }}" class="hover:text-indigo-200">Music</a>
                    <span class="text-indigo-200">|</span>
                    <span class="text-sm">{{ session('msisdn') }}</span>
                    <a href="{{ route('service.logout') }}" class="bg-red-500 px-4 py-2 rounded hover:bg-red-600">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container mx-auto px-6 py-12">
        <h2 class="text-4xl font-bold text-gray-800 mb-8">
            <i class="fas fa-film text-red-500 mr-3"></i>
            Video Library
        </h2>

        <div class="grid md:grid-cols-3 gap-8">
            @foreach($videos as $video)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow">
                    <img src="{{ $video['thumbnail'] }}" 
                         alt="{{ $video['title'] }}" 
                         class="w-full h-48 object-cover">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $video['title'] }}</h3>
                        <div class="flex items-center text-gray-600 mb-4">
                            <i class="fas fa-clock mr-2"></i>
                            <span>{{ $video['duration'] }}</span>
                        </div>
                        <button class="w-full bg-gradient-to-r from-red-500 to-pink-500 text-white py-3 rounded-lg font-bold hover:shadow-lg transition-shadow">
                            <i class="fas fa-play mr-2"></i>
                            Watch Now
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Categories -->
        <div class="mt-12">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Browse by Category</h3>
            <div class="flex flex-wrap gap-4">
                <button class="px-6 py-3 bg-white rounded-full shadow hover:shadow-lg transition-shadow">
                    <i class="fas fa-fire mr-2 text-red-500"></i>Trending
                </button>
                <button class="px-6 py-3 bg-white rounded-full shadow hover:shadow-lg transition-shadow">
                    <i class="fas fa-laugh mr-2 text-yellow-500"></i>Comedy
                </button>
                <button class="px-6 py-3 bg-white rounded-full shadow hover:shadow-lg transition-shadow">
                    <i class="fas fa-fist-raised mr-2 text-orange-500"></i>Action
                </button>
                <button class="px-6 py-3 bg-white rounded-full shadow hover:shadow-lg transition-shadow">
                    <i class="fas fa-book mr-2 text-blue-500"></i>Documentary
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

