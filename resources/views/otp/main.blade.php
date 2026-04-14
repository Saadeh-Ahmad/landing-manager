@extends('layouts.app')

@section('title', $config['service_title'] ?? __('landing.otp.enter_phone_number'))

@section('content')
<div class="min-h-screen bg-gray-100 flex items-center justify-center px-4 py-12 relative">
    <div class="language-switcher fixed top-4 right-4 z-50">
        <div class="flex items-center gap-2 bg-white rounded-lg shadow px-3 py-2 border border-gray-200">
            <a href="{{ route('lang.switch', 'ar') }}" class="px-2 py-1 rounded text-sm font-semibold {{ app()->getLocale() === 'ar' ? 'bg-blue-600 text-white' : 'text-gray-700 hover:text-blue-600' }}">عربي</a>
            <span class="text-gray-400">|</span>
            <a href="{{ route('lang.switch', 'en') }}" class="px-2 py-1 rounded text-sm font-semibold {{ app()->getLocale() === 'en' ? 'bg-blue-600 text-white' : 'text-gray-700 hover:text-blue-600' }}">EN</a>
        </div>
    </div>

    <!-- Phone Section -->
    <div id="phoneSection" class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden border border-gray-200">
        <div class="px-8 py-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-2 text-left">
                {{ __('landing.otp.get_started') }}
            </h1>

            <div id="alertMessage" class="hidden mb-4 p-4 rounded-lg text-sm"></div>

            <form id="phoneForm" class="space-y-6">
                <div>
                    <label for="msisdn" class="block text-sm font-semibold text-gray-700 mb-2">
                        {{ __('landing.otp.enter_phone_number') }}
                    </label>
                    <div class="phone-input-container flex rounded-xl border-2 border-orange-400 focus-within:ring-2 focus-within:ring-orange-500/20 focus-within:border-orange-500 overflow-hidden bg-white">
                        <span class="country-code inline-flex items-center px-4 bg-gray-50 border-r-2 border-orange-400 text-gray-700 font-semibold text-base">964</span>
                        <input type="tel"
                               id="msisdn"
                               name="msisdn"
                               placeholder="7xxxxxxxxx"
                               required
                               pattern="7[0-9]{9}"
                               maxlength="10"
                               inputmode="numeric"
                               class="flex-1 px-4 py-3.5 text-base border-0 focus:outline-none focus:ring-0 bg-white text-gray-800 placeholder-gray-400">
                    </div>
                </div>

                <button type="submit"
                        id="sendOtpBtn"
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white py-4 rounded-full text-lg font-bold shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:ring-offset-2">
                    {{ __('landing.otp.confirm') }}
                </button>
            </form>

            <hr class="my-6 border-gray-200">
            <p class="text-xs text-gray-400 text-center leading-relaxed">
                {{ __('landing.disclaimer.text') }}
            </p>
        </div>
    </div>

    <!-- OTP Section -->
    <div id="otpSection" class="hidden bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden border border-gray-200">
        <div class="px-8 py-8">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center">
                    <i class="fas fa-sms text-orange-500 text-lg"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">{{ __('landing.otp.enter_otp_code') }}</h1>
            </div>
            <p class="text-gray-500 text-sm mb-6">{{ __('landing.otp.code_sent_message') }}</p>

            <div id="otpAlertMessage" class="hidden mb-4 p-4 rounded-lg text-sm"></div>

            <form id="otpForm" class="space-y-6">
                <div>
                    <label for="pincode" class="block text-sm font-semibold text-gray-700 mb-2">
                        {{ __('landing.otp.enter_code_sent') }}
                    </label>
                    <input type="text"
                           id="pincode"
                           name="pincode"
                           placeholder="{{ str_replace(':length', $config['pin_length'], __('landing.otp.enter_digit_code')) }}"
                           required
                           maxlength="{{ $config['pin_length'] }}"
                           inputmode="numeric"
                           class="w-full px-4 py-3.5 rounded-xl border-2 border-gray-200 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 text-2xl text-center tracking-widest font-bold text-gray-800">
                    <p class="text-xs text-gray-400 mt-2 text-center">
                        {{ str_replace(':length', $config['pin_length'], __('landing.otp.check_sms')) }}
                    </p>
                </div>

                <button type="submit"
                        id="verifyOtpBtn"
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white py-4 rounded-full text-lg font-bold shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-orange-400 focus:ring-offset-2">
                    {{ __('landing.otp.verify') }} &amp; {{ __('landing.subscribe_now') }}
                </button>
            </form>

            <button onclick="backToPhone()" class="mt-4 w-full text-center text-sm text-orange-500 hover:text-orange-600 font-semibold transition-colors">
                <i class="fas fa-arrow-left mr-1"></i>{{ __('landing.otp.back_to_phone') }}
            </button>
        </div>
    </div>

    <!-- Placeholder to keep backToPhone() JS happy (no visual game preview in this design) -->
    <div id="gamePreview" class="hidden"></div>
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

                if (portalUrl) {
                    console.log('Redirecting to portal:', portalUrl);
                    window.location.href = portalUrl;
                } else {
                    console.error('No portal URL found in response:', data);
                    window.location.href = '/success';
                }
            } else {
                showAlert('OTP sent successfully! Check your SMS', 'success');
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
    alert.className = `mb-4 p-4 rounded-lg text-sm ${type === 'error' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'}`;
    alert.textContent = message;
    alert.classList.remove('hidden');

    setTimeout(() => {
        alert.classList.add('hidden');
    }, 5000);
}

function showOtpAlert(message, type) {
    const alert = document.getElementById('otpAlertMessage');
    alert.className = `mb-4 p-4 rounded-lg text-sm ${type === 'error' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'}`;
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
