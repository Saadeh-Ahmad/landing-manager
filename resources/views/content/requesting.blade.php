@extends('layouts.app')

@section('title', 'Connecting to Content Portal')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-600 to-purple-700 flex items-center justify-center px-6">
    <div class="bg-white rounded-2xl shadow-2xl p-12 max-w-md w-full text-center">
        <div class="mb-8">
            <div class="relative">
                <div class="w-24 h-24 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6 animate-pulse">
                    <i class="fas fa-satellite-dish text-white text-4xl"></i>
                </div>
                <div class="absolute top-0 left-1/2 transform -translate-x-1/2">
                    <div class="w-32 h-32 border-4 border-indigo-200 border-t-indigo-600 rounded-full animate-spin"></div>
                </div>
            </div>
        </div>

        <h1 class="text-3xl font-bold text-gray-800 mb-4">Connecting to Content Portal</h1>
        <p class="text-gray-600 mb-8">Please wait while we prepare your content access...</p>

        <div class="space-y-4" id="statusMessages">
            <div class="flex items-center justify-center space-x-3">
                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-gray-700">Verifying subscription...</span>
                <i class="fas fa-check text-green-500"></i>
            </div>
            <div class="flex items-center justify-center space-x-3" id="session-status">
                <div class="w-3 h-3 bg-blue-500 rounded-full animate-pulse"></div>
                <span class="text-gray-700">Requesting session ID...</span>
                <div class="spinner"><i class="fas fa-spinner fa-spin text-blue-500"></i></div>
            </div>
            <div class="flex items-center justify-center space-x-3 text-gray-400" id="redirect-status">
                <div class="w-3 h-3 bg-gray-300 rounded-full"></div>
                <span>Redirecting to content...</span>
                <i class="fas fa-hourglass-half text-gray-400"></i>
            </div>
        </div>

        <div class="mt-8">
            <div class="bg-indigo-50 rounded-lg p-4">
                <p class="text-sm text-indigo-700">
                    <i class="fas fa-info-circle mr-2"></i>
                    Subscriber: <strong>{{ $msisdn }}</strong>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-request session after 2 seconds
    setTimeout(function() {
        requestSession();
    }, 2000);
});

function requestSession() {
    fetch('{{ route("content.portal.process") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Update UI
            document.getElementById('session-status').innerHTML = `
                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                <span class="text-gray-700">Session created successfully!</span>
                <i class="fas fa-check text-green-500"></i>
            `;
            
            document.getElementById('redirect-status').innerHTML = `
                <div class="w-3 h-3 bg-blue-500 rounded-full animate-pulse"></div>
                <span class="text-gray-700">Redirecting to content...</span>
                <i class="fas fa-spinner fa-spin text-blue-500"></i>
            `;
            
            // Redirect after 1 second
            setTimeout(function() {
                window.location.href = data.redirect_url;
            }, 1000);
        } else {
            // Show error
            document.getElementById('session-status').innerHTML = `
                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                <span class="text-red-700">Failed to create session</span>
                <i class="fas fa-times text-red-500"></i>
            `;
            
            // Retry after 3 seconds
            setTimeout(function() {
                requestSession();
            }, 3000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Retry after 3 seconds
        setTimeout(function() {
            requestSession();
        }, 3000);
    });
}
</script>
@endsection

