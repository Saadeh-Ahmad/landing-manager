@extends('layouts.app')

@section('title', $config['service_title'] ?? __('landing.subscribe_now'))

@section('content')
<div class="min-h-screen bg-slate-50 flex flex-col items-center px-4 py-10 sm:py-14">
    <div class="fixed top-4 end-4 z-50">
        <div class="flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-2 py-1.5 text-sm shadow-sm">
            <a href="{{ route('lang.switch', 'ar') }}"
               class="rounded px-2 py-1 font-medium {{ app()->getLocale() === 'ar' ? 'bg-slate-800 text-white' : 'text-slate-600 hover:text-slate-900' }}">عربي</a>
            <span class="text-slate-300">|</span>
            <a href="{{ route('lang.switch', 'en') }}"
               class="rounded px-2 py-1 font-medium {{ app()->getLocale() === 'en' ? 'bg-slate-800 text-white' : 'text-slate-600 hover:text-slate-900' }}">EN</a>
        </div>
    </div>

    <div class="w-full max-w-md mt-8">
        <div class="mb-8 text-center">
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">{{ $config['service_title'] }}</h1>
            @if($evina_config)
                <p class="mt-2 inline-block rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-800 ring-1 ring-emerald-200">
                    {{ __('landing.protected') }}
                </p>
            @endif
            <p class="mt-4 text-sm leading-relaxed text-slate-600">
                {{ $config['subscribe_description'] ?? __('landing.get_instant_access') }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm" id="subscribeSection">
            <h2 class="text-center text-lg font-medium text-slate-900">
                {{ $config['subscribe_title'] ?? __('landing.subscribe_now') }}
            </h2>

            <div id="alertMessage" class="mt-4 hidden rounded-lg p-3 text-sm"></div>

            <form id="subscribeForm" class="mt-6">
                <input type="hidden" name="service_name" value="{{ $config['service_name'] }}">
                <button type="submit"
                        id="subscribe_btn"
                        class="w-full rounded-lg bg-slate-900 py-3.5 text-center text-base font-semibold text-white transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2">
                    {{ $config['subscribe_button_text'] ?? __('landing.subscribe_play_now') }}
                </button>
            </form>

            <p class="mt-6 text-center text-xs leading-relaxed text-slate-500">
                {{ __('landing.by_subscribing') }}
                <a href="{{ route('terms') }}" class="text-slate-800 underline hover:text-slate-950">{{ __('landing.terms_conditions') }}</a>
                {{ __('landing.and') }}
                <a href="{{ route('privacy') }}" class="text-slate-800 underline hover:text-slate-950">{{ __('landing.privacy_policy') }}</a>
            </p>

            <hr class="my-6 border-slate-200">
            <p class="text-center text-xs leading-relaxed text-slate-500">
                {{ __('landing.disclaimer.text') }}
            </p>
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
    const bgColor = type === 'error' ? 'bg-red-50 text-red-800 ring-1 ring-red-200' :
                    type === 'success' ? 'bg-emerald-50 text-emerald-800 ring-1 ring-emerald-200' :
                    'bg-slate-50 text-slate-800 ring-1 ring-slate-200';

    alert.className = `mt-4 rounded-lg p-3 text-sm ${bgColor}`;
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
