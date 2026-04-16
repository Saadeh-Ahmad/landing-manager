@extends('layouts.zainiqduel')

@php
    $locale = app()->getLocale();
    $isAr   = $locale === 'ar';
    $isKu   = $locale === 'ku';
    $isEn   = $locale === 'en';
    $isRtl  = in_array($locale, ['ar', 'ku'], true);
@endphp

@section('title', $isEn ? 'Subscription Activated – Zain Duel' : 'تم التفعيل – Zain Duel')

@push('styles')
<style>
    :root {
        --zain-otp-bg:       #e8e8e8;
        --zain-otp-footer:   #2e1a47;
        --zain-otp-purple:   #2e1a47;
        --zain-otp-pink:     #e91e63;
        --zain-otp-pink-dark:#c2185b;
        --zain-green:        #22c55e;
        --zain-green-d:      #16a34a;
    }
    *, *::before, *::after { box-sizing: border-box; }
    html, body { margin: 0; padding: 0; }

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

    /* ── Centered card ── */
    .zain-result__center {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .zain-result__card {
        width: 100%;
        max-width: 420px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 8px 28px rgba(0,0,0,.08);
        padding: 32px 28px 28px;
        text-align: center;
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

    /* ── Already subscribed notice ── */
    .zain-result__notice {
        background: #eff6ff;
        border: 1.5px solid #bfdbfe;
        border-radius: 8px;
        padding: 10px 14px;
        margin-bottom: 20px;
        font-size: .82rem;
        color: #1e40af;
        line-height: 1.6;
        text-align: {{ $isRtl ? 'right' : 'left' }};
    }

    /* ── Active badge ── */
    .zain-result__badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #dcfce7;
        border-radius: 999px;
        padding: 6px 18px;
        font-size: .82rem;
        font-weight: 700;
        color: var(--zain-green-d);
        margin-bottom: 24px;
    }
    .zain-result__badge span {
        display: block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--zain-green);
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
        .zain-result__card { padding: 24px 16px 20px; }
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
                    <polyline points="7 12.5 10.5 16 17 9"/>
                </svg>
            </div>

            <h1 class="zain-result__title">
                @if($isEn) Subscription Activated! @else تم تفعيل الاشتراك! @endif
            </h1>

            <p class="zain-result__subtitle">
                @if($isEn)
                    Welcome to Zain Duel! Test your skills and win amazing prizes.
                @else
                    مرحباً بك في Zain Duel! اختبر مهاراتك وفوز بجوائز رائعة.
                @endif
            </p>

            @if(!empty($alreadySubscribed))
            <div class="zain-result__notice">
                {{ $isEn
                    ? 'You are already subscribed to this service. Your subscription remains active.'
                    : 'أنت مشترك بالفعل في هذه الخدمة. اشتراكك لا يزال نشطاً.' }}
            </div>
            @endif

            <div class="zain-result__badge">
                <span></span>
                @if($isEn) ACTIVE @else نشط @endif
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
@endsection
