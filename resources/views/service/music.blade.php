@extends('layouts.app')

@section('title', 'Music - Media World')

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
                    <a href="{{ route('service.music') }}" class="hover:text-indigo-200 border-b-2 border-white">Music</a>
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
            <i class="fas fa-music text-green-500 mr-3"></i>
            Music Library
        </h2>

        <div class="bg-gradient-to-r from-green-400 to-blue-500 rounded-xl p-8 text-white mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-3xl font-bold mb-2">Now Playing</h3>
                    <p class="text-xl">{{ $songs[0]['title'] }} - {{ $songs[0]['artist'] }}</p>
                </div>
                <div class="flex space-x-4">
                    <button class="bg-white/20 hover:bg-white/30 rounded-full w-14 h-14 flex items-center justify-center">
                        <i class="fas fa-backward"></i>
                    </button>
                    <button class="bg-white text-green-500 rounded-full w-14 h-14 flex items-center justify-center">
                        <i class="fas fa-play"></i>
                    </button>
                    <button class="bg-white/20 hover:bg-white/30 rounded-full w-14 h-14 flex items-center justify-center">
                        <i class="fas fa-forward"></i>
                    </button>
                </div>
            </div>
        </div>

        <h3 class="text-2xl font-bold text-gray-800 mb-6">Your Playlist</h3>
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-gray-600">#</th>
                        <th class="px-6 py-4 text-left text-gray-600">Title</th>
                        <th class="px-6 py-4 text-left text-gray-600">Artist</th>
                        <th class="px-6 py-4 text-left text-gray-600">Duration</th>
                        <th class="px-6 py-4 text-left text-gray-600">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($songs as $song)
                        <tr class="border-t hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">{{ $song['id'] }}</td>
                            <td class="px-6 py-4 font-semibold text-gray-800">{{ $song['title'] }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $song['artist'] }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $song['duration'] }}</td>
                            <td class="px-6 py-4">
                                <button class="text-green-500 hover:text-green-700 mr-4">
                                    <i class="fas fa-play-circle text-xl"></i>
                                </button>
                                <button class="text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-heart text-xl"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Genres -->
        <div class="mt-12">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Browse by Genre</h3>
            <div class="grid grid-cols-4 gap-4">
                <div class="bg-gradient-to-br from-red-400 to-pink-500 rounded-xl p-6 text-white cursor-pointer hover:shadow-xl transition-shadow">
                    <i class="fas fa-fire text-3xl mb-2"></i>
                    <h4 class="font-bold">Pop</h4>
                </div>
                <div class="bg-gradient-to-br from-purple-400 to-indigo-500 rounded-xl p-6 text-white cursor-pointer hover:shadow-xl transition-shadow">
                    <i class="fas fa-guitar text-3xl mb-2"></i>
                    <h4 class="font-bold">Rock</h4>
                </div>
                <div class="bg-gradient-to-br from-green-400 to-teal-500 rounded-xl p-6 text-white cursor-pointer hover:shadow-xl transition-shadow">
                    <i class="fas fa-leaf text-3xl mb-2"></i>
                    <h4 class="font-bold">Jazz</h4>
                </div>
                <div class="bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl p-6 text-white cursor-pointer hover:shadow-xl transition-shadow">
                    <i class="fas fa-drum text-3xl mb-2"></i>
                    <h4 class="font-bold">Hip Hop</h4>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

