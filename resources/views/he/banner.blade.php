@extends('layouts.zainiqduel')

@php
    $bannerLocale = request()->query('lang');
    if (!in_array($bannerLocale, ['ar', 'en'], true)) {
        $bannerLocale = app()->getLocale();
    }
    if (!in_array($bannerLocale, ['ar', 'en'], true)) {
        $bannerLocale = 'ar';
    }
    $isEn = $bannerLocale === 'en';
@endphp

@section('title', __('landing.zain.banner.cta'))

@push('styles')
<style>
    :root {
        --promo-bg-1: #100033;
        --promo-bg-2: #1c0a56;
        --promo-bg-3: #27006b;
        --promo-neon-pink: #ff1493;
        --promo-neon-pink-dark: #d6007e;
        --promo-neon-blue: #37d8ff;
        --promo-white-soft: rgba(255, 255, 255, 0.85);
    }

    html:has(.promo-banner),
    body.zainiqduel-body:has(.promo-banner) {
        margin: 0;
        overflow-x: hidden;
        overflow-y: auto;
    }

    .promo-banner {
        min-height: 100vh;
        display: grid;
        place-items: center;
        padding: 20px;
        box-sizing: border-box;
        font-family: 'Cairo', 'Segoe UI', Tahoma, sans-serif;
        color: #fff;
        background: url('{{ asset('images/zainiqduel/get_started_bg.jpg') }}') center center / cover no-repeat;
        overflow-x: hidden;
        overflow-y: auto;
        position: relative;
    }

    .promo-banner::before {
        content: '';
        position: absolute;
        inset: -40% -50% auto;
        height: 90%;
        background: radial-gradient(circle, rgba(255, 20, 147, 0.14) 0%, transparent 65%);
        pointer-events: none;
    }

    .promo-banner::after {
        content: '';
        position: absolute;
        inset: 0;
        background-image:
            radial-gradient(circle at 12% 16%, rgba(255, 255, 255, 0.7) 0 1px, transparent 1px),
            radial-gradient(circle at 26% 62%, rgba(255, 255, 255, 0.55) 0 1px, transparent 1px),
            radial-gradient(circle at 44% 26%, rgba(255, 255, 255, 0.65) 0 1px, transparent 1px),
            radial-gradient(circle at 62% 52%, rgba(255, 255, 255, 0.45) 0 1px, transparent 1px),
            radial-gradient(circle at 90% 28%, rgba(255, 255, 255, 0.6) 0 1px, transparent 1px);
        pointer-events: none;
        opacity: 0.65;
    }

    .promo-card {
        position: relative;
        z-index: 6;
        width: min(1280px, 96vw);
        aspect-ratio: 16 / 9;
        max-height: 92vh;
        border-radius: 0;
        overflow: visible;
        background: transparent;
        box-shadow: none;
        display: flex;
        flex-direction: column;
    }

    .promo-top {
        position: fixed;
        top: 10px;
        right: 12px;
        z-index: 40;
        justify-content: flex-end;
        display: flex;
        padding: 0;
    }

    .promo-lang {
        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 4px;
        background: rgba(0, 0, 0, 0.28);
        border-radius: 6px;
        padding: 6px 8px;
        font-size: 14px;
        font-weight: 600;
    }

    .promo-lang a {
        color: #fff;
        text-decoration: none;
        padding: 4px 10px;
        border-radius: 4px;
        text-align: center;
    }

    .promo-lang a.is-active {
        background: #fff;
        color: #1a0033;
    }

    .promo-lang a:hover:not(.is-active) {
        background: rgba(255, 255, 255, 0.08);
    }

    .promo-headline {
        position: relative;
        z-index: 7;
        text-align: center;
        padding: 34px 96px 0 16px;
        margin: 0 auto;
        width: 100%;
        box-sizing: border-box;
    }

    .promo-headline h1 {
        margin: 0;
        font-size: clamp(1.35rem, 2.6vw, 2.75rem);
        font-weight: 800;
        line-height: 1.25;
        letter-spacing: 0.02em;
        text-shadow: 0 6px 20px rgba(0, 0, 0, 0.45);
    }

    .promo-row {
        flex: 1 1 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: clamp(12px, 2vw, 24px);
        padding: 8px clamp(10px, 2vw, 24px) clamp(18px, 3.5vh, 36px);
        width: 100%;
        box-sizing: border-box;
        direction: ltr;
    }

    .promo-left,
    .promo-right {
        width: 50%;
        min-width: 0;
        display: flex;
        justify-content: flex-start;
        align-items: center;
    }

    .promo-avatar-frame {
        position: relative;
        width: min(95%, 560px);
        aspect-ratio: 1 / 1;
        border-radius: 50%;
        background:
            radial-gradient(circle at 50% 55%, rgba(0, 0, 0, 0.15), transparent 62%),
            linear-gradient(145deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.06));
        box-shadow:
            0 0 0 2px rgba(255, 255, 255, 0.15),
            0 22px 40px rgba(0, 0, 0, 0.38);
        overflow: hidden;
        transform: rotate(-7deg) skewX(-3deg);
        transform-origin: center center;
    }

    .promo-avatar-frame img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        transform: scale(1.08) rotate(6deg);
    }

    .promo-right {
        position: relative;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 8px 6px;
    }

    .promo-right::before {
        content: '';
        position: absolute;
        inset: -10% -12%;
        background:
            repeating-conic-gradient(from 0deg at 50% 50%, transparent 0deg 8deg, rgba(255, 20, 147, 0.12) 8deg 12deg, transparent 12deg 20deg, rgba(55, 216, 255, 0.08) 20deg 24deg),
            radial-gradient(circle, rgba(130, 50, 255, 0.16) 0%, transparent 60%);
        opacity: 0.95;
        pointer-events: none;
        z-index: 0;
        border-radius: 50%;
    }

    .promo-money {
        position: relative;
        z-index: 2;
        width: min(88%, 430px);
        height: auto;
        object-fit: cover;
        filter:
            drop-shadow(0 16px 30px rgba(0, 0, 0, 0.42))
            drop-shadow(0 0 18px rgba(55, 216, 255, 0.25));
        margin-bottom: 12px;
    }

    .promo-copy {
        position: relative;
        z-index: 2;
        margin-bottom: 14px;
        max-width: 420px;
        text-align: center;
        padding: 0 6px;
    }

    .promo-copy[dir="rtl"] {
        text-align: center;
    }

    .promo-lead {
        margin: 0 0 10px;
        font-size: clamp(0.92rem, 1.2vw, 1.15rem);
        font-weight: 700;
        line-height: 1.45;
        color: var(--promo-neon-blue);
    }

    .promo-desc {
        margin: 0;
        font-size: clamp(0.78rem, 1.05vw, 0.95rem);
        font-weight: 600;
        line-height: 1.6;
        color: var(--promo-white-soft);
    }

    .promo-cta {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        border-radius: 4px;
        padding: 0 24px;
        min-width: 160px;
        width: clamp(160px, 34vw, 190px);
        height: 56px;
        font-size: clamp(0.95rem, 1.4vw, 1.02rem);
        font-weight: 800;
        letter-spacing: 0;
        text-transform: uppercase;
        color: #fff;
        background: #f0008a;
        box-shadow:
            0 10px 22px rgba(240, 0, 138, 0.42),
            0 0 20px rgba(240, 0, 138, 0.35);
        transition: transform 0.12s ease, box-shadow 0.2s ease;
    }

    .promo-cta:hover {
        transform: translateY(-1px);
        box-shadow:
            0 12px 26px rgba(240, 0, 138, 0.5),
            0 0 24px rgba(240, 0, 138, 0.45);
    }

    .promo-cta:active {
        transform: scale(0.98);
    }

    @media (max-width: 960px) {
        .promo-banner {
            padding: 12px;
            place-items: start center;
        }

        .promo-top {
            top: 8px;
            right: 8px;
        }

        .promo-card {
            aspect-ratio: auto;
            min-height: calc(100vh - 24px);
        }

        .promo-headline {
            padding: 64px 16px 0;
            max-width: 100%;
        }

        .promo-row {
            flex-direction: column;
            padding: 10px 8px 22px;
            gap: 10px;
        }

        .promo-left,
        .promo-right {
            width: 100%;
            justify-content: center;
        }

        .promo-avatar-frame {
            width: min(72vw, 290px);
            transform: rotate(-5deg) skewX(-2deg);
        }

        .promo-money {
            width: min(60vw, 250px);
        }

        .promo-lang {
            flex-direction: column;
            padding: 5px 6px;
        }

        .promo-lang a {
            padding: 4px 8px;
            font-size: 12px;
        }
    }

    @media (max-width: 560px) {
        .promo-headline {
            padding: 72px 14px 0;
            max-width: 360px;
            margin: 0 auto;
        }

        .promo-headline h1 {
            font-size: clamp(1.1rem, 7vw, 1.7rem);
            line-height: 1.25;
        }

        .promo-avatar-frame {
            width: min(68vw, 250px);
            transform: rotate(-4deg) skewX(-1deg);
        }

        .promo-copy {
            max-width: 95%;
            text-align: center;
        }

        .promo-lead {
            font-size: clamp(0.85rem, 4.4vw, 1rem);
        }

        .promo-desc {
            font-size: clamp(0.78rem, 3.9vw, 0.9rem);
            line-height: 1.55;
        }

        .promo-cta {
            width: min(160px, 88vw);
            height: 54px;
            padding: 0 20px;
        }
    }
