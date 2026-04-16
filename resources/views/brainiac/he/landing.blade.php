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
{{-- TikTok Pixel — Brainiac campaign (343) --}}
<script>
!function (w, d, t) {
    w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie","holdConsent","revokeConsent","grantConsent"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var r="https://analytics.tiktok.com/i18n/pixel/events.js",o=n&&n.partner;ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=r,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};n=document.createElement("script");n.type="text/javascript",n.async=!0,n.src=r+"?sdkid="+e+"&lib="+t;e=document.getElementsByTagName("script")[0];e.parentNode.insertBefore(n,e)};
    ttq.load('D7BP2JRC77U41AUTQ330');
    ttq.page();
}(window, document, 'ttq');
</script>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --br-navy:  #002659;
        --br-teal:  #00c4d7;
        --br-pink:  #e91e8c;
        --br-pink-d:#c01677;
        --br-gray:  #f2f2f2;
    }
    *, *::before, *::after { box-sizing: border-box; }
    html, body { margin: 0; padding: 0; overflow-x: hidden; overflow-y: auto; }

    /* ── Full-page shell ── */
    .br-page {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        font-family: 'Cairo', Tahoma, Arial, sans-serif;
        background:
        url('{{ asset('images/brainiac/math_pattern_v2.svg') }}') left center / 20% repeat, repeating-linear-gradient(rgb(19, 154, 132), rgb(139, 91, 149), rgb(82, 30, 87) 49.9%, rgb(242, 242, 242) 50.1%, rgb(242, 242, 242) 100%) center center;
        background-position: left center, center center;
    }

    /* ── Top gradient section ── */
    .br-top {
        position: relative;
        padding: 0 0 clamp(24px, 5vw, 48px);
        overflow: hidden;
    }

    /* ── Nav bar (language switcher) ── */
    .br-nav {
        display: flex;
        justify-content: flex-end;
        padding: 14px 20px 0;
    }
    [dir="rtl"] .br-nav { justify-content: flex-start; }
    .br-lang {
        display: flex;
        align-items: center;
        gap: 2px;
        background: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.3);
        border-radius: 6px;
        padding: 4px 6px;
    }
    .br-lang a {
        color: #fff;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 4px;
        transition: background .15s;
    }
    .br-lang a.active { background: rgba(255,255,255,.25); }
    .br-lang a:hover:not(.active) { background: rgba(255,255,255,.12); }

    /* ── Logo ── */
    .br-logo-wrap {
        display: flex;
        justify-content: center;
        padding: clamp(12px, 3vw, 28px) 20px clamp(4px, 1.5vw, 14px);
    }
    .br-logo-wrap img {
        height: clamp(56px, 10vw, 88px);
        width: auto;
        object-fit: contain;
        filter: drop-shadow(0 4px 14px rgba(0,0,0,.35));
    }

    /* ── Subtitle / Description ── */
    .br-subtitle {
        text-align: center;
        color: #fff;
        padding: 0 clamp(16px, 5vw, 48px) clamp(16px, 3vw, 28px);
        max-width: 680px;
        margin: 0 auto;
    }
    .br-subtitle__header {
        display: block;
        font-size: clamp(1rem, 2vw, 1.15rem);
        font-weight: 700;
        letter-spacing: .02em;
        color: rgba(255,255,255,.75);
        margin-bottom: 6px;
        text-shadow: 0 1px 6px rgba(0,0,0,.3);
    }
    .br-subtitle__body {
        display: block;
        font-size: clamp(.9rem, 1.6vw, 1rem);
        font-weight: 400;
        line-height: 1.7;
        color: rgba(255,255,255,.9);
        text-shadow: 0 1px 6px rgba(0,0,0,.25);
    }

    /* ── Prize cards row ── */
    .br-prizes {
        display: flex;
        justify-content: center;
        gap: clamp(12px, 3vw, 28px);
        padding: 0 clamp(16px, 5vw, 48px);
        flex-wrap: wrap;
    }
    .br-prize-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 6px 22px rgba(0,0,0,.18);
        padding: 12px 14px 14px;
        text-align: center;
        width: clamp(140px, 28vw, 200px);
        flex-shrink: 0;
    }
    .br-prize-card img {
        width: 100%;
        height: clamp(90px, 15vw, 130px);
        object-fit: contain;
        display: block;
        margin-bottom: 8px;
    }
    .br-prize-card__label {
        font-size: clamp(.7rem, 1.6vw, .82rem);
        font-weight: 800;
        color: var(--br-navy);
        text-transform: uppercase;
        letter-spacing: .04em;
        margin-bottom: 3px;
    }
    [dir="rtl"] .br-prize-card__label { text-transform: none; letter-spacing: 0; }
    .br-prize-card__sub {
        font-size: clamp(.62rem, 1.3vw, .72rem);
        color: #666;
        line-height: 1.4;
    }

    /* ── CTA section (light gray) ── */
    .br-cta {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: clamp(24px, 5vw, 44px) 20px clamp(20px, 4vw, 36px);
        gap: 14px;
    }
    .br-btn {
        display: block;
        width: min(100%, 340px);
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-family: inherit;
        font-size: 1rem;
        font-weight: 800;
        letter-spacing: .06em;
        padding: 15px 20px;
        text-align: center;
        text-decoration: none;
        transition: background .15s, transform .1s;
    }
    .br-btn:active { transform: scale(.98); }
    .br-btn--primary {
        background: var(--br-pink);
        color: #fff;
        box-shadow: 0 4px 18px rgba(233,30,140,.38);
    }
    .br-btn--primary:hover { background: var(--br-pink-d); }
    .br-btn--outline {
        background: #fff;
        color: var(--br-pink);
        border: 2px solid var(--br-pink);
    }
    .br-btn--outline:hover { background: #fdf0f7; }

    [dir="rtl"] .br-btn { letter-spacing: .01em; }

    /* ── Description text ── */
    .br-desc {
        text-align: center;
        color: #444;
        font-size: clamp(.78rem, 1.8vw, .88rem);
        line-height: 1.7;
        max-width: 520px;
        padding: 0 4px;
    }

    /* ── Previous Winners ── */
    .br-winners {
        width: 100%;
        max-width: 560px;
        margin: 0 auto;
    }
    .br-winners__title {
        text-align: center;
        font-size: clamp(.9rem, 2vw, 1rem);
        font-weight: 800;
        color: #333;
        margin: 0 0 10px;
    }
    .br-winners__list {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .br-winners__item {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,.07);
        padding: 10px 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: .85rem;
        color: #333;
    }
    .br-winners__avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: var(--br-teal);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 800;
        font-size: .85rem;
        flex-shrink: 0;
    }
    .br-winners__name { font-weight: 700; flex: 1; }
    .br-winners__prize { color: var(--br-navy); font-weight: 600; font-size: .78rem; }

    /* ── Footer ── */
    .br-footer {
        background: var(--br-navy);
        text-align: center;
        padding: 14px 16px;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        flex-wrap: wrap;
    }
    .br-footer__brand {
        color: rgba(255,255,255,.85);
        font-size: 11px;
        letter-spacing: .03em;
        font-weight: 600;
    }
    .br-footer__sep { color: rgba(255,255,255,.4); }
    .br-footer a,
    .br-footer span:not(.br-footer__brand):not(.br-footer__sep) {
        color: rgba(255,255,255,.85);
        text-decoration: none;
        font-weight: 600;
        font-size: 11px;
    }
    .br-footer a:hover { text-decoration: underline; }

    @media (max-width: 480px) {
        .br-prizes { gap: 8px; padding: 0 12px; }
        .br-prize-card { width: clamp(110px, 40vw, 160px); padding: 8px 8px 10px; }
        .br-prize-card img { height: clamp(60px, 18vw, 90px); }
        .br-logo-wrap img { height: clamp(44px, 12vw, 68px); }
    }
