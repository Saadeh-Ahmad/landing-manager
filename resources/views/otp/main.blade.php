@extends('layouts.app')

@section('title', $config['service_title'] ?? __('landing.otp.enter_phone_number'))

@section('content')
<div class="min-h-screen bg-slate-50 flex flex-col items-center justify-center px-4 py-10 sm:py-14">
    <div class="fixed top-4 end-4 z-50">
        <div class="flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-2 py-1.5 text-sm shadow-sm">
            <a href="{{ route('lang.switch', 'ar') }}"
               class="rounded px-2 py-1 font-medium {{ app()->getLocale() === 'ar' ? 'bg-slate-800 text-white' : 'text-slate-600 hover:text-slate-900' }}">عربي</a>
            <span class="text-slate-300">|</span>
            <a href="{{ route('lang.switch', 'en') }}"
               class="rounded px-2 py-1 font-medium {{ app()->getLocale() === 'en' ? 'bg-slate-800 text-white' : 'text-slate-600 hover:text-slate-900' }}">EN</a>
        </div>
    </div>

    <div class="w-full max-w-md">
        <div class="mb-6 text-center">
            <h1 class="text-xl font-semibold tracking-tight text-slate-900">{{ $config['service_title'] }}</h1>
            <p class="mt-2 text-sm text-slate-600">{{ __('landing.otp.get_started') }}</p>
        </div>

        <!-- Phone Section -->
        <div id="phoneSection" class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <div id="alertMessage" class="hidden mb-4 rounded-lg p-3 text-sm"></div>

            <form id="phoneForm" class="space-y-5">
                <div>
                    <label for="msisdn" class="mb-1.5 block text-sm font-medium text-slate-700">
                        {{ __('landing.otp.enter_phone_number') }}
                    </label>
                    <div class="flex overflow-hidden rounded-lg border border-slate-300 bg-white focus-within:border-slate-500 focus-within:ring-1 focus-within:ring-slate-500">
                        <span class="inline-flex items-center border-e border-slate-200 bg-slate-50 px-3 text-sm font-medium text-slate-600">964</span>
                        <input type="tel"
                               id="msisdn"
                               name="msisdn"
                               placeholder="7xxxxxxxxx"
                               required
                               pattern="7[0-9]{9}"
                               maxlength="10"
                               inputmode="numeric"
                               class="min-w-0 flex-1 border-0 bg-transparent px-3 py-2.5 text-base text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-0">
                    </div>
                </div>

                <button type="submit"
                        id="sendOtpBtn"
                        class="w-full rounded-lg bg-slate-900 py-3 text-center text-base font-semibold text-white transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2">
                    {{ __('landing.otp.confirm') }}
                </button>
            </form>

            <p class="mt-6 text-center text-xs leading-relaxed text-slate-500">
                {{ __('landing.disclaimer.text') }}
            </p>
        </div>

        <!-- OTP Section -->
        <div id="otpSection" class="mt-6 hidden rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-medium text-slate-900">{{ __('landing.otp.enter_otp_code') }}</h2>
            <p class="mt-1 text-sm text-slate-600">{{ __('landing.otp.code_sent_message') }}</p>

            <div id="otpAlertMessage" class="mt-4 hidden rounded-lg p-3 text-sm"></div>

            <form id="otpForm" class="mt-5 space-y-5">
                <div>
                    <label for="pincode" class="mb-1.5 block text-sm font-medium text-slate-700">
                        {{ __('landing.otp.enter_code_sent') }}
                    </label>
                    <input type="text"
                           id="pincode"
                           name="pincode"
                           placeholder="{{ str_replace(':length', $config['pin_length'], __('landing.otp.enter_digit_code')) }}"
                           required
                           maxlength="{{ $config['pin_length'] }}"
                           inputmode="numeric"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-center text-xl font-semibold tracking-widest text-slate-900 focus:border-slate-500 focus:outline-none focus:ring-1 focus:ring-slate-500">
                    <p class="mt-2 text-center text-xs text-slate-500">
                        {{ str_replace(':length', $config['pin_length'], __('landing.otp.check_sms')) }}
                    </p>
                </div>

                <button type="submit"
                        id="verifyOtpBtn"
                        class="w-full rounded-lg bg-slate-900 py-3 text-center text-base font-semibold text-white transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2">
                    {{ __('landing.otp.verify') }} &amp; {{ __('landing.subscribe_now') }}
                </button>
            </form>

            <button type="button" onclick="backToPhone()" class="mt-4 w-full text-center text-sm font-medium text-slate-600 hover:text-slate-900">
                {{ __('landing.otp.back_to_phone') }}
            </button>
        </div>
    </div>

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
            if (data.already_subscribed) {
                window.location.href = @json(route('duel.success', ['already_subscribed' => true]));
                return;
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
            showOtpAlert('Subscription activated successfully! Redirecting...', 'success');
            setTimeout(() => {
                window.location.href = @json(route('duel.success'));
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
    alert.className = `mb-4 rounded-lg p-3 text-sm ${type === 'error' ? 'bg-red-50 text-red-800 ring-1 ring-red-200' : 'bg-emerald-50 text-emerald-800 ring-1 ring-emerald-200'}`;
    alert.textContent = message;
    alert.classList.remove('hidden');

    setTimeout(() => {
        alert.classList.add('hidden');
    }, 5000);
}

function showOtpAlert(message, type) {
    const alert = document.getElementById('otpAlertMessage');
    alert.className = `rounded-lg p-3 text-sm ${type === 'error' ? 'bg-red-50 text-red-800 ring-1 ring-red-200' : 'bg-emerald-50 text-emerald-800 ring-1 ring-emerald-200'}`;
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