</style>
@endpush

@section('content')
<section class="promo-banner">
    <div class="promo-card">
        <header class="promo-top">
        <nav class="promo-lang" aria-label="Language">
            <a href="{{ url()->current() }}?lang=ar" class="{{ $bannerLocale === 'ar' ? 'is-active' : '' }}">{{ trans('landing.zain.lang_ar', [], $bannerLocale) }}</a>
            <a href="{{ url()->current() }}?lang=en" class="{{ $bannerLocale === 'en' ? 'is-active' : '' }}">{{ trans('landing.zain.lang_en', [], $bannerLocale) }}</a>
        </nav>
    </header>

    <div class="promo-headline">
        <h1>{{ trans('landing.zain.banner.headline_full', [], $bannerLocale) }}</h1>
    </div>

    <main class="promo-row">
        <div class="promo-left">
            <div class="promo-avatar-frame">
                <img src="{{ asset('images/zainiqduel/Get-Started_tablet.png') }}" alt="Cartoon quiz avatar in world landmarks collage" width="643" height="549" loading="eager" decoding="async">
            </div>
        </div>

        <div class="promo-right">
            <img class="promo-money" src="{{ asset('images/zainiqduel/Get-Started_prizes_lossy.png') }}" alt="Prize money bundles" width="592" height="287" loading="eager">
            <div class="promo-copy" @if(!$isEn) dir="rtl" @endif>
                <p class="promo-lead">
                    {{ trans('landing.zain.banner.desc_line1', [], $bannerLocale) }}
                    {{ trans('landing.zain.banner.desc_line2', [], $bannerLocale) }}
                </p>
                <p class="promo-desc">
                    {{ trans('landing.zain.banner.desc_body', [], $bannerLocale) }}
                </p>
            </div>
            <a class="promo-cta" href="/landing/duel-he?lang={{ $bannerLocale }}">{{ trans('landing.zain.banner.cta', [], $bannerLocale) }}</a>
        </div>
    </main>
    </div>
</section>
@endsection
