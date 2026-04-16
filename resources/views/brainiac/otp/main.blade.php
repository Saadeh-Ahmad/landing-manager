@extends('layouts.zainiqduel')

@php
    $locale = app()->getLocale();
    $isAr   = $locale === 'ar';
    $isKu   = $locale === 'ku';
    $isEn   = $locale === 'en';
    $isRtl  = in_array($locale, ['ar', 'ku'], true);
@endphp

@section('title', $config['service_title'] ?? 'Brainiac')

@push('styles')
<style>
    :root {
        --br-navy:  #002659;
        --br-teal:  #00c4d7;
        --br-pink:  #e91e8c;
        --br-pink-d:#c01677;
        --br-gray:  #f2f2f2;
    }
    *, *::before, *::after { box-sizing: border-box; }
    html, body { margin: 0; padding: 0; }

    /* ── Full-page shell ── */
    .br-otp-page {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        font-family: 'Cairo', Tahoma, Arial, sans-serif;
        background:
        url('{{ asset('images/brainiac/math_pattern_v2.svg') }}') left center / 20% repeat, repeating-linear-gradient(rgb(19, 154, 132), rgb(139, 91, 149), rgb(82, 30, 87) 49.9%, rgb(242, 242, 242) 50.1%, rgb(242, 242, 242) 100%) center center;
        background-position: left center, center center;
    }

    /* ── Top gradient section ── */
    .br-otp-top {
        position: relative;
        padding: 0 0 clamp(28px, 8vw, 60px);
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    /* ── Nav ── */
    .br-otp-nav {
        width: 100%;
        display: flex;
        justify-content: flex-end;
        padding: 14px 20px 0;
    }
    [dir="rtl"] .br-otp-nav { justify-content: flex-start; }
    .br-otp-lang {
        display: flex;
        align-items: center;
        gap: 2px;
        background: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.3);
        border-radius: 6px;
        padding: 4px 6px;
    }
    .br-otp-lang a {
        color: #fff;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 4px;
        transition: background .15s;
    }
    .br-otp-lang a.active { background: rgba(255,255,255,.25); }
    .br-otp-lang a:hover:not(.active) { background: rgba(255,255,255,.12); }

    /* ── Logo ── */
    .br-otp-logo {
        padding: clamp(10px, 2.5vw, 20px) 20px 0;
    }
    .br-otp-logo img {
        height: clamp(46px, 8vw, 66px);
        width: auto;
        object-fit: contain;
        filter: drop-shadow(0 3px 10px rgba(0,0,0,.3));
    }

    /* ── Card (centered, overlaps gradient bottom) ── */
    .br-otp-body {
        flex: 1;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        padding: clamp(16px, 4vw, 32px) 16px clamp(80px, 12vw, 100px);
        margin-top: clamp(-60px, -10vw, -40px);
    }
    .br-otp-card {
        width: 100%;
        max-width: 420px;
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 10px 36px rgba(0,0,0,.12);
        padding: clamp(22px, 5vw, 32px) clamp(18px, 5vw, 28px) clamp(18px, 4vw, 24px);
        position: relative;
        z-index: 2;
    }

    /* ── Progress bar ── */
    .br-otp-progress {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 18px;
    }
    .br-otp-step {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 14px;
        color: #fff;
        flex-shrink: 0;
    }
    .br-otp-step.on  { background: var(--br-navy); }
    .br-otp-step.off { background: #b0b0b0; }
    .br-otp-line {
        flex: 1;
        height: 3px;
        background: #d0d0d0;
        max-width: 110px;
        margin: 0 6px;
        border-radius: 2px;
    }

    /* ── Card heading ── */
    .br-otp-card h2 {
        margin: 0 0 6px;
        font-size: clamp(.9rem, 2.2vw, 1rem);
        font-weight: 700;
        color: #555;
        text-align: center;
        line-height: 1.4;
    }
    .br-otp-hint {
        text-align: center;
        font-size: .87rem;
        color: #777;
        margin: 0 0 18px;
        line-height: 1.55;
    }

    /* ── Phone icon ── */
    .br-otp-icon {
        display: flex;
        justify-content: center;
        margin: 0 0 18px;
    }
    .br-otp-icon-circle {
        width: 76px;
        height: 76px;
        border-radius: 50%;
        background: var(--br-navy);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 14px rgba(0,38,89,.3);
    }
    .br-otp-icon-circle svg {
        width: 36px;
        height: 36px;
        fill: #fff;
    }

    /* ── Alert ── */
    .br-otp-alert {
        margin-bottom: 12px;
        padding: 10px 12px;
        border-radius: 6px;
        font-size: .85rem;
        text-align: center;
    }
    .br-otp-alert.err { background: #fdecea; color: #b42318; }
    .br-otp-alert.ok  { background: #e8f5e9; color: #1b5e20; }

    /* ── Phone row ── */
    .br-otp-phone-row {
        display: flex;
        align-items: center;
        gap: 8px;
        border-bottom: 2px solid #c5c5c5;
        padding-bottom: 6px;
        margin-bottom: 6px;
    }
    .br-otp-cc {
        font-size: 1.05rem;
        font-weight: 700;
        color: #444;
        flex-shrink: 0;
    }
    .br-otp-phone-row input {
        flex: 1;
        border: none;
        outline: none;
        font-size: 1.1rem;
        font-family: inherit;
        background: transparent;
        min-width: 0;
    }

    /* ── PIN input ── */
    .br-otp-pin {
        width: 100%;
        border: none;
        border-bottom: 2px solid #c5c5c5;
        text-align: center;
        font-size: 1.5rem;
        letter-spacing: .35em;
        font-weight: 700;
        padding: 10px 8px;
        outline: none;
        font-family: inherit;
    }

    /* ── Price line ── */
    .br-otp-price {
        text-align: center;
        font-size: .85rem;
        color: #666;
        margin: 12px 0 16px;
    }

    /* ── Buttons ── */
    .br-otp-btn {
        width: 100%;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        background: var(--br-pink);
        color: #fff;
        font-family: inherit;
        font-size: .98rem;
        font-weight: 800;
        letter-spacing: .04em;
        text-transform: uppercase;
        padding: 14px 16px;
        box-shadow: 0 4px 14px rgba(233,30,140,.35);
        transition: background .15s;
    }
    [dir="rtl"] .br-otp-btn { text-transform: none; letter-spacing: .01em; }
    .br-otp-btn:hover:not(:disabled) { background: var(--br-pink-d); }
    .br-otp-btn:disabled { opacity: .72; cursor: not-allowed; }

    .br-otp-back {
        display: block;
        width: 100%;
        margin-top: 12px;
        padding: 8px;
        text-align: center;
        background: none;
        border: none;
        cursor: pointer;
        font-family: inherit;
        font-size: .88rem;
        color: #666;
        text-decoration: underline;
    }

    /* ── Login link ── */
    .br-otp-login {
        text-align: center;
        margin-top: 14px;
        font-size: .88rem;
        color: #666;
    }
    .br-otp-login a {
        color: #333;
        font-weight: 800;
        text-decoration: none;
    }
    .br-otp-login a:hover { text-decoration: underline; }

    /* ── Fine print ── */
    .br-otp-fine {
        margin-top: 16px;
        font-size: .66rem;
        color: #aaa;
        text-align: center;
        line-height: 1.5;
    }

    /* ── Footer ── */
    .br-otp-footer {
        position: fixed;
        left: 0; right: 0; bottom: 0;
        z-index: 50;
        background: var(--br-navy);
        text-align: center;
        padding: 13px 12px;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        flex-wrap: wrap;
    }
    .br-otp-footer__brand {
        color: rgba(255,255,255,.8);
        font-size: 11px;
        font-weight: 600;
    }
    .br-otp-footer__sep { color: rgba(255,255,255,.35); }
    .br-otp-footer a,
    .br-otp-footer span:not(.br-otp-footer__brand):not(.br-otp-footer__sep) {
        color: rgba(255,255,255,.85);
        text-decoration: none;
        font-weight: 600;
        font-size: 11px;
    }
    .br-otp-footer a:hover { text-decoration: underline; }

    @media (max-width: 480px) {
        .br-otp-card { padding: 18px 14px 16px; }
    }
</style>
@endpush

@section('content')
<div class="br-otp-page" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">

    {{-- ── Top gradient ── --}}
    <div class="br-otp-top">
        <nav class="br-otp-nav" aria-label="{{ $isAr ? 'اللغة' : 'Language' }}">
            <div class="br-otp-lang">
                @php $currentPath = '/' . request()->path(); @endphp
                <a href="{{ route('lang.switch', 'ar') }}?next={{ urlencode($currentPath) }}" class="{{ $isAr ? 'active' : '' }}">عربي</a>
                <a href="{{ route('lang.switch', 'en') }}?next={{ urlencode($currentPath) }}" class="{{ $isEn ? 'active' : '' }}">EN</a>
            </div>
        </nav>
        <div class="br-otp-logo">
            <img src="{{ asset('images/brainiac/logo-brainiac.svg') }}" alt="Brainiac" width="180" height="66" loading="eager">
        </div>
    </div>

    {{-- ── OTP Card ── --}}
    <div class="br-otp-body">
        <div class="br-otp-card">

            {{-- STEP 1: Phone --}}
            <div id="phoneSection">
                <h2>
                    @if($isEn) Register in 2 steps
                    @elseif($isKu) تۆمارکردن لە ٢ هەنگاودا
                    @else قم بالتسجيل عبر خطوتين سهلتين
                    @endif
                </h2>
                <div class="br-otp-progress" aria-hidden="true">
                    <div class="br-otp-step on">1</div>
                    <div class="br-otp-line"></div>
                    <div class="br-otp-step off">2</div>
                </div>
                <p class="br-otp-hint">
                    @if($isEn)
                        Please enter your mobile number below. A verification code will be sent to you via SMS.
                    @elseif($isKu)
                        تکایە ژمارەی مۆبایلەکەت لەخوارەوە بنووسە. کۆدی تاوەکوکردن ئەنێردرێتە بۆ مەسەجەکەت.
                    @else
                        يرجى إدخال رقم هاتفك المحمول أدناه وسيُرسل لك رمز التفعيل برسالة قصيرة.
                    @endif
                </p>

                <div class="br-otp-icon">
                    <div class="br-otp-icon-circle">
                        {{-- Simple phone icon SVG --}}
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6.6 10.8c1.4 2.8 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.6 21 3 13.4 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1L6.6 10.8z"/>
                        </svg>
                    </div>
                </div>

                <div id="alertMessage" class="br-otp-alert hidden"></div>

                <form id="phoneForm">
                    <div class="br-otp-phone-row" dir="ltr">
                        <span class="br-otp-cc">964</span>
                        <input type="tel" id="msisdn" name="msisdn"
                               placeholder="7xxxxxxxxx"
                               required pattern="7[0-9]{9}" maxlength="10"
                               inputmode="numeric" autocomplete="tel">
                    </div>
                    <p class="br-otp-price">
                        @if($isEn)
                            By clicking "Subscribe" you agree to the
                            <span style="color:var(--br-pink);">Terms &amp; Conditions</span>
                        @else
                            عند النقر على "اشترك" سنقوم بتفعيل اشتراكك
                            <span style="color:var(--br-pink);">الشروط والأحكام</span>
                        @endif
                    </p>
                </form>

                <p class="br-otp-login">
                    @if($isEn) Already have an account?
                    @else هل لديك حساب؟
                    @endif
                    <a href="#" id="loginLink">
                        @if($isEn) Login @else تسجيل الدخول @endif
                    </a>
                </p>

                <p class="br-otp-fine">
                    @if($isEn)
                        This service will cost you IQD 350/Day. You can unsubscribe from this service by sending STOP to 2222. For more information, call our customer care 111.
                    @else
                        ستخصم الخدمة 350 د.ع في اليوم. يمكنك إلغاء الاشتراك من هذه الخدمة عن طريق إرسال STOP إلى 2222، للمزيد من المعلومات اتصل بخدمة العملاء 111.
                    @endif
                </p>
            </div>

            {{-- STEP 2: OTP --}}
            <div id="otpSection" class="hidden">
                <h2>
                    @if($isEn) Enter your verification code
                    @elseif($isKu) کۆدی پشتڕاستکردنەوەکەت بنووسە
                    @else أدخل رمز التحقق
                    @endif
                </h2>
                <div class="br-otp-progress" aria-hidden="true">
                    <div class="br-otp-step off">1</div>
                    <div class="br-otp-line"></div>
                    <div class="br-otp-step on">2</div>
                </div>
                <p class="br-otp-hint">
                    @if($isEn) Enter the code we sent to your phone via SMS
                    @elseif($isKu) کۆدی نێردراو بۆ مۆبایلەکەت بنووسە
                    @else أدخل الرمز الذي أرسلناه إلى هاتفك عبر الرسائل القصيرة
                    @endif
                </p>

                <div id="otpAlertMessage" class="br-otp-alert hidden"></div>

                <form id="otpForm">
                    <input type="text" id="pincode" name="pincode" class="br-otp-pin"
                           placeholder="{{ str_repeat('•', $config['pin_length']) }}"
                           required maxlength="{{ $config['pin_length'] }}"
                           inputmode="numeric" autocomplete="one-time-code">
                </form>

                <button type="button" onclick="backToPhone()" class="br-otp-back">
                    @if($isEn) ← Back to phone number @else ← العودة إلى رقم الهاتف @endif
                </button>
            </div>

            {{-- ── Single shared action button ── --}}
            <button type="button" id="mainActionBtn" class="br-otp-btn" style="margin-top:18px">
                @if($isEn) SUBSCRIBE @else اشترك @endif
            </button>

        </div>{{-- .br-otp-card --}}
    </div>

    {{-- ── Footer ── --}}
    <footer class="br-otp-footer">
        <span class="br-otp-footer__brand">
            @if($isEn) Brought to you by @else بالتعاون مع @endif
            <strong>Zain IQ</strong>
        </span>
        <span class="br-otp-footer__sep">|</span>
        <span>@if($isEn) T&Cs @else الشروط والأحكام @endif</span>
        <span class="br-otp-footer__sep">|</span>
        <span>@if($isEn) Privacy @else سياسة الخصوصية @endif</span>
    </footer>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
const translations = {
    subscribe:  @json($isEn ? 'SUBSCRIBE' : 'اشترك'),
    verify:     @json($isEn ? 'CONFIRM' : 'تأكيد'),
    sending:    @json($isEn ? 'Sending...' : 'جاري الإرسال...'),
    verifying:  @json($isEn ? 'Verifying...' : 'جاري التحقق...'),
};

const config = {
    serviceName:       '{{ $config['service_name'] }}',
    pinLength:         {{ (int) $config['pin_length'] }},
    enableEvinaFraud:  {{ $config['enable_evina_fraud'] ? 'true' : 'false' }},
    apiSendPincode:    @json(route('api.dcb.send-pincode')),
    apiVerifyPincode:  @json(route('api.dcb.verify-pincode')),
    @if($evina_config)
    evinaConfig: {!! json_encode($evina_config) !!},
    @endif
};

async function readJsonOrThrow(response) {
    const text = await response.text();
    try { return JSON.parse(text); }
    catch (e) {
        const err = new Error('Bad response');
        err.status = response.status;
        err.bodyPreview = text.slice(0, 200);
        throw err;
    }
}

let evinaState = { ti: null, ts: null };
let currentMsisdn = '';

function generateTransactionId(prefix) {
    const ts = Math.floor(Date.now() / 1000);
    const rnd = Math.floor(Math.random() * 9000) + 1000;
    return prefix + '-' + ts + '-' + rnd;
}
function generateTimestamp() { return Math.floor(Date.now() / 1000); }

function append_script(returnedScript) {
    console.log('[Evina] append_script called, content length:', returnedScript ? returnedScript.length : 'EMPTY/NULL');
    if (!returnedScript || typeof returnedScript !== 'string') {
        console.warn('[Evina] append_script: no script content — aborting.');
        return;
    }
    var el = document.createElement('script');
    el.type = 'text/javascript';
    el.textContent = returnedScript;
    document.head.appendChild(el);
    console.log('[Evina] Script appended to <head> successfully.');
    document.dispatchEvent(new Event('DCBProtectRun'));
    console.log('[Evina] DCBProtectRun event dispatched.');
}

async function loadEvinaScript() {
    if (!config.enableEvinaFraud || !config.evinaConfig) return;
    try {
        evinaState.ti = generateTransactionId(config.evinaConfig.transaction_prefix);
        evinaState.ts = generateTimestamp();
        const scriptUrl = config.evinaConfig.base_url.replace(/\/$/, '') + '/' +
            config.evinaConfig.get_script_endpoint.replace(/^\//, '');
        const params = new URLSearchParams({
            action:       'script',
            servicename:  config.evinaConfig.service_name,
            merchantname: config.evinaConfig.merchant_name,
            type:         'pin',
            ti:           evinaState.ti,
            ts:           evinaState.ts,
            te:           '#mainActionBtn',
        });
        var fullUrl = scriptUrl + '?' + params.toString();
        console.log('[Evina] GetScript URL:', fullUrl);
        $.ajax({
            url: fullUrl, method: 'GET',
            dataType: 'json',
            success: function(r) {
                console.log('[Evina] GetScript AJAX success, response.s length:', r && r.s ? r.s.length : 'MISSING');
                append_script(r.s);
            },
            error: function(xhr, status, err) {
                console.warn('[Evina] GetScript AJAX error:', status, err, '— falling back to fetch()');
                fetch(fullUrl)
                    .then(function(r) { return r.json(); })
                    .then(function(data) {
                        console.log('[Evina] GetScript fetch fallback success, data.s length:', data && data.s ? data.s.length : 'MISSING');
                        append_script(data.s);
                    })
                    .catch(function(e) { console.error('[Evina] GetScript fetch fallback failed:', e); });
            },
        });
    } catch (e) {}
}

window.addEventListener('load', function () {
    if (config.enableEvinaFraud && config.evinaConfig) loadEvinaScript();
});

function showAlert(id, message, type) {
    const el = document.getElementById(id);
    el.textContent = message;
    el.className = 'br-otp-alert ' + (type === 'error' ? 'err' : 'ok');
    el.classList.remove('hidden');
    setTimeout(() => el.classList.add('hidden'), 5000);
}

const mainBtn = document.getElementById('mainActionBtn');

document.getElementById('phoneForm').addEventListener('submit', async function (e) {
    e.preventDefault();
    const raw = document.getElementById('msisdn').value;
    if (!/^7[0-9]{9}$/.test(raw)) {
        showAlert('alertMessage', @json($isEn ? 'Please enter a valid Zain Iraq number starting with 7.' : 'الرجاء إدخال رقم زين العراقي الصحيح ويبدأ بـ 7 (10 أرقام).'), 'error');
        return;
    }
    currentMsisdn = '964' + raw;
    mainBtn.disabled = true;
    mainBtn.textContent = translations.sending;
    try {
        const response = await fetch(config.apiSendPincode, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ msisdn: currentMsisdn, service: config.serviceName }),
        });
        const data = await readJsonOrThrow(response);
        if (data.success) {
            if (data.already_subscribed) {
                window.location.href = @json(route('brainiac.success', ['already_subscribed' => true]));
                return;
            }
            showAlert('alertMessage', @json($isEn ? 'We sent a code to your phone.' : 'أرسلنا رمزًا إلى هاتفك.'), 'success');
            setTimeout(function () {
                document.getElementById('phoneSection').classList.add('hidden');
                document.getElementById('otpSection').classList.remove('hidden');
                mainBtn.textContent = translations.verify;
                mainBtn.disabled = false;
                document.getElementById('pincode').focus();
            }, 900);
        } else {
            showAlert('alertMessage', data.message || 'Error', 'error');
            mainBtn.disabled = false;
            mainBtn.textContent = translations.subscribe;
        }
    } catch (err) {
        const msg = err.status
            ? (@json($isEn ? 'Server error' : 'خطأ في الخادم') + ' (' + err.status + ')')
            : @json($isEn ? 'Network error. Check your connection.' : 'خطأ في الشبكة. تحقق من الاتصال.');
        showAlert('alertMessage', msg, 'error');
        mainBtn.disabled = false;
        mainBtn.textContent = translations.subscribe;
    }
});

document.getElementById('otpForm').addEventListener('submit', async function (e) {
    e.preventDefault();
    const pincode = document.getElementById('pincode').value;
    const pinRegex = new RegExp('^[0-9]{' + config.pinLength + '}$');
    if (!pinRegex.test(pincode)) {
        showAlert('otpAlertMessage', @json($isEn ? 'Please enter a valid code.' : 'الرجاء إدخال رمز صحيح.'), 'error');
        return;
    }
    mainBtn.disabled = true;
    mainBtn.textContent = translations.verifying;
    try {
        const response = await fetch(config.apiVerifyPincode, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({
                msisdn:  currentMsisdn,
                pincode: pincode,
                service: config.serviceName,
                ti:      evinaState.ti || null,
                ts:      evinaState.ts || null,
            }),
        });
        const data = await readJsonOrThrow(response);
        if (data.success) {
            showAlert('otpAlertMessage', @json($isEn ? 'Subscribed successfully!' : 'تم الاشتراك بنجاح!'), 'success');
            setTimeout(() => { window.location.href = @json(route('brainiac.success')); }, 1200);
        } else {
            showAlert('otpAlertMessage', data.message || 'Invalid code', 'error');
            mainBtn.disabled = false;
            mainBtn.textContent = translations.verify;
            document.getElementById('pincode').value = '';
            document.getElementById('pincode').focus();
        }
    } catch (err) {
        const msg = err.status
            ? (@json($isEn ? 'Server error' : 'خطأ في الخادم') + ' (' + err.status + ')')
            : @json($isEn ? 'Network error.' : 'خطأ في الشبكة.');
        showAlert('otpAlertMessage', msg, 'error');
        mainBtn.disabled = false;
        mainBtn.textContent = translations.verify;
    }
});

mainBtn.addEventListener('click', function () {
    if (!document.getElementById('otpSection').classList.contains('hidden')) {
        document.getElementById('otpForm').requestSubmit();
    } else {
        document.getElementById('phoneForm').requestSubmit();
    }
});

function backToPhone() {
    document.getElementById('otpSection').classList.add('hidden');
    document.getElementById('phoneSection').classList.remove('hidden');
    document.getElementById('pincode').value = '';
    mainBtn.disabled = false;
    mainBtn.textContent = translations.subscribe;
}

document.getElementById('msisdn').addEventListener('input', function (e) {
    e.target.value = e.target.value.replace(/[^0-9]/g, '');
});
document.getElementById('pincode').addEventListener('input', function (e) {
    e.target.value = e.target.value.replace(/[^0-9]/g, '');
});
document.getElementById('loginLink').addEventListener('click', function (e) {
    e.preventDefault();
});
</script>
@endpush
