@extends('layouts.app')

@section('title', $config['service_title'] ?? 'Premium Entertainment')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-500 via-red-600 to-orange-700 relative overflow-hidden">
    <!-- Animated background elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute top-20 left-10 w-32 h-32 bg-white/5 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-20 right-10 w-40 h-40 bg-yellow-400/10 rounded-full blur-3xl animate-pulse delay-1000"></div>
    </div>

    <!-- Language Switcher -->
    <div class="language-switcher fixed top-4 right-4 z-50">
        <div class="flex items-center space-x-2 bg-white rounded-lg shadow-lg px-3 py-2 border border-gray-200">
            <a href="{{ route('lang.switch', 'ar') }}"
               class="px-2 py-1 rounded text-sm font-semibold transition-colors {{ app()->getLocale() === 'ar' ? 'bg-blue-600 text-white' : 'text-gray-700 hover:text-blue-600' }}">
                عربي
            </a>
            <span class="text-gray-400">|</span>
            <a href="{{ route('lang.switch', 'en') }}"
               class="px-2 py-1 rounded text-sm font-semibold transition-colors {{ app()->getLocale() === 'en' ? 'bg-blue-600 text-white' : 'text-gray-700 hover:text-blue-600' }}">
                EN
            </a>
        </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 w-full py-8 relative z-10">
        <div class="max-w-2xl mx-auto">
            <!-- Game Header -->
            <div class="text-center mb-6 sm:mb-8">
                <h1 class="text-3xl sm:text-4xl font-extrabold text-white mb-4 drop-shadow-2xl">
                    <span class="bg-gradient-to-r from-yellow-300 to-orange-300 bg-clip-text text-transparent">
                        {{ __('landing.basketball.title') }}
                    </span>
                </h1>
                <div class="flex items-center justify-center mb-4">
                    <img src="{{ asset('images/3D Basketball/3D_Basketball_512x512.png') }}"
                         alt="{{ __('landing.basketball.title') }}"
                         class="w-24 h-24 sm:w-32 sm:h-32 object-contain">
                </div>
                <p class="text-xl sm:text-2xl text-white/90 mb-4 font-semibold drop-shadow-lg">
                    {{ __('landing.basketball.hero_subtitle') }}
                </p>
                <p class="text-base sm:text-lg text-white/80 max-w-2xl mx-auto mb-4">
                    {{ __('landing.basketball.hero_description') }}
                </p>
                <p class="text-white/90 text-sm sm:text-base">{{ __('landing.otp.subscribe_to_start') }}</p>
            </div>

            <!-- Phone Number Section -->
            <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8 border-4 border-orange-400/30 relative z-10" id="phoneSection">
                <div class="text-center mb-6">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">{{ __('landing.otp.get_started') }}</h2>
                    <p class="text-gray-600 text-sm sm:text-base">{{ __('landing.otp.enter_phone_description') }}</p>
                </div>

                <div id="alertMessage" class="hidden mb-4 p-4 rounded-lg"></div>
                <form id="phoneForm">
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2 text-sm sm:text-base">
                            <i class="fas fa-phone mr-2 text-orange-600"></i>{{ __('landing.otp.enter_phone_number') }}
                        </label>
                        <div class="flex phone-input-container">
                            <span class="country-code inline-flex items-center px-3 sm:px-4 bg-gradient-to-r from-orange-500 to-red-600 text-white border border-r-0 border-orange-600 rounded-l-lg font-semibold text-sm sm:text-base">
                                +964
                            </span>
                            <input type="tel"
                                   id="msisdn"
                                   name="msisdn"
                                   placeholder="7xxxxxxxxx"
                                   required
                                   pattern="7[0-9]{9}"
                                   maxlength="10"
                                   class="flex-1 px-4 sm:px-6 py-3 sm:py-4 rounded-r-lg border-2 border-gray-300 focus:outline-none focus:border-orange-600 focus:ring-2 focus:ring-orange-500/20 text-base sm:text-lg">
                        </div>
                    </div>

                    <button type="submit"
                            id="sendOtpBtn"
                            class="w-full bg-gradient-to-r from-orange-500 via-red-600 to-orange-600 text-white py-3 sm:py-4 rounded-xl text-lg sm:text-xl font-bold hover:shadow-2xl transform hover:scale-105 transition-all duration-200 border-2 border-orange-400/50 relative overflow-hidden group">
                        <span class="relative z-10 flex items-center justify-center">
                            <i class="fas fa-basketball-ball mr-2"></i>
                            {{ __('landing.otp.confirm') }}
                        </span>
                        <div class="absolute inset-0 bg-gradient-to-r from-orange-400 to-red-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </button>
                </form>

                <!-- Disclaimer -->
                <hr class="my-4 sm:my-6 border-gray-300" id="disclaimerSection">
                <p class="text-base sm:text-lg text-gray-700 leading-relaxed text-center">
                    {{ __('landing.disclaimer.text') }}
                </p>
            </div>

            <!-- OTP Section -->
            <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8 border-4 border-orange-400/30 hidden relative z-10" id="otpSection">
                <div class="text-center mb-6">
                    <div class="flex items-center justify-center mb-3">
                        <i class="fas fa-sms text-4xl text-orange-600"></i>
                    </div>
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">{{ __('landing.otp.enter_otp_code') }}</h2>
                    <p class="text-gray-600 text-sm">{{ __('landing.otp.code_sent_message') }}</p>
                </div>

                <div id="otpAlertMessage" class="hidden mb-4 p-4 rounded-lg"></div>

                <form id="otpForm">
                    <div class="mb-6">
                        <label class="block text-gray-700 font-semibold mb-2 text-sm sm:text-base">
                            <i class="fas fa-key mr-2 text-orange-600"></i>{{ __('landing.otp.enter_code_sent') }}
                        </label>
                        <input type="text"
                               id="pincode"
                               name="pincode"
                               placeholder="{{ str_replace(':length', $config['pin_length'], __('landing.otp.enter_digit_code')) }}"
                               required
                               maxlength="{{ $config['pin_length'] }}"
                               inputmode="numeric"
                               class="w-full px-4 sm:px-6 py-3 sm:py-4 rounded-lg border-2 border-gray-300 focus:outline-none focus:border-orange-600 focus:ring-2 focus:ring-orange-500/20 text-base sm:text-lg text-center text-xl sm:text-2xl tracking-widest font-bold">
                        <p class="text-xs text-gray-500 mt-2 text-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            {{ str_replace(':length', $config['pin_length'], __('landing.otp.check_sms')) }}
                        </p>
                    </div>

                    <button type="submit"
                            id="verifyOtpBtn"
                            class="w-full bg-gradient-to-r from-orange-500 via-red-600 to-orange-600 text-white py-3 sm:py-4 rounded-xl text-lg sm:text-xl font-bold hover:shadow-2xl transform hover:scale-105 transition-all duration-200 border-2 border-orange-400/50 relative overflow-hidden group">
                        <span class="relative z-10 flex items-center justify-center">
                            <i class="fas fa-basketball-ball mr-2"></i>
                            {{ __('landing.otp.verify') }} & {{ __('landing.subscribe_now') }}
                        </span>
                        <div class="absolute inset-0 bg-gradient-to-r from-orange-400 to-red-500 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    </button>
                </form>

                <button onclick="backToPhone()" class="text-orange-600 mt-4 text-center w-full hover:text-orange-700 font-semibold transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>{{ __('landing.otp.back_to_phone') }}
                </button>
            </div>

            <!-- Game Preview (shown on phone section) -->
            <div class="mt-6 sm:mt-8 grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4" id="gamePreview">
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
<script>
// Translations
const translations = {
    getOtpCode: '{{ __('landing.otp.confirm') }}',
    verifySubscribe: '{{ __('landing.otp.verify_subscribe') }}',
    sending: '{{ __('landing.otp.sending') }}',
    verifying: '{{ __('landing.otp.verifying') }}',
};

