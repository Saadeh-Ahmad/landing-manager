@extends('layouts.zainiqduel')

@php
    $locale = app()->getLocale();
    $isAr   = $locale === 'ar';
    $isKu   = $locale === 'ku';
    $isEn   = $locale === 'en';
    $isRtl  = in_array($locale, ['ar', 'ku'], true);
@endphp

@section('title', $isEn ? 'Subscription Failed – Zain Duel' : 'فشل الاشتراك – Zain Duel')

@push('styles')
<style>
    :root {
        --zain-otp-bg:       #e8e8e8;
        --zain-otp-footer:   #2e1a47;
        --zain-otp-purple:   #2e1a47;
        --zain-otp-pink:     #e91e63;
        --zain-otp-pink-dark:#c2185b;
        --zain-red:          #ef4444;
        --zain-red-d:        #dc2626;
    }
    *, *::before, *::after { box-sizing: border-box; }
    html, body { margin: 0; padding: 0; overflow-x: hidden; overflow-y: auto; }

    .zainiqduel-body { margin: 0; background: var(--zain-otp-bg); }
    .zain-result {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        font-family: 'Cairo', Tahoma, Arial, sans-serif;
        background-color: var(--zain-otp-bg);
        background-image: url('{{ asset('images/zainiqduel/dotted.png') }}');
        background-repeat: repeat;
        background-size: auto;
        padding: 16px 12px 72px;
    }

    /* ── Language switcher ── */
    .zain-result__top {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 8px;
    }
    [dir="rtl"] .zain-result__top { justify-content: flex-start; }
    .zain-result__lang {
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
    .zain-result__lang a {
        color: #333;
        text-decoration: none;
        padding: 4px 10px;
        border-radius: 4px;
        text-align: center;
    }
    .zain-result__lang a.is-active {
        background: var(--zain-otp-purple);
        color: #fff;
    }

    /* ── Centered layout ── */
    .zain-result__center {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 16px;
    }

    /* ── Main card ── */
    .zain-result__card {
        width: 100%;
        max-width: 420px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 8px 28px rgba(0,0,0,.08);
        padding: 32px 28px 28px;
        text-align: center;
    }

    /* ── Error details card ── */
    .zain-result__error-card {
        width: 100%;
        max-width: 420px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 8px 28px rgba(0,0,0,.08);
        padding: 22px 28px;
        text-align: {{ $isRtl ? 'right' : 'left' }};
    }
    .zain-result__error-title {
        font-size: clamp(.9rem, 2.4vw, 1rem);
        font-weight: 800;
        color: var(--zain-otp-purple);
        margin: 0 0 12px;
        text-align: center;
    }
    .zain-result__error-detail {
        background: #fef2f2;
        border-radius: 8px;
        padding: 12px 14px;
        min-height: 60px;
        font-size: .82rem;
        color: #555;
    }

    /* ── Icon ── */
    .zain-result__icon {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        background: var(--zain-otp-purple);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        box-shadow: 0 4px 14px rgba(46,26,71,.25);
    }
    .zain-result__icon svg {
        width: 44px;
        height: 44px;
    }

    /* ── Text ── */
    .zain-result__title {
        font-size: clamp(1.15rem, 4vw, 1.5rem);
        font-weight: 800;
        color: var(--zain-otp-purple);
        margin: 0 0 10px;
    }
    .zain-result__subtitle {
        font-size: clamp(.82rem, 2.2vw, .92rem);
        color: #666;
        line-height: 1.6;
        margin: 0 0 22px;
    }

    /* ── Button ── */
    .zain-result__btn {
        display: block;
        width: 100%;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        background: var(--zain-otp-pink);
        color: #fff;
        font-family: inherit;
        font-size: 1rem;
        font-weight: 800;
        letter-spacing: .04em;
        text-transform: uppercase;
        padding: 14px 16px;
        text-decoration: none;
        text-align: center;
        box-shadow: 0 4px 14px rgba(233,30,99,.35);
        transition: background .15s;
    }
    .zain-result__btn:hover { background: var(--zain-otp-pink-dark); }
    [dir="rtl"] .zain-result__btn { text-transform: none; letter-spacing: .01em; }

    /* ── Fixed footer ── */
    .zain-result__footer {
        position: fixed;
        left: 0; right: 0; bottom: 0;
        z-index: 50;
        background: var(--zain-otp-footer);
        text-align: center;
        padding: 14px 12px;
        font-size: 13px;
    }
    .zain-result__footer a,
    .zain-result__footer span {
        color: #fff;
        text-decoration: none;
        margin: 0 14px;
        font-weight: 600;
    }
    .zain-result__footer a:hover { text-decoration: underline; }

    @media (max-width: 480px) {
        .zain-result__card,
        .zain-result__error-card { padding: 24px 16px 20px; }
    }
</style>
@endpush

@section('content')
<div class="zain-result" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">

    <div class="zain-result__top">
        <nav class="zain-result__lang" aria-label="{{ $isAr ? 'اللغة' : 'Language' }}">
            <a href="{{ route('lang.switch', 'ar') }}?next={{ urlencode(request()->path() === '/' ? '/' : '/' . request()->path()) }}" class="{{ $isAr ? 'is-active' : '' }}">عربي</a>
            <a href="{{ route('lang.switch', 'en') }}?next={{ urlencode(request()->path() === '/' ? '/' : '/' . request()->path()) }}" class="{{ $isEn ? 'is-active' : '' }}">EN</a>
        </nav>
    </div>

    <div class="zain-result__center">

        <div class="zain-result__card">

            <div class="zain-result__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="15" y1="9" x2="9" y2="15"/>
                    <line x1="9" y1="9" x2="15" y2="15"/>
                </svg>
            </div>

            <h1 class="zain-result__title">
                @if($isEn) Subscription Failed @else فشل الاشتراك @endif
            </h1>

            <p class="zain-result__subtitle">
                @if($isEn)
                    We could not complete your subscription. Please try again.
                @else
                    لم نتمكن من إتمام اشتراكك. يرجى المحاولة مرة أخرى.
                @endif
            </p>

        </div>

        <div class="zain-result__error-card">
            <h2 class="zain-result__error-title">
                @if($isEn) Error Details @else تفاصيل الخطأ @endif
            </h2>
            <div class="zain-result__error-detail" id="zain-error-detail">
                <p style="text-align:center;color:#aaa;margin:0;">
                    @if($isEn) Loading error details... @else جاري تحميل تفاصيل الخطأ... @endif
                </p>
            </div>
        </div>

    </div>

    <footer class="zain-result__footer">
        @if($isAr)
            <span>{{ __('landing.zain.footer_privacy') }}</span>
            <span>{{ __('landing.zain.footer_tc') }}</span>
        @else
            <span>{{ __('landing.zain.footer_tc') }}</span>
            <span>{{ __('landing.zain.footer_privacy') }}</span>
        @endif
    </footer>
</div>

<script>
window.addEventListener('load', function () {
    const searchParams = new URLSearchParams(window.location.search);
    const box = document.getElementById('zain-error-detail');
    box.innerHTML = '';

    if (!searchParams.toString()) {
        box.innerHTML = '<p style="text-align:center;color:#aaa;font-size:.82rem;margin:0;">' + @json($isEn ? 'No error details available.' : 'لا تتوفر تفاصيل للخطأ.') + '</p>';
        return;
    }

    const container = document.createElement('div');
    container.style.cssText = 'display:flex;flex-direction:column;gap:6px;';

    for (const [key, value] of searchParams.entries()) {
        const row = document.createElement('p');
        row.style.cssText = 'margin:0;font-size:.82rem;color:#374151;';
        const label = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        row.innerHTML = '<strong style="color:#dc2626;">' + label + ':</strong> <span style="color:#555;">' + decodeURIComponent(value) + '</span>';
        container.appendChild(row);
    }

    box.appendChild(container);
});
</script>
@endsection