</style>
@endpush

@section('content')
<div class="br-page" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">

    {{-- ── Top gradient section ── --}}
    <div class="br-top">
        <nav class="br-nav" aria-label="{{ $isAr ? 'اللغة' : 'Language' }}">
            <div class="br-lang">
                @php $currentPath = '/' . request()->path(); @endphp
                <a href="{{ route('lang.switch', 'ar') }}?next={{ urlencode($currentPath) }}" class="{{ $isAr ? 'active' : '' }}">عربي</a>
                <a href="{{ route('lang.switch', 'en') }}?next={{ urlencode($currentPath) }}" class="{{ $isEn ? 'active' : '' }}">EN</a>
            </div>
        </nav>

        <div class="br-logo-wrap">
            <img src="{{ asset('images/brainiac/logo-brainiac.svg') }}" alt="Brainiac" width="220" height="88" loading="eager">
        </div>

        <p class="br-subtitle">
            @if($isEn)
                <span class="br-subtitle__header">By clicking on Subscribe, you agree to the below terms and conditions:</span>
                <span class="br-subtitle__body">Welcome to the Brainiac competition from Zain. Continue and activate your 1 day free trial now. Play now and increase your points to win amazing prizes with only 300 IQD/day after the end of your free trial. To continue, click on the &ldquo;Subscribe&rdquo; button. You can unsubscribe at any time by sending 0 to 2222.</span>
            @else
                <span class="br-subtitle__header">بالنقر على &ldquo;اشترك&rdquo;، أنت توافق على الشروط والأحكام التالية:</span>
                <span class="br-subtitle__body">أهلا بك في مسابقة &ldquo;لغز الملايين&rdquo; من زين! تابع وقم بتفعيل الفترة التجريبية المجانية ليوم واحد الآن. العب الآن وقم بزيادة نقاطك للفوز بجوائز مذهلة بتكلفة 300 د.ع/اليوم فقط بعد انتهاء الفترة التجريبية المجانية. للمتابعة، انقر على &ldquo;اشترك&rdquo;. يمكنك إلغاء الاشتراك في أي وقت بإرسال 0 إلى 2222.</span>
            @endif
        </p>

        <div class="br-prizes">
            {{-- Grand Prize --}}
            <div class="br-prize-card">
                
            <img src="{{ asset('images/brainiac/prize_weekly.png') }}" alt="" width="200" height="130" loading="eager">
                <div class="br-prize-card__label">
                    @if($isEn) Weekly Prize @else جائزة أسبوعية @endif
                </div>
                <div class="br-prize-card__sub">iPhone 17 Pro Max</div>
            </div>

            {{-- Weekly Prize --}}
            <div class="br-prize-card">
            <img src="{{ asset('images/brainiac/prize_grand.png') }}" alt="" width="200" height="130" loading="eager">
                <div class="br-prize-card__label">
                    @if($isEn) Grand Prize @else الجائزة الكبرى @endif
                </div>
                <div class="br-prize-card__sub">
                    @if($isEn) IQD 200,000,000 in Cash @else 200,000,000 د.ع نقداً @endif
                </div>
            </div>
        </div>
    </div>

    {{-- ── CTA section ── --}}
    <div class="br-cta">
        <form id="subscribeForm" style="width:min(100%,340px)">
            <input type="hidden" name="service_name" value="{{ $config['service_name'] }}">
            <button type="submit" id="subscribe_btn" class="br-btn br-btn--primary">
                @if($isEn) REGISTER @else اشترك @endif
            </button>
        </form>

        <a href="{{ route('landing.brainiac-otp') }}" class="br-btn br-btn--outline">
            @if($isEn) LOGIN @else تسجيل الدخول @endif
        </a>

        <p class="br-desc">
            @if($isEn)
                Earn points to win valuable prizes IQD 200,000,000 in Cash
                such as the Grand Prize of IQD 200,000,000 in Cash!
            @else
                احصل على نقاط لفرصة الفوز بجوائز قيمة الجائزة الكبرى
                200,000,000 د.ع نقداً مع أعلى رصيد نقاطاً
            @endif
        </p>

        <!-- {{-- Previous Winners --}}
        <div class="br-winners">
            <h3 class="br-winners__title">
                @if($isEn) Previous Winners @else الفائزون السابقون @endif
            </h3>
            <ul class="br-winners__list">
                <li class="br-winners__item">
                    <div class="br-winners__avatar">A</div>
                    <span class="br-winners__name">Ahmad K.</span>
                    <span class="br-winners__prize">IQD 200,000,000</span>
                </li>
                <li class="br-winners__item">
                    <div class="br-winners__avatar">S</div>
                    <span class="br-winners__name">Sara M.</span>
                    <span class="br-winners__prize">iPhone 17 Pro Max</span>
                </li>
                <li class="br-winners__item">
                    <div class="br-winners__avatar">M</div>
                    <span class="br-winners__name">Mohammed H.</span>
                    <span class="br-winners__prize">IQD 200,000,000</span>
                </li>
            </ul>
        </div> -->
    </div>

    {{-- ── Footer ── --}}
    <footer class="br-footer">
        <span class="br-footer__brand">
            @if($isEn) Brought to you by @else بالتعاون مع @endif
            <strong>Zain IQ</strong>
        </span>
        <span class="br-footer__sep">|</span>
        <span>@if($isEn) T&Cs @else الشروط والأحكام @endif</span>
        @if(!$isAr)
        <span class="br-footer__sep">|</span>
        @endif
        <span>@if($isEn) Privacy @else سياسة الخصوصية @endif</span>
    </footer>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