// Configuration from backend
const config = {
    serviceName: '{{ $config['service_name'] }}',
    pinLength: {{ $config['pin_length'] }},
    enableEvinaFraud: {{ $config['enable_evina_fraud'] ? 'true' : 'false' }},
    subscriptionType: '{{ $config['subscription_type'] }}',
    @if($evina_config)
    evinaConfig: {!! json_encode($evina_config) !!},
    @endif
};

// Evina state
let evinaState = {
    ti: null,
    ts: null
};

// Function to generate transaction ID
function generateTransactionId(prefix) {
    const timestamp = Math.floor(Date.now() / 1000);
    const random = Math.floor(Math.random() * 9000) + 1000;
    return `${prefix}-${timestamp}-${random}`;
}

// Function to generate timestamp
function generateTimestamp() {
    return Math.floor(Date.now() / 1000);
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

// Load Evina script from API
async function loadEvinaScript() {
    if (!config.enableEvinaFraud || !config.evinaConfig) {
        return;
    }

    try {
        // Generate TI and TS
        evinaState.ti = generateTransactionId(config.evinaConfig.transaction_prefix);
        evinaState.ts = generateTimestamp();

        // Build script URL
        const scriptUrl = config.evinaConfig.base_url.replace(/\/$/, '') + '/' +
                         config.evinaConfig.get_script_endpoint.replace(/^\//, '');

        const scriptParams = new URLSearchParams({
            action: 'script',
            servicename: config.evinaConfig.service_name,
            merchantname: config.evinaConfig.merchant_name,
            type: 'pin',
            ti: evinaState.ti,
            ts: evinaState.ts,
            te: '#sendOtpBtn'
        });

        const fullScriptUrl = scriptUrl + '?' + scriptParams.toString();

        // Fetch and append script
        const response = await fetch(fullScriptUrl);
        if (response.ok) {
            const scriptContent = await response.text();
            append_script(scriptContent);
            console.log('Evina anti-fraud script loaded successfully');
        } else {
            console.error('Failed to load Evina script:', response.status);
        }
    } catch (error) {
        console.error('Error loading Evina script:', error);
    }
}

// Load Evina script on page load
if (config.enableEvinaFraud && config.evinaConfig) {
    loadEvinaScript();
}

let currentMsisdn = '';

// Send OTP via Laravel API
document.getElementById('phoneForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const msisdnInput = document.getElementById('msisdn').value;
    const msisdn = '964' + msisdnInput;

    // Validate phone number
    if (!msisdnInput.match(/^7[0-9]{9}$/)) {
        showAlert('Please enter a valid Zain Iraq number starting with 7', 'error');
        return;
    }

    currentMsisdn = msisdn;
    const btn = document.getElementById('sendOtpBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ' + translations.sending;

    try {
        const response = await fetch('/api/dcb/send-pincode', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                msisdn: msisdn,
                service: config.serviceName
            })
        });

        const data = await response.json();
        console.log('DCB Send PIN Response:', data);

        if (data.success) {
            // Check if user is already subscribed
            if (data.already_subscribed || (data.data && data.data.portal_url)) {
                const portalUrl = data.data?.portal_url;
                // showAlert('You are already subscribed! Redirecting to content...', 'success');

                // Redirect to portal after short delay
                // setTimeout(() => {
                if (portalUrl) {
                    console.log('Redirecting to portal:', portalUrl);
                    window.location.href = portalUrl;
                } else {
                    console.error('No portal URL found in response:', data);
                    window.location.href = '/success';
                }
                // }, 1500);
            } else {
                showAlert('OTP sent successfully! Check your SMS', 'success');
                // Show OTP form
                    setTimeout(() => {
                        document.getElementById('phoneSection').classList.add('hidden');
                        document.getElementById('otpSection').classList.remove('hidden');
                        document.getElementById('gamePreview').classList.add('hidden');
                        document.getElementById('pincode').focus();
                    }, 1500);
            }
        } else {
            showAlert(data.message || 'Failed to send OTP. Please try again.', 'error');
            btn.disabled = false;
            btn.innerHTML = translations.getOtpCode;
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('Network error. Please check your connection.', 'error');
        btn.disabled = false;
        btn.innerHTML = translations.getOtpCode;
    }
});

