@extends('layouts.zainiqduel')

@section('title', $config['service_title'] ?? __('landing.zain.otp.step_title'))

@push('styles')
<style>
    :root {
        --zain-otp-bg: #e8e8e8;
        --zain-otp-footer: #2e1a47;
        --zain-otp-purple: #2e1a47;
        --zain-otp-pink: #e91e63;
        --zain-otp-pink-dark: #c2185b;
    }
    .zainiqduel-body { margin: 0; background: var(--zain-otp-bg); }
    .zain-otp {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        font-family: 'Cairo', Tahoma, Arial, sans-serif;
        background-color: var(--zain-otp-bg);
        background-image: url('{{ asset('images/zainiqduel/dotted.png') }}');
        background-repeat: repeat;
        background-size: auto;
        padding: 16px 12px 72px;
        box-sizing: border-box;
    }
    .zain-otp__top {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 8px;
    }
    [dir="rtl"] .zain-otp__top { justify-content: flex-start; }
    .zain-otp__lang {
        display: flex;
        flex-direction: column;
        gap: 4px;
        background: #fff;
        border-radius: 6px;
        padding: 6px 8px;
        font-size: 13px;
        font-weight: 700;
        border: 1px solid #ddd;
        box-shadow: 0 1px 3px rgba(0,0,0,.06);
    }
    .zain-otp__lang a {
        color: #333;
        text-decoration: none;
        padding: 4px 10px;
        border-radius: 4px;
        text-align: center;
    }
    .zain-otp__lang a.is-active {
        background: var(--zain-otp-purple);
        color: #fff;
    }
    .zain-otp__center {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .zain-otp__card {
        width: 100%;
        max-width: 420px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 8px 28px rgba(0, 0, 0, 0.08);
        padding: 28px 22px 22px;
        box-sizing: border-box;
    }
    .zain-otp__card h2 {
        margin: 0 0 16px;
        font-size: 1rem;
        font-weight: 600;
        color: #6f6f6f;
        text-align: center;
        line-height: 1.45;
    }
    .zain-otp__progress {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0;
        margin-bottom: 18px;
        padding: 0 12px;
    }
    .zain-otp__step {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 15px;
        color: #fff;
        flex-shrink: 0;
    }
    .zain-otp__step.is-on { background: var(--zain-otp-purple); }
    .zain-otp__step.is-off { background: #b0b0b0; }
    .zain-otp__line {
        flex: 1;
        height: 3px;
        background: #c8c8c8;
        max-width: 120px;
        margin: 0 6px;
        border-radius: 2px;
    }
    .zain-otp__hint {
        text-align: center;
        font-size: 0.9rem;
        color: #666;
        line-height: 1.5;
        margin: 0 0 18px;
    }
    .zain-otp__icon-wrap {
        display: flex;
        justify-content: center;
        margin: 8px 0 20px;
    }
    .zain-otp__icon-circle {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        background: var(--zain-otp-purple);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 14px rgba(46, 26, 71, 0.25);
    }
    .zain-otp__icon-circle img {
        width: 44px;
        height: 44px;
        object-fit: contain;
        filter: brightness(0) invert(1);
    }
    .zain-otp__field label {
        display: block;
        text-align: center;
        font-size: 0.95rem;
        color: #555;
        margin-bottom: 6px;
    }
    .zain-otp__phone-row {
        display: flex;
        align-items: center;
        gap: 8px;
        border-bottom: 2px solid #c5c5c5;
        padding-bottom: 6px;
        margin-bottom: 6px;
    }
    .zain-otp__cc {
        font-size: 1.05rem;
        font-weight: 700;
        color: #444;
        flex-shrink: 0;
    }
    .zain-otp__phone-row input {
        flex: 1;
        border: none;
        outline: none;
        font-size: 1.1rem;
        font-family: inherit;
        background: transparent;
        min-width: 0;
    }
    .zain-otp__price {
        text-align: center;
        font-size: 0.9rem;
        color: #666;
        margin: 14px 0 18px;
    }
    .zain-otp__btn {
        width: 100%;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        background: var(--zain-otp-pink);
        color: #fff;
        font-family: inherit;
        font-size: 1rem;
        font-weight: 800;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        padding: 14px 16px;
        box-shadow: 0 4px 14px rgba(233, 30, 99, 0.35);
        transition: background 0.15s ease;
    }
    .zain-otp__btn:hover:not(:disabled) { background: var(--zain-otp-pink-dark); }
    .zain-otp__btn:disabled { opacity: 0.75; cursor: not-allowed; }
    .zain-otp__login {
        text-align: center;
        margin-top: 16px;
        font-size: 0.9rem;
        color: #666;
    }
    .zain-otp__login a {
        color: #333;
        font-weight: 800;
        text-decoration: none;
    }
    .zain-otp__login a:hover { text-decoration: underline; }
    .zain-otp__fine {
        margin-top: 18px;
        font-size: 0.68rem;
        line-height: 1.5;
        color: #9a9a9a;
        text-align: center;
    }
    .zain-otp__pin-input {
        width: 100%;
        border: none;
        border-bottom: 2px solid #c5c5c5;
        text-align: center;
        font-size: 1.5rem;
        letter-spacing: 0.35em;
        font-weight: 700;
        padding: 10px 8px;
        outline: none;
        font-family: inherit;
        box-sizing: border-box;
    }
    .zain-otp__footer {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 50;
        background: var(--zain-otp-footer);
        text-align: center;
        padding: 14px 12px;
        font-size: 13px;
    }
    .zain-otp__footer a {
        color: #fff;
        text-decoration: none;
        margin: 0 14px;
        font-weight: 600;
    }
    .zain-otp__footer a:hover { text-decoration: underline; }
    .zain-otp__backlink {
        display: block;
        width: 100%;
        margin-top: 12px;
        padding: 8px;
        text-align: center;
        background: none;
        border: none;
        cursor: pointer;
        font-family: inherit;
        font-size: 0.9rem;
        color: #666;
        text-decoration: underline;
    }
    .zain-otp__alert {
        margin-bottom: 12px;
        padding: 10px 12px;
        border-radius: 6px;
        font-size: 0.85rem;
        text-align: center;
    }
    .zain-otp__alert.is-err { background: #fdecea; color: #b42318; }
    .zain-otp__alert.is-ok { background: #e8f5e9; color: #1b5e20; }
    @media (max-width: 480px) {
        .zain-otp__card { padding: 22px 16px 18px; }
    }
</style>
@endpush

@section('content')
<div class="zain-otp">
    <div class="zain-otp__top">
        <nav class="zain-otp__lang" aria-label="Language">
            <a href="{{ route('lang.switch', 'ar') }}" class="{{ app()->getLocale() === 'ar' ? 'is-active' : '' }}">{{ __('landing.zain.lang_ar') }}</a>
            <a href="{{ route('lang.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'is-active' : '' }}">{{ __('landing.zain.lang_en') }}</a>
            <a href="{{ route('lang.switch', 'ku') }}" class="{{ app()->getLocale() === 'ku' ? 'is-active' : '' }}">{{ __('landing.zain.lang_ku') }}</a>
        </nav>
    </div>

    <div class="zain-otp__center">
        <div class="zain-otp__card">
            <div id="phoneSection">
                <h2>{{ __('landing.zain.otp.step_title') }}</h2>
                <div class="zain-otp__progress" aria-hidden="true">
                    <div class="zain-otp__step is-on">1</div>
                    <div class="zain-otp__line"></div>
                    <div class="zain-otp__step is-off">2</div>
                </div>
                <p class="zain-otp__hint">{{ __('landing.zain.otp.step_hint') }}</p>

                <div class="zain-otp__icon-wrap">
                    <div class="zain-otp__icon-circle">
                        <img src="{{ asset('images/zainiqduel/mobile-icon.svg') }}" alt="" width="44" height="44">
                    </div>
                </div>

                <div id="alertMessage" class="zain-otp__alert hidden"></div>

                <form id="phoneForm">
                    <div class="zain-otp__field">
                        <label for="msisdn">{{ __('landing.zain.otp.phone_label') }}</label>
                        <div class="zain-otp__phone-row" dir="ltr">
                            <span class="zain-otp__cc">964</span>
                            <input type="tel" id="msisdn" name="msisdn" placeholder="7xxxxxxxxx" required pattern="7[0-9]{9}" maxlength="10" inputmode="numeric" autocomplete="tel">
                        </div>
                    </div>
                    <p class="zain-otp__price">{{ __('landing.zain.otp.price_line') }}</p>
                    <button type="submit" id="sendOtpBtn" class="zain-otp__btn">{{ __('landing.zain.otp.continue') }}</button>
                </form>

                <p class="zain-otp__login">
                    {{ __('landing.zain.otp.login_prefix') }}
                    <a href="#" id="zainLoginLink">{{ __('landing.zain.otp.login') }}</a>
                </p>
                <p class="zain-otp__fine">{{ __('landing.zain.otp.fine_print') }}</p>
            </div>

            <div id="otpSection" class="hidden">
                <h2>{{ __('landing.zain.otp.step2_title') }}</h2>
                <div class="zain-otp__progress" aria-hidden="true">
                    <div class="zain-otp__step is-off">1</div>
                    <div class="zain-otp__line"></div>
                    <div class="zain-otp__step is-on">2</div>
                </div>
                <p class="zain-otp__hint">{{ __('landing.zain.otp.step2_hint') }}</p>

                <div id="otpAlertMessage" class="zain-otp__alert hidden"></div>

                <form id="otpForm">
                    <div class="zain-otp__field">
                        <label for="pincode">{{ __('landing.otp.enter_code_sent') }}</label>
                        <input type="text" id="pincode" name="pincode" class="zain-otp__pin-input"
                               placeholder="{{ str_replace(':length', $config['pin_length'], __('landing.otp.enter_digit_code')) }}"
                               required maxlength="{{ $config['pin_length'] }}" inputmode="numeric" autocomplete="one-time-code">
                    </div>
                    <button type="submit" id="verifyOtpBtn" class="zain-otp__btn" style="margin-top:18px">{{ __('landing.zain.otp.verify') }}</button>
                </form>

                <button type="button" onclick="backToPhone()" class="zain-otp__backlink">
                    {{ __('landing.otp.back_to_phone') }}
                </button>
            </div>
        </div>
    </div>

    <footer class="zain-otp__footer">
        <a href="{{ route('terms') }}">{{ __('landing.zain.footer_tc') }}</a>
        <a href="{{ route('privacy') }}">{{ __('landing.zain.footer_privacy') }}</a>
    </footer>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
const translations = {
    getOtpCode: @json(__('landing.zain.otp.continue')),
    verifySubscribe: @json(__('landing.zain.otp.verify')),
    sending: @json(__('landing.otp.sending')),
    verifying: @json(__('landing.otp.verifying')),
};

const config = {
    serviceName: '{{ $config['service_name'] }}',
    pinLength: {{ (int) $config['pin_length'] }},
    enableEvinaFraud: {{ $config['enable_evina_fraud'] ? 'true' : 'false' }},
    apiSendPincode: @json(route('api.dcb.send-pincode')),
    apiVerifyPincode: @json(route('api.dcb.verify-pincode')),
    @if($evina_config)
    evinaConfig: {!! json_encode($evina_config) !!},
    @endif
};

async function readJsonOrThrow(response) {
    const text = await response.text();
    try {
        return JSON.parse(text);
    } catch (e) {
        const err = new Error('Bad response');
        err.status = response.status;
        err.bodyPreview = text.slice(0, 200);
        throw err;
    }
}

let evinaState = { ti: null, ts: null };

function generateTransactionId(prefix) {
    const timestamp = Math.floor(Date.now() / 1000);
    const random = Math.floor(Math.random() * 9000) + 1000;
    return prefix + '-' + timestamp + '-' + random;
}
function generateTimestamp() { return Math.floor(Date.now() / 1000); }

function append_script(returnedScript) {
    var scriptElement = document.createElement('script');
    scriptElement.type = 'text/javascript';
    scriptElement.innerHTML = returnedScript;
    document.head.appendChild(scriptElement);
    document.dispatchEvent(new Event('DCBProtectRun'));
}

async function loadEvinaScript() {
    if (!config.enableEvinaFraud || !config.evinaConfig) return;
    try {
        evinaState.ti = generateTransactionId(config.evinaConfig.transaction_prefix);
        evinaState.ts = generateTimestamp();
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
        const response = await fetch(fullScriptUrl);
        if (response.ok) {
            const scriptContent = await response.text();
            append_script(scriptContent);
        }
    } catch (e) {}
}

if (config.enableEvinaFraud && config.evinaConfig) {
    loadEvinaScript();
}

let currentMsisdn = '';

function showAlert(message, type) {
    const alert = document.getElementById('alertMessage');
    alert.textContent = message;
    alert.className = 'zain-otp__alert ' + (type === 'error' ? 'is-err' : 'is-ok');
    alert.classList.remove('hidden');
    setTimeout(function () { alert.classList.add('hidden'); }, 5000);
}

function showOtpAlert(message, type) {
    const alert = document.getElementById('otpAlertMessage');
    alert.textContent = message;
    alert.className = 'zain-otp__alert ' + (type === 'error' ? 'is-err' : 'is-ok');
    alert.classList.remove('hidden');
    setTimeout(function () { alert.classList.add('hidden'); }, 5000);
}

document.getElementById('phoneForm').addEventListener('submit', async function (e) {
    e.preventDefault();
    const msisdnInput = document.getElementById('msisdn').value;
    const msisdn = '964' + msisdnInput;
    if (!/^7[0-9]{9}$/.test(msisdnInput)) {
        showAlert(@json(__('landing.zain.otp.invalid_phone')), 'error');
        return;
    }
    currentMsisdn = msisdn;
    const btn = document.getElementById('sendOtpBtn');
    btn.disabled = true;
    btn.textContent = translations.sending;
    try {
        const response = await fetch(config.apiSendPincode, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ msisdn: msisdn, service: config.serviceName })
        });
        const data = await readJsonOrThrow(response);
        if (data.success) {
            if (data.already_subscribed) {
                window.location.href = @json(route('duel.success', ['already_subscribed' => true]));
                return;
            }
            showAlert(@json(__('landing.otp.code_sent_message')), 'success');
            setTimeout(function () {
                document.getElementById('phoneSection').classList.add('hidden');
                document.getElementById('otpSection').classList.remove('hidden');
                document.getElementById('pincode').focus();
            }, 900);
        } else {
            showAlert(data.message || 'Error', 'error');
            btn.disabled = false;
            btn.textContent = translations.getOtpCode;
        }
    } catch (err) {
        const msg = err.status
            ? (@json(__('landing.otp.request_failed')) + ' (' + err.status + ')')
            : @json(__('landing.otp.network_error'));
        showAlert(msg, 'error');
        btn.disabled = false;
        btn.textContent = translations.getOtpCode;
    }
});

document.getElementById('otpForm').addEventListener('submit', async function (e) {
    e.preventDefault();
    const pincode = document.getElementById('pincode').value;
    const pinRegex = new RegExp('^[0-9]{' + config.pinLength + '}$');
    if (!pinRegex.test(pincode)) {
        showOtpAlert(@json(__('landing.otp.enter_digit_code', ['length' => (string) $config['pin_length']])), 'error');
        return;
    }
    const btn = document.getElementById('verifyOtpBtn');
    btn.disabled = true;
    btn.textContent = translations.verifying;
    try {
        const response = await fetch(config.apiVerifyPincode, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({
                msisdn: currentMsisdn,
                pincode: pincode,
                service: config.serviceName,
                ti: evinaState.ti || null,
                ts: evinaState.ts || null,
            })
        });
        const data = await readJsonOrThrow(response);
        if (data.success) {
            showOtpAlert(@json(__('landing.otp.verify').'…'), 'success');
            setTimeout(function () { window.location.href = @json(route('duel.success')); }, 1200);
        } else {
            showOtpAlert(data.message || 'Invalid code', 'error');
            btn.disabled = false;
            btn.textContent = translations.verifySubscribe;
            document.getElementById('pincode').value = '';
            document.getElementById('pincode').focus();
        }
    } catch (err) {
        const msg = err.status
            ? (@json(__('landing.otp.request_failed')) + ' (' + err.status + ')')
            : @json(__('landing.otp.network_error'));
        showOtpAlert(msg, 'error');
        btn.disabled = false;
        btn.textContent = translations.verifySubscribe;
    }
});

function backToPhone() {
    document.getElementById('otpSection').classList.add('hidden');
    document.getElementById('phoneSection').classList.remove('hidden');
    document.getElementById('pincode').value = '';
    const btn = document.getElementById('sendOtpBtn');
    btn.disabled = false;
    btn.textContent = translations.getOtpCode;
}

document.getElementById('msisdn').addEventListener('input', function (e) {
    e.target.value = e.target.value.replace(/[^0-9]/g, '');
});
document.getElementById('pincode').addEventListener('input', function (e) {
    e.target.value = e.target.value.replace(/[^0-9]/g, '');
});
document.getElementById('zainLoginLink').addEventListener('click', function (e) {
    e.preventDefault();
});
</script>
@endpush