const config = {
    serviceName: '{{ $config['service_name'] }}',
    enableEvinaFraud: {{ $config['enable_evina_fraud'] ? 'true' : 'false' }},
    @if($evina_config)
    evinaConfig: {!! json_encode($evina_config) !!},
    @endif
};

const otpLandingUrl = @json(route('landing.brainiac-otp'));

let evinaState = { ti: null, ts: null, heRedirectUrl: null };

// ti format: MW-{epoch_seconds}-{4-digit-random}  (matches OTP pages and Evina guide)
function generateTransactionId(prefix) {
    const ts = Math.floor(Date.now() / 1000);
    const rnd = Math.floor(Math.random() * 9000) + 1000;
    return (prefix || 'MW') + '-' + ts + '-' + rnd;
}
// ts must be epoch seconds (not milliseconds)
function generateTimestamp() { return Math.floor(Date.now() / 1000); }

function buildHeRedirectUrl(evinaConfig, ti, ts) {
    // HE redirect uses he_base_url (iq-duel), not the Evina GetScript base_url (iq-dcb)
    const baseUrl = evinaConfig.he_base_url;
    const endpoint = evinaConfig.he_redirect_endpoint;
    const url = baseUrl.replace(/\/$/, '') + '/' + endpoint.replace(/^\//, '');
    const params = new URLSearchParams({
        serviceId:    evinaConfig.service_id,
        spId:         evinaConfig.sp_id,
        shortcode:    evinaConfig.shortcode,
        ti:           ti,
        ts:           ts,
        servicename:  evinaConfig.service_name,
        merchantname: evinaConfig.merchant_name,
        otp_landing:  @json(route($otp_landing_name)),
    });
    return url + '?' + params.toString();
}

function append_script(returnedScript) {
    if (!returnedScript || typeof returnedScript !== 'string') return;
    var el = document.createElement('script');
    el.type = 'text/javascript';
    el.textContent = returnedScript;
    document.head.appendChild(el);
    document.dispatchEvent(new Event('DCBProtectRun'));
}

function exec_anti_fraud() {
    if (!config.enableEvinaFraud || !config.evinaConfig) return;
    evinaState.ti = generateTransactionId(config.evinaConfig.transaction_prefix);
    evinaState.ts = generateTimestamp();
    evinaState.heRedirectUrl = buildHeRedirectUrl(config.evinaConfig, evinaState.ti, evinaState.ts);
    // GetScript uses base_url (iq-dcb) — separate from the HE redirect host
    const scriptUrl = config.evinaConfig.base_url.replace(/\/$/, '') + '/' +
        config.evinaConfig.get_script_endpoint.replace(/^\//, '');
    const params = new URLSearchParams({
        action:       'script',
        ti:           evinaState.ti,
        ts:           evinaState.ts.toString(),
        te:           '#subscribe_btn',
        servicename:  config.evinaConfig.service_name,
        merchantname: config.evinaConfig.merchant_name || 'MediaWorld',
        type:         'he',
    });
    const fullScriptUrl = scriptUrl + '?' + params.toString();
    $.ajax({
        url: fullScriptUrl,
        method: 'GET',
        dataType: 'json',
        success: function(r) { append_script(r.s); },
        error:   function() {
            fetch(fullScriptUrl)
                .then(function(r) { return r.json(); })
                .then(function(data) { append_script(data.s); })
                .catch(function() {});
        },
    });
}

window.addEventListener('load', function () {
    if (config.enableEvinaFraud && config.evinaConfig) exec_anti_fraud();
    // TikTok: ViewContent — user sees the subscription offer
    if (typeof ttq !== 'undefined') {
        ttq.track('ViewContent', {
            contents: [{ content_id: '343', content_type: 'product', content_name: 'Brainiac' }],
            value: 12,
            currency: 'IQD'
        });
    }
});

document.getElementById('subscribeForm').addEventListener('submit', function (e) {
    e.preventDefault();
    // TikTok: InitiateCheckout — user taps subscribe
    if (typeof ttq !== 'undefined') {
        ttq.track('InitiateCheckout', {
            contents: [{ content_id: '343', content_type: 'product', content_name: 'Brainiac' }],
            value: 12,
            currency: 'IQD'
        });
    }
    if (evinaState.heRedirectUrl) {
        window.location.href = evinaState.heRedirectUrl;
    } else {
        window.location.href = otpLandingUrl;
    }
});
</script>
@endpush