// Verify OTP via Laravel API
document.getElementById('otpForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const pincode = document.getElementById('pincode').value;

    const pinRegex = new RegExp(`^[0-9]{${config.pinLength}}$`);
    if (!pincode.match(pinRegex)) {
        showOtpAlert(`Please enter a valid ${config.pinLength}-digit code`, 'error');
        return;
    }

    const btn = document.getElementById('verifyOtpBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ' + translations.verifying;

    try {
        const response = await fetch('/api/dcb/verify-pincode', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                msisdn: currentMsisdn,
                pincode: pincode,
                service: config.serviceName,
                ti: evinaState.ti || null,
                ts: evinaState.ts || null,
            })
        });

        const data = await response.json();
        console.log('DCB Verify Response:', data);

        if (data.success) {
            showOtpAlert('Subscription activated successfully! Redirecting to content...', 'success');
            // Redirect to portal if URL is provided, otherwise fallback
            setTimeout(() => {
                const portalUrl = data.data?.portal_url || data.portal_url;
                console.log('Portal URL:', portalUrl);

                if (portalUrl) {
                    console.log('Redirecting to portal:', portalUrl);
                    window.location.href = portalUrl;
                } else {
                    console.error('No portal URL found in response:', data);
                    window.location.href = '/success';
                }
            }, 1500);
        } else {
            showOtpAlert(data.message || 'Invalid code. Please try again.', 'error');
            btn.disabled = false;
            btn.innerHTML = translations.verifySubscribe;
            document.getElementById('pincode').value = '';
            document.getElementById('pincode').focus();
        }
    } catch (error) {
        console.error('Error:', error);
        showOtpAlert('Network error. Please try again.', 'error');
        btn.disabled = false;
        btn.innerHTML = translations.verifySubscribe;
    }
});

