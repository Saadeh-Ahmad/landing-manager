@extends('layouts.zainiqduel')

@php
    $locale = app()->getLocale();
    $isAr   = $locale === 'ar';
    $isKu   = $locale === 'ku';
    $isEn   = $locale === 'en';
    $isRtl  = in_array($locale, ['ar', 'ku'], true);
@endphp

@section('title', $isEn ? 'Subscription Activated – Brainiac' : 'تم التفعيل – Brainiac')

@push('styles')
<style>
    :root {
        --br-navy:  #002659;
        --br-teal:  #00c4d7;
        --br-pink:  #e91e8c;
        --br-pink-d:#c01677;
        --br-green: #22c55e;
        --br-green-d:#16a34a;
    }
    *, *::before, *::after { box-sizing: border-box; }
    html, body { margin: 0; padding: 0; }

    .br-result-page {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        font-family: 'Cairo', Tahoma, Arial, sans-serif;
        background:
            url('{{ asset('images/brainiac/math_pattern_v2.svg') }}') left center / 20% repeat,
            repeating-linear-gradient(
                rgb(19, 154, 132),
                rgb(139, 91, 149),
                rgb(82, 30, 87) 49.9%,
                rgb(242, 242, 242) 50.1%,
                rgb(242, 242, 242) 100%
            ) center center;
        background-position: left center, center center;
    }

    /* ── Top gradient section ── */
    .br-result-top {
        padding: 0 0 clamp(24px, 6vw, 48px);
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .br-result-nav {
        width: 100%;
        display: flex;
        justify-content: flex-end;
        padding: 14px 20px 0;
    }
    [dir="rtl"] .br-result-nav { justify-content: flex-start; }

    .br-result-logo {
        padding: clamp(10px, 2.5vw, 20px) 20px 0;
    }
    .br-result-logo img {
        height: clamp(46px, 8vw, 68px);
        width: auto;
        filter: drop-shadow(0 4px 14px rgba(0,0,0,.35));
    }

    /* ── Card section ── */
    .br-result-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: clamp(20px, 5vw, 48px) 20px clamp(24px, 5vw, 40px);
    }

    .br-result-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0,0,0,.18);
        padding: clamp(24px, 6vw, 44px) clamp(20px, 5vw, 40px);
        text-align: center;
        width: min(100%, 400px);
    }

    .br-result-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #dcfce7;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 18px;
    }
    .br-result-icon svg {
        width: 44px;
        height: 44px;
    }

    .br-result-title {
        font-size: clamp(1.2rem, 4vw, 1.6rem);
        font-weight: 800;
        color: var(--br-navy);
        margin: 0 0 10px;
    }

    .br-result-subtitle {
        font-size: clamp(.82rem, 2.2vw, .95rem);
        color: #555;
        line-height: 1.7;
        margin: 0 0 20px;
    }

    .br-result-notice {
        background: #eff6ff;
        border: 1.5px solid #bfdbfe;
        border-radius: 10px;
        padding: 12px 16px;
        margin-bottom: 20px;
        font-size: .82rem;
        color: #1e40af;
        line-height: 1.6;
        text-align: {{ $isRtl ? 'right' : 'left' }};
    }

    .br-result-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #dcfce7;
        border-radius: 999px;
        padding: 6px 18px;
        font-size: .82rem;
        font-weight: 700;
        color: var(--br-green-d);
        margin-bottom: 22px;
    }
    .br-result-badge span { display: block; width: 8px; height: 8px; border-radius: 50%; background: var(--br-green); }

    .br-result-btn {
        display: block;
        width: 100%;
        background: var(--br-pink);
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 14px 20px;
        font-family: inherit;
        font-size: .95rem;
        font-weight: 800;
        cursor: pointer;
        text-decoration: none;
        text-align: center;
        box-shadow: 0 4px 18px rgba(233,30,140,.35);
        transition: background .15s;
    }
    .br-result-btn:hover { background: var(--br-pink-d); }

    /* ── Footer ── */
    .br-result-footer {
        background: var(--br-navy);
        text-align: center;
        padding: 14px 16px;
        font-size: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        flex-wrap: wrap;
        color: rgba(255,255,255,.85);
        font-weight: 600;
    }
    .br-result-footer__sep { color: rgba(255,255,255,.4); }
</style>
@endpush

@section('content')
<div class="br-result-page" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">

    <div class="br-result-top">
        <nav class="br-result-nav" aria-label="{{ $isAr ? 'اللغة' : 'Language' }}">
            <div style="display:flex;align-items:center;gap:2px;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.3);border-radius:6px;padding:4px 6px;">
                @php $currentPath = '/' . request()->path(); @endphp
                <a href="{{ route('lang.switch', 'ar') }}?next={{ urlencode($currentPath) }}" style="color:#fff;text-decoration:none;font-size:13px;font-weight:700;padding:4px 10px;border-radius:4px;{{ $isAr ? 'background:rgba(255,255,255,.25)' : '' }}">عربي</a>
                <a href="{{ route('lang.switch', 'en') }}?next={{ urlencode($currentPath) }}" style="color:#fff;text-decoration:none;font-size:13px;font-weight:700;padding:4px 10px;border-radius:4px;{{ $isEn ? 'background:rgba(255,255,255,.25)' : '' }}">EN</a>
            </div>
        </nav>
        <div class="br-result-logo">
            <img src="{{ asset('images/brainiac/logo-brainiac.svg') }}" alt="Brainiac" width="200" height="68" loading="eager">
        </div>
    </div>

    <div class="br-result-body">
        <div class="br-result-card">
            <div class="br-result-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <polyline points="7 12.5 10.5 16 17 9"/>
                </svg>
            </div>

            <h1 class="br-result-title">
                @if($isEn) Subscription Activated! @else تم تفعيل الاشتراك! @endif
            </h1>

            <p class="br-result-subtitle">
                @if($isEn)
                    Welcome to Brainiac! Test your knowledge and win amazing prizes.
                @else
                    مرحباً بك في Brainiac! اختبر معرفتك وفوز بجوائز رائعة.
                @endif
            </p>

            @if(!empty($alreadySubscribed))
            <div class="br-result-notice">
                {{ $isEn ? 'You are already subscribed to this service. Your subscription remains active.' : 'أنت مشترك بالفعل في هذه الخدمة. اشتراكك لا يزال نشطاً.' }}
            </div>
            @endif

            <div class="br-result-badge">
                <span></span>
                @if($isEn) ACTIVE @else نشط @endif
            </div>
        </div>
    </div>

    <footer class="br-result-footer">
        <span>@if($isEn) Brought to you by @else بالتعاون مع @endif <strong>Zain IQ</strong></span>
        <span class="br-result-footer__sep">|</span>
        <span>@if($isEn) T&Cs @else الشروط والأحكام @endif</span>
        <span class="br-result-footer__sep">|</span>
        <span>@if($isEn) Privacy @else سياسة الخصوصية @endif</span>
    </footer>
</div>
@endsection
