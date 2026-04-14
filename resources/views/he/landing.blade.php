@extends('layouts.zainiqduel')

@php
    $isEn = app()->getLocale() === 'en';
    $ribbonStripeEn = in_array(app()->getLocale(), ['en', 'ku'], true);
@endphp

@section('title', $config['service_title'] ?? __('landing.zain.he.cta'))

@push('styles')
{{-- Original operator stylesheet (downloaded for parity reference; not linked — would conflict with Laravel markup): public/vendor/zainiqduel/main.min.css from https://www.zainiqduel.com/static/css/main.min.css --}}
@if($isEn)
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
@endif
<style>
    :root {
        --zain-he-bg: #1a0033;
        --zain-he-bg-mid: #2a0a4a;
        --zain-he-bg-deep: #0d001f;
        --zain-he-pink: #e6007e;
        --zain-he-pink-dark: #b80062;
        --zain-he-teal: #00bcd4;
        --zain-he-green: #4caf50;
    }
    /* HE only: no vertical page scroll — shell fits the viewport */
    html:has(.zain-he),
    body.zainiqduel-body:has(.zain-he) {
        height: 100%;
        margin: 0;
        overflow: hidden;
        overscroll-behavior: none;
    }
    .zainiqduel-body { margin: 0; }
    .zain-he {
        position: relative;
        box-sizing: border-box;
        height: 100vh;
        max-height: 100vh;
        display: flex;
        flex-direction: column;
        font-family: 'Cairo', Tahoma, Arial, sans-serif;
        color: #fff;
        /* get_started_bg.jpg — refresh from zainiqduel static/skins/skin_13/images/ */
        background: url('{{ asset('images/zainiqduel/get_started_bg.jpg') }}') center center / cover no-repeat;
        overflow: hidden;
    }
    @supports (height: 100dvh) {
        .zain-he {
            height: 100dvh;
            max-height: 100dvh;
        }
    }
    .zain-he--en-font .zain-he__headline h1 {
        font-family: 'Montserrat', 'Segoe UI', Arial, sans-serif;
    }
    .zain-he__top {
        position: relative;
        z-index: 5;
        display: flex;
        justify-content: flex-end;
        padding: clamp(8px, 2vh, 14px) 18px 0;
        flex-shrink: 0;
    }
    [dir="rtl"] .zain-he__top { justify-content: flex-start; }
    .zain-he__lang {
        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 4px;
        background: rgba(0, 0, 0, 0.28);
        border-radius: 6px;
        padding: 6px 8px;
        font-size: 14px;
        font-weight: 600;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .zain-he__lang a {
        color: #fff;
        text-decoration: none;
        padding: 4px 10px;
        border-radius: 4px;
        text-align: center;
    }
    .zain-he__lang a.is-active {
        background: #fff;
        color: var(--zain-he-bg);
    }
    .zain-he__lang a:hover:not(.is-active) { background: rgba(255, 255, 255, 0.08); }

    /* Headline: centered; EN = two weights like reference */
    .zain-he__headline {
        position: relative;
        z-index: 4;
        text-align: center;
        padding: clamp(2px, 1vh, 8px) 16px clamp(2px, 1vh, 8px);
        max-width: 960px;
        margin: 0 auto;
        width: 100%;
        box-sizing: border-box;
        flex-shrink: 0;
    }
    .zain-he__headline h1 {
        margin: 0;
        font-size: clamp(1.2rem, 3.6vw, 2.25rem);
        font-weight: 800;
        line-height: 1.32;
        letter-spacing: 0.02em;
        text-shadow: 0 2px 18px rgba(0, 0, 0, 0.4);
    }
    .zain-he__hl1 {
        font-weight: 500;
        display: block;
    }
    .zain-he__hl2 {
        font-weight: 800;
        display: block;
        margin-top: 4px;
    }

    /* Character left / money right: flex main axis must stay LTR even when html dir=rtl */
    .zain-he__row {
        position: relative;
        z-index: 3;
        flex: 1 1 auto;
        min-height: 0;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        gap: clamp(8px, 2vw, 28px);
        padding: 4px clamp(8px, 2.5vw, 24px) clamp(48px, 8vh, 88px);
        max-width: none;
        margin: 0 auto;
        width: 100%;
        box-sizing: border-box;
        direction: ltr;
        unicode-bidi: isolate;
        overflow: hidden;
    }
    .zain-he__visual {
        flex: 1 1 0;
        min-width: 0;
        max-width: none;
        width: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .zain-he__visual-stage {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        max-width: min(720px, 46vw);
        aspect-ratio: 1;
        margin: 0 auto;
    }
    /* Abstract “landmark” ring — pure CSS, no hero screenshot */
    .zain-he__photo-ring {
        position: absolute;
        inset: 2%;
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
        border: 2px solid rgba(255, 255, 255, 0.12);
        box-shadow:
            inset 0 0 40px rgba(0, 188, 212, 0.15),
            0 0 0 1px rgba(230, 0, 126, 0.2);
    }
    .zain-he__ring-thumb {
        position: absolute;
        width: 14%;
        height: 18%;
        top: 50%;
        left: 50%;
        border-radius: 6px;
        border: 2px solid rgba(255, 255, 255, 0.35);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.35);
        transform-origin: center center;
        opacity: 0.92;
    }
    .zain-he__ring-thumb:nth-child(1) { transform: translate(-50%, -50%) rotate(0deg) translate(0, min(-36vw, -210px)) rotate(0deg); background: linear-gradient(135deg, #c9a227, #f4e4a6 45%, #8b6914); }
    .zain-he__ring-thumb:nth-child(2) { transform: translate(-50%, -50%) rotate(36deg) translate(0, min(-36vw, -210px)) rotate(-36deg); background: linear-gradient(160deg, #5d4037, #a1887f, #3e2723); }
    .zain-he__ring-thumb:nth-child(3) { transform: translate(-50%, -50%) rotate(72deg) translate(0, min(-36vw, -210px)) rotate(-72deg); background: linear-gradient(180deg, #1565c0, #42a5f5, #0d47a1); }
    .zain-he__ring-thumb:nth-child(4) { transform: translate(-50%, -50%) rotate(108deg) translate(0, min(-36vw, -210px)) rotate(-108deg); background: linear-gradient(120deg, #2e7d32, #81c784, #1b5e20); }
    .zain-he__ring-thumb:nth-child(5) { transform: translate(-50%, -50%) rotate(144deg) translate(0, min(-36vw, -210px)) rotate(-144deg); background: linear-gradient(200deg, #6a1b9a, #ce93d8, #4a148c); }
    .zain-he__ring-thumb:nth-child(6) { transform: translate(-50%, -50%) rotate(180deg) translate(0, min(-36vw, -210px)) rotate(-180deg); background: linear-gradient(90deg, #546e7a, #b0bec5, #37474f); }
    .zain-he__ring-thumb:nth-child(7) { transform: translate(-50%, -50%) rotate(216deg) translate(0, min(-36vw, -210px)) rotate(-216deg); background: linear-gradient(145deg, #e65100, #ffcc80, #bf360c); }
    .zain-he__ring-thumb:nth-child(8) { transform: translate(-50%, -50%) rotate(252deg) translate(0, min(-36vw, -210px)) rotate(-252deg); background: linear-gradient(170deg, #00838f, #4dd0e1, #006064); }
    .zain-he__ring-thumb:nth-child(9) { transform: translate(-50%, -50%) rotate(288deg) translate(0, min(-36vw, -210px)) rotate(-288deg); background: linear-gradient(110deg, #ad1457, #f48fb1, #880e4f); }
    .zain-he__ring-thumb:nth-child(10) { transform: translate(-50%, -50%) rotate(324deg) translate(0, min(-36vw, -210px)) rotate(-324deg); background: linear-gradient(130deg, #4527a0, #b39ddb, #311b92); }
    .zain-he__visual img {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 100%;
        max-height: min(72vh, 720px);
        height: auto;
        object-fit: contain;
        filter: drop-shadow(0 14px 36px rgba(0, 0, 0, 0.5));
    }
    .zain-he__right {
        flex: 1 1 0;
        min-width: 0;
        max-width: none;
        width: 50%;
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 0;
        padding: 8px 0;
    }
    /* Large faint mandala behind cash */
    .zain-he__mandala {
        position: absolute;
        inset: -22% -24%;
        background:
            repeating-conic-gradient(from 0deg at 50% 50%, transparent 0deg 5deg, rgba(230, 0, 126, 0.09) 5deg 6deg, transparent 6deg 12deg, rgba(40, 30, 100, 0.12) 12deg 13deg, transparent 13deg 24deg),
            radial-gradient(circle at 50% 50%, rgba(30, 0, 55, 0.45) 0%, transparent 58%);
        pointer-events: none;
        z-index: 0;
    }
    .zain-he__right::before {
        content: '';
        position: absolute;
        inset: -6% -10%;
        background:
            conic-gradient(from 35deg at 55% 45%, transparent 0deg 8deg, rgba(230, 0, 126, 0.16) 8deg 16deg, transparent 16deg 28deg, rgba(76, 175, 80, 0.12) 28deg 36deg, transparent 36deg 52deg, rgba(57, 73, 171, 0.14) 52deg 62deg, transparent 62deg 360deg),
            conic-gradient(from 190deg at 40% 60%, transparent 0deg 12deg, rgba(230, 0, 126, 0.08) 12deg 22deg, transparent 22deg 360deg);
        opacity: 0.92;
        pointer-events: none;
        z-index: 0;
    }
    .zain-he__right-bg {
        position: absolute;
        inset: -4%;
        background: url('{{ asset('images/zainiqduel/generic_bg.svg') }}') center center / 120% auto no-repeat;
        opacity: 0.12;
        pointer-events: none;
        z-index: 0;
    }
    .zain-he__prize-img {
        position: relative;
        z-index: 2;
        width: min(100%, min(760px, 52vw));
        max-width: 100%;
        height: auto;
        object-fit: contain;
        filter: drop-shadow(0 12px 28px rgba(0, 0, 0, 0.55));
        margin-bottom: 16px;
    }
    .zain-he__cta-wrap { position: relative; z-index: 2; }
    /* EN: white bar + pink text (zainiqduel EN). AR/KU: solid pink + white text (zainiqduel AR) */
    .zain-he__cta {
        appearance: none;
        border: none;
        cursor: pointer;
        background: #fff;
        color: var(--zain-he-pink);
        font-family: inherit;
        font-size: clamp(0.95rem, 2.1vw, 1.08rem);
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        padding: 14px clamp(40px, 8vw, 72px);
        border-radius: 3px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.25);
        transition: background 0.15s ease, color 0.15s ease, transform 0.1s ease, box-shadow 0.15s ease;
    }
    html[lang="ar"] .zain-he__cta,
    html[lang="ku"] .zain-he__cta {
        background: var(--zain-he-pink);
        color: #fff;
        text-transform: none;
        letter-spacing: 0.02em;
        box-shadow: 0 6px 24px rgba(230, 0, 126, 0.45);
    }
    .zain-he__cta:hover {
        background: #fff7fb;
        color: var(--zain-he-pink-dark);
        box-shadow: 0 6px 28px rgba(230, 0, 126, 0.2);
    }
    html[lang="ar"] .zain-he__cta:hover,
    html[lang="ku"] .zain-he__cta:hover {
        background: var(--zain-he-pink-dark);
        color: #fff;
        box-shadow: 0 8px 28px rgba(230, 0, 126, 0.5);
    }
    .zain-he__cta:active { transform: scale(0.98); }

    /* https://www.zainiqduel.com/ext/skin/free-trial-stripe_AR.png (+ EN variant) */
    .zain-he__ribbon {
        position: fixed;
        z-index: 30;
        right: max(-12px, -1.5vmin);
        bottom: clamp(40px, 7vh, 72px);
        line-height: 0;
        pointer-events: none;
        filter: drop-shadow(0 6px 16px rgba(0, 0, 0, 0.35));
    }
    .zain-he__ribbon img {
        display: block;
        width: clamp(168px, 36vmin, 275px);
        height: auto;
    }
    [dir="rtl"] .zain-he__ribbon {
        right: auto;
    }

    .zain-he__footer {
        position: relative;
        z-index: 5;
        text-align: center;
        padding: clamp(6px, 1.5vh, 12px) 12px clamp(8px, 2vh, 16px);
        font-size: 11px;
        font-weight: 300;
        letter-spacing: 0.04em;
        flex-shrink: 0;
        background-color: #25013f;
    }
    .zain-he__footer a {
        color: rgba(255, 255, 255, 0.88);
        text-decoration: underline;
        text-underline-offset: 2px;
        text-decoration-thickness: 1px;
        margin: 0 14px;
    }

    @media (max-width: 768px) {
        .zain-he__row {
            flex-direction: column;
            padding-bottom: clamp(40px, 10vh, 72px);
        }
        .zain-he__visual,
        .zain-he__right {
            width: 100%;
            max-width: 100%;
        }
        .zain-he__visual-stage {
            width: 100%;
            max-width: min(96vw, 520px);
            max-height: min(52vh, 520px);
        }
        .zain-he__visual img {
            max-height: min(48vh, 480px);
            width: 100%;
        }
        .zain-he__prize-img {
            width: min(100%, min(96vw, 640px));
            max-height: min(34vh, 360px);
            margin-bottom: 10px;
        }
        [dir="rtl"] .zain-he__top { justify-content: center; }
        .zain-he__lang { flex-direction: row; flex-wrap: wrap; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div class="zain-he {{ $isEn ? 'zain-he--en-font' : '' }}">
    <header class="zain-he__top">
        <nav class="zain-he__lang" aria-label="Language">
            <a href="{{ route('lang.switch', 'ar') }}" class="{{ app()->getLocale() === 'ar' ? 'is-active' : '' }}">{{ __('landing.zain.lang_ar') }}</a>
            <a href="{{ route('lang.switch', 'en') }}" class="{{ app()->getLocale() === 'en' ? 'is-active' : '' }}">{{ __('landing.zain.lang_en') }}</a>
            <a href="{{ route('lang.switch', 'ku') }}" class="{{ app()->getLocale() === 'ku' ? 'is-active' : '' }}">{{ __('landing.zain.lang_ku') }}</a>
        </nav>
    </header>

    <div class="zain-he__headline">
        <h1>
            @if($isEn)
                <span class="zain-he__hl1">{{ __('landing.zain.he.headline_l1') }}</span>
                <span class="zain-he__hl2">{{ __('landing.zain.he.headline_l2') }}</span>
            @elseif(app()->getLocale() === 'ku')
                {{ trans('landing.zain.he.headline_full', [], 'ar') }}
            @else
                {{ __('landing.zain.he.headline_full') }}
            @endif
        </h1>
    </div>

    <main class="zain-he__row">
        <div class="zain-he__visual">
            <div class="zain-he__visual-stage">
                <img src="{{ asset('images/zainiqduel/Get-Started_tablet.png') }}" alt="" width="643" height="549" loading="eager" decoding="async">
            </div>
        </div>
        <div class="zain-he__right">
            <div class="zain-he__mandala" aria-hidden="true"></div>
            <div class="zain-he__right-bg" aria-hidden="true"></div>
            <img class="zain-he__prize-img" src="{{ asset('images/zainiqduel/Get-Started_prizes_lossy.png') }}" alt="" width="592" height="287" loading="eager">
            <div class="zain-he__cta-wrap">
                <form id="subscribeForm">
                    <input type="hidden" name="service_name" value="{{ $config['service_name'] }}">
                    <button type="submit" id="subscribe_btn" class="zain-he__cta">{{ __('landing.zain.he.cta') }}</button>
                </form>
            </div>
        </div>
    </main>

    <div class="zain-he__ribbon">
        <img src="{{ $ribbonStripeEn ? asset('images/zainiqduel/free-trial-stripe_EN.png') : asset('images/zainiqduel/free-trial-stripe_AR.png') }}" alt="{{ __('landing.zain.he.ribbon') }}" width="275" height="242" loading="lazy" decoding="async">
    </div>

    <footer class="zain-he__footer">
        @if(app()->getLocale() === 'ar')
            <a href="{{ route('privacy') }}">{{ __('landing.zain.footer_privacy') }}</a>
            <a href="{{ route('terms') }}">{{ __('landing.zain.footer_tc') }}</a>
        @else
            <a href="{{ route('terms') }}">{{ __('landing.zain.footer_tc') }}</a>
            <a href="{{ route('privacy') }}">{{ __('landing.zain.footer_privacy') }}</a>
        @endif
    </footer>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/uuid@latest/dist/umd/uuidv4.min.js"></script>
<script>
const config = {
    serviceName: '{{ $config['service_name'] }}',
    enableEvinaFraud: {{ $config['enable_evina_fraud'] ? 'true' : 'false' }},
    @if($evina_config)
    evinaConfig: {!! json_encode($evina_config) !!},
    @endif
};

let evinaState = { ti: null, ts: null, heRedirectUrl: null };

function generateTransactionId() { return uuidv4(); }
function generateTimestamp() { return new Date().getTime(); }

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

function append_script(returnedScript) {
    var scriptElement = document.createElement('script');
    scriptElement.type = 'text/javascript';
    scriptElement.innerHTML = returnedScript;
    document.head.appendChild(scriptElement);
    document.dispatchEvent(new Event('DCBProtectRun'));
}

function exec_anti_fraud() {
    if (!config.enableEvinaFraud || !config.evinaConfig) return;
    evinaState.ti = generateTransactionId();
    evinaState.ts = generateTimestamp();
    evinaState.heRedirectUrl = buildHeRedirectUrl(config.evinaConfig, evinaState.ti, evinaState.ts);
    const scriptUrl = config.evinaConfig.base_url.replace(/\/$/, '') + '/' +
        config.evinaConfig.get_script_endpoint.replace(/^\//, '');
    const css_selector = '#subscribe_btn';
    const scriptParams = new URLSearchParams({
        action: 'script',
        ti: evinaState.ti,
        ts: evinaState.ts.toString(),
        te: css_selector,
        servicename: config.evinaConfig.service_name,
        merchantname: config.evinaConfig.merchant_name || 'MediaWorld',
        type: 'he'
    });
    const fullScriptUrl = scriptUrl + '?' + scriptParams.toString();
    $.ajax({
        url: fullScriptUrl,
        method: 'GET',
        success: function (response) {
            var script_data = response.s;
            append_script(script_data);
        },
        error: function () {
            fetch(fullScriptUrl)
                .then(function (r) { return r.text(); })
                .then(function (t) { append_script(t); })
                .catch(function () {});
        }
    });
}

window.addEventListener('load', function () {
    if (config.enableEvinaFraud && config.evinaConfig) {
        exec_anti_fraud();
    }
});

$('#subscribeForm').on('submit', function (e) {
    e.preventDefault();
    if (evinaState.heRedirectUrl) {
        window.location.href = evinaState.heRedirectUrl;
    }
});
</script>
@endpush
