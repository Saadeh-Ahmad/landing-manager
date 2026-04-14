@extends('layouts.app')

@section('title', 'Subscription Failed')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-400 via-pink-500 to-orange-500">
    <header class="bg-white/10 backdrop-blur-md">
        <div class="container mx-auto px-6 py-4">
            <div class="text-white text-2xl font-bold">Media World Premium</div>
        </div>
    </header>

    <div class="container mx-auto px-6 py-20">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-2xl shadow-2xl p-8 mb-6">
                <div class="text-center mb-6">
                    <div class="text-6xl mb-4">❌</div>
                    <h1 class="text-4xl font-bold mb-4 text-gray-800">Subscription Failed</h1>
                    <!-- <p class="text-xl text-gray-600">We couldn't complete your subscription</p> -->
                </div>
            </div>
 
            <!-- <div class="bg-white rounded-2xl shadow-2xl p-8 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Error Details</h2>

                <div class="error-msg bg-red-50 p-4 rounded-lg mb-4 min-h-[100px]">
                    <p class="text-sm text-gray-500 text-center">Loading error details...</p>
                </div>

                <div class="space-y-3">
                    <a href="{{ route('landing') }}"
                       class="block w-full bg-gray-200 text-gray-700 py-2 rounded-lg hover:bg-gray-300 transition-all text-center">
                        <i class="fas fa-home mr-2"></i>
                        Back to Home
                    </a>
                </div>
            </div> -->

            <!-- Support Section -->
            <div class="bg-white/10 backdrop-blur-lg rounded-xl p-6 text-white text-center">
                <h3 class="text-xl font-bold mb-2">Need Help?</h3>
                <p class="text-sm mb-2">Contact our support team</p>
                <p class="text-sm">
                    <i class="fas fa-envelope mr-2"></i>
                    Email: <a href="mailto:info@mediaworldiq.com" class="text-white underline hover:text-yellow-200">info@mediaworldiq.com</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
window.onload = function() {
    const searchParams = new URLSearchParams(window.location.search);
    const msgDiv = document.querySelector('.error-msg');

    // Clear the loading message
    msgDiv.innerHTML = '';

    // Check if there are any query parameters
    if (searchParams.toString() === '') {
        msgDiv.innerHTML = '<p class="text-sm text-gray-500 text-center">No error details available</p>';
        return;
    }

    // Create a container for better styling
    const container = document.createElement('div');
    container.className = 'space-y-2';

    // Loop through all query parameters and display them
    for (const [key, value] of searchParams.entries()) {
        const p = document.createElement('p');
        p.className = 'text-sm text-gray-800';

        // Format the key (replace underscores with spaces, capitalize)
        const formattedKey = key
            .replace(/_/g, ' ')
            .replace(/\b\w/g, l => l.toUpperCase());

        // Create a styled display
        const strong = document.createElement('strong');
        strong.className = 'text-red-700';
        strong.textContent = formattedKey + ': ';

        const span = document.createElement('span');
        span.className = 'text-gray-700';
        span.textContent = decodeURIComponent(value);

        p.appendChild(strong);
        p.appendChild(span);
        container.appendChild(p);
    }

    msgDiv.appendChild(container);
};
</script>
@endsection