// Show alert message
function showAlert(message, type) {
    const alert = document.getElementById('alertMessage');
    alert.className = `mb-4 p-4 rounded-lg ${type === 'error' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'}`;
    alert.textContent = message;
    alert.classList.remove('hidden');

    setTimeout(() => {
        alert.classList.add('hidden');
    }, 5000);
}

function showOtpAlert(message, type) {
    const alert = document.getElementById('otpAlertMessage');
    alert.className = `mb-4 p-4 rounded-lg ${type === 'error' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'}`;
    alert.textContent = message;
    alert.classList.remove('hidden');

    setTimeout(() => {
        alert.classList.add('hidden');
    }, 5000);
}

// Back to phone number
function backToPhone() {
    document.getElementById('otpSection').classList.add('hidden');
    document.getElementById('phoneSection').classList.remove('hidden');
    document.getElementById('gamePreview').classList.remove('hidden');
    document.getElementById('pincode').value = '';
    document.getElementById('sendOtpBtn').disabled = false;
    document.getElementById('sendOtpBtn').innerHTML = translations.getOtpCode;
}

// Auto-format phone number input (only numbers)
document.getElementById('msisdn').addEventListener('input', (e) => {
    e.target.value = e.target.value.replace(/[^0-9]/g, '');
});

// Auto-format PIN input (only numbers)
document.getElementById('pincode').addEventListener('input', (e) => {
    e.target.value = e.target.value.replace(/[^0-9]/g, '');
});
</script>
@endsection
