@extends('layouts.app')

@section('title', $config['service_title'] ?? 'Premium Entertainment')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-500 via-red-600 to-orange-700 relative overflow-hidden">
    <!-- Animated background elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 left-10 w-32 h-32 bg-white/5 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-20 right-10 w-40 h-40 bg-yellow-400/10 rounded-full blur-3xl animate-pulse delay-1000"></div>
        <div class="absolute top-1/2 left-1/4 w-24 h-24 bg-white/5 rounded-full blur-2xl animate-pulse delay-2000"></div>
    </div>

    <header class="bg-black/20 backdrop-blur-md border-b border-white/10 relative z-10">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/3D Basketball/3D_Basketball_512x512.png') }}"
                         alt="{{ __('landing.basketball.title') }}"
                         class="w-10 h-10 object-contain">
                    <div class="text-white text-xl sm:text-2xl font-bold">{{ __('landing.basketball.title') }}</div>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Language Switcher -->
                    <div class="flex items-center space-x-2 bg-white/10 rounded-lg px-2 py-1">
                        <a href="{{ route('lang.switch', 'ar') }}" 
                           class="px-2 py-1 rounded text-sm font-semibold transition-colors {{ app()->getLocale() === 'ar' ? 'bg-orange-500 text-white' : 'text-white/70 hover:text-white' }}">
                            عربي
                        </a>
                        <span class="text-white/50">|</span>
                        <a href="{{ route('lang.switch', 'en') }}" 
                           class="px-2 py-1 rounded text-sm font-semibold transition-colors {{ app()->getLocale() === 'en' ? 'bg-orange-500 text-white' : 'text-white/70 hover:text-white' }}">
                            EN
                        </a>
                    </div>
                    @if($evina_config)
                        <div class="bg-green-500 text-white px-3 py-1.5 rounded-full inline-block font-semibold text-xs sm:text-sm">
                            🔒 {{ __('landing.protected') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-6 py-8 sm:py-12 relative z-10">
        <div class="max-w-4xl mx-auto">
            <!-- Hero Section with Game Banner -->
            <div class="text-center mb-6 sm:mb-8">
                <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-white mb-4 sm:mb-6 drop-shadow-2xl">
                <span class="bg-gradient-to-r from-yellow-300 to-orange-300 bg-clip-text text-transparent">
                    {{ __('landing.basketball.hero_title') }}
                </span>
                </h1>
                <div class="mb-4 sm:mb-6">
                    <img src="{{ asset('images/3D Basketball/3DBasketball_1024x500.png') }}"
                         alt="{{ __('landing.basketball.title') }}"
                         class="w-full max-w-2xl mx-auto rounded-2xl shadow-2xl border-4 border-white/20">
                </div>
                <p class="text-xl sm:text-2xl text-white/90 mb-6 font-semibold drop-shadow-lg">
                    {{ __('landing.basketball.hero_subtitle') }}
                </p>
                <p class="text-base sm:text-lg text-white/80 max-w-2xl mx-auto">
                    {{ __('landing.basketball.hero_description') }}
                </p>
            </div>

            <!-- Subscribe Section - Generic and Reusable -->
            <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8 border-4 border-orange-400/30 mb-8 sm:mb-12" id="subscribeSection">
                <div class="text-center mb-6">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">{{ $config['subscribe_title'] ?? __('landing.subscribe_now') }}</h2>
                    <p class="text-gray-600 text-sm sm:text-base">{{ $config['subscribe_description'] ?? __('landing.get_instant_access') }}</p>
                </div>

                <div id="alertMessage" class="hidden mb-4 p-4 rounded-lg"></div>

                <!-- Subscribe Button -->
                <form id="subscribeForm">
                    <input type="hidden" name="service_name" value="{{ $config['service_name'] }}">

                    <button type="submit"
                            id="subscribe_btn"
                            class="w-full bg-gradient-to-r from-orange-500 via-red-600 to-orange-600 text-white py-4 sm:py-5 rounded-xl text-lg sm:text-xl font-bold hover:shadow-2xl transform hover:scale-105 transition-all duration-200 border-2 border-orange-400/50 relative overflow-hidden group">
                    <span class="relative z-10 flex items-center justify-center">
                        <i class="fas {{ $config['subscribe_button_icon'] ?? 'fa-basketball-ball' }} mr-2 text-xl"></i>
                        {{ $config['subscribe_button_text'] ?? __('landing.subscribe_play_now') }}
                    </span>
                        <div class="absolute inset-0 bg-gradient-to-r from-orange-400 to-red-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </button>
                </form>

                <p class="text-xs sm:text-sm text-gray-500 mt-4 sm:mt-6 text-center">
                    {{ __('landing.by_subscribing') }} <a href="{{ route('terms') }}" class="text-orange-600 hover:text-orange-700 underline">{{ __('landing.terms_conditions') }}</a> {{ __('landing.and') }} <a href="{{ route('privacy') }}" class="text-orange-600 hover:text-orange-700 underline">{{ __('landing.privacy_policy') }}</a>
                </p>
                
                <!-- Disclaimer -->
                <hr class="my-4 sm:my-6 border-gray-300">
                <p class="text-base sm:text-lg text-gray-700 leading-relaxed text-center">
                    {{ __('landing.disclaimer.text') }}
                </p>
            </div>

            <!-- Game Screenshots Gallery -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 mb-8 sm:mb-12">
                <div class="rounded-xl overflow-hidden shadow-lg border-2 border-white/20 hover:scale-105 transition-transform">
                    <img src="{{ asset('images/3D Basketball/3D_Basketball_01_1280x720.png') }}"
                         alt="{{ __('landing.game_screenshot') }} 1"
                         class="w-full h-auto object-cover">
                </div>
                <div class="rounded-xl overflow-hidden shadow-lg border-2 border-white/20 hover:scale-105 transition-transform">
                    <img src="{{ asset('images/3D Basketball/3D_Basketball_02_1280x720.png') }}"
                         alt="{{ __('landing.game_screenshot') }} 2"
                         class="w-full h-auto object-cover">
                </div>
                <div class="rounded-xl overflow-hidden shadow-lg border-2 border-white/20 hover:scale-105 transition-transform">
                    <img src="{{ asset('images/3D Basketball/3D_Basketball_03_1280x720.png') }}"
                         alt="{{ __('landing.game_screenshot') }} 3"
                         class="w-full h-auto object-cover">
                </div>
                <div class="rounded-xl overflow-hidden shadow-lg border-2 border-white/20 hover:scale-105 transition-transform">
                    <img src="{{ asset('images/3D Basketball/3D_Basketball_04_1280x720.png') }}"
                         alt="{{ __('landing.game_screenshot') }} 4"
                         class="w-full h-auto object-cover">
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/uuid@latest/dist/umd/uuidv4.min.js"></script>
<script>
// Configuration from backend
const config = {
    serviceName: '{{ $config['service_name'] }}',
    enableEvinaFraud: {{ $config['enable_evina_fraud'] ? 'true' : 'false' }},
    @if($evina_config)
    evinaConfig: {!! json_encode($evina_config) !!},
    @endif
};

// Evina state
let evinaState = {
    ti: null,
    ts: null,
    heRedirectUrl: null
};

// Function to generate transaction ID using UUID (matching example)
function generateTransactionId() {
    return uuidv4();
}

// Function to generate timestamp
function generateTimestamp() {
    return new Date().getTime();
}

// Function to build HE redirect URL
function buildHeRedirectUrl(evinaConfig, ti, ts) {
    const baseUrl = evinaConfig.base_url;
    const endpoint = evinaConfig.he_redirect_endpoint;
    const url = baseUrl.replace(/\/$/, '') + '/' + endpoint.replace(/^\//, '');

    const params = new URLSearchParams({
        serviceId: evinaConfig.service_id,
        spId: evinaConfig.sp_id,
        shortcode: evinaConfig.shortcode,
        ti: ti,
        ts: ts,
        servicename: evinaConfig.service_name,
        merchantname: evinaConfig.merchant_name,
        otp_landing: '{{ $otp_landing_name }}',
    });

    return url + '?' + params.toString();
}

// Function to append Evina script correctly
function append_script(returnedScript) {
    var scriptElement = document.createElement("script");
    scriptElement.type = "text/javascript";
    scriptElement.innerHTML = returnedScript;
    $('head').append(scriptElement);

    var event = new Event('DCBProtectRun');
    document.dispatchEvent(event);
}

// Load Evina script from API (matching example flow)
function exec_anti_fraud() {
    if (!config.enableEvinaFraud || !config.evinaConfig) {
        return;
    }

    // Generate TI and TS
    evinaState.ti = generateTransactionId();
    evinaState.ts = generateTimestamp();

    // Build HE redirect URL
    evinaState.heRedirectUrl = buildHeRedirectUrl(config.evinaConfig, evinaState.ti, evinaState.ts);

    // Build script URL
    const scriptUrl = config.evinaConfig.base_url.replace(/\/$/, '') + '/' +
                     config.evinaConfig.get_script_endpoint.replace(/^\//, '');

    // CSS selector for the button - pass raw, URLSearchParams will encode it
    const css_selector = '#subscribe_btn';

    // Build query parameters - URLSearchParams will handle encoding
    const scriptParams = new URLSearchParams({
        action: 'script',
        ti: evinaState.ti,
        ts: evinaState.ts.toString(),
        te: css_selector, // Pass raw selector, URLSearchParams encodes it
        servicename: config.evinaConfig.service_name,
        merchantname: config.evinaConfig.merchant_name || 'MediaWorld',
        type: 'he'
    });

    const fullScriptUrl = scriptUrl + '?' + scriptParams.toString();

    // Use jQuery AJAX to match example (expects JSON response with 's' property)
    $.ajax({
        url: fullScriptUrl,
        method: 'GET',
        success: function(response) {
            let script_data = response.s;
            append_script(script_data);
            console.log('Evina anti-fraud script loaded successfully');
        },
        error: function(xhr, status, error) {
            console.error('Error loading Evina script:', status, error);
            // Fallback: try as plain text if JSON fails
            fetch(fullScriptUrl)
                .then(response => response.text())
                .then(scriptContent => {
                    append_script(scriptContent);
                    console.log('Evina anti-fraud script loaded (fallback)');
                })
                .catch(err => {
                    console.error('Failed to load Evina script:', err);
                });
        }
    });
}

// Load Evina script on page load
window.onload = () => {
    if (config.enableEvinaFraud && config.evinaConfig) {
        exec_anti_fraud();
    }
}

// Handle subscribe button click - direct redirect (matching example)
$('#subscribeForm').on('submit', function(e) {
    e.preventDefault();

    // Redirect to HE API directly
    if (evinaState.heRedirectUrl) {
        window.location.href = evinaState.heRedirectUrl;
    } else {
        console.error('HE redirect URL not available');
        showAlert('Error: Unable to redirect. Please try again.', 'error');
    }
});

// Show alert message
function showAlert(message, type) {
    const alert = document.getElementById('alertMessage');
    const bgColor = type === 'error' ? 'bg-red-100 text-red-700' :
                    type === 'success' ? 'bg-green-100 text-green-700' :
                    'bg-blue-100 text-blue-700';

    alert.className = `mb-4 p-4 rounded-lg ${bgColor}`;
    alert.textContent = message;
    alert.classList.remove('hidden');

    if (type !== 'info') {
        setTimeout(() => {
            alert.classList.add('hidden');
        }, 5000);
    }
}

// Handle Evina anti-fraud script events (if enabled)
if (config.enableEvinaFraud) {
    document.addEventListener('DCBProtectRun', function() {
        console.log('Evina anti-fraud script loaded and running');
    });
}
</script>
@endsection

