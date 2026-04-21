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

@section('title', trans('landing.zain.brainiac_banner.cta', [], $bannerLocale))

@push('styles')
<style>
    :root {
        --br-navy: #0e2256;
        --br-teal: #2edacb;
        --br-purple: #5d2c88;
        --br-pink: #e91e8c;
        --br-pink-d: #c01677;
    }

    html:has(.br-banner-page),
    body.zainiqduel-body:has(.br-banner-page) {
        margin: 0;
        overflow-x: hidden;
        overflow-y: auto;
    }

    .br-banner-page {
        height: 100vh;
        font-family: 'Cairo', Tahoma, Arial, sans-serif;
        background:
            url('{{ asset('images/brainiac/math_pattern_v2.svg') }}') left center / 20% repeat,
            linear-gradient(180deg, #28c3ba 0%, #8a63a6 36%, #5f2e89 58%, #f2f2f2 58%, #f2f2f2 100%);
        color: #fff;
        position: relative;
        overflow-x: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow-y: hidden;
    }

    .br-banner-wrap {
        max-width: none;
        margin: 0 auto;
        padding: 20px 16px 26px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        width: 100%;
        min-height: 100vh;
        justify-content: center;
    }

    .br-banner-top {
        position: fixed;
        top: 10px;
        right: 12px;
        z-index: 40;
        display: flex;
        justify-content: flex-end;
    }

    .br-banner-lang {
        display: flex;
        gap: 2px;
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.28);
        border-radius: 6px;
        padding: 4px 6px;
    }

    .br-banner-lang a {
        color: #fff;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 4px;
    }

    .br-banner-lang a.active {
        background: rgba(255, 255, 255, 0.22);
    }

    .br-banner-logo {
        display: flex;
        justify-content: center;
        margin: 14px 0 6px;
    }

    .br-banner-logo img {
        height: clamp(48px, 10vw, 88px);
        width: auto;
    }

    .br-banner-copy {
        text-align: center;
        max-width: 690px;
        margin: 0 auto 18px;
        padding: 0 8px;
        width: 100%;
        box-sizing: border-box;
    }

    .br-banner-copy[dir="ltr"],
    .br-banner-copy[dir="rtl"] {
        text-align: center;
    }

    .br-banner-copy h1 {
        margin: 0 0 8px;
        font-size: clamp(0.95rem, 2.1vw, 1.12rem);
        font-weight: 800;
        color: rgba(255, 255, 255, 0.94);
    }

    .br-banner-copy p {
        margin: 0;
        font-size: clamp(0.83rem, 1.5vw, 0.95rem);
        line-height: 1.7;
        color: rgba(255, 255, 255, 0.9);
        text-align: center;
        margin-inline: auto;
        max-width: 100%;
        overflow-wrap: anywhere;
    }

    .br-banner-prizes {
        display: grid;
        grid-template-columns: repeat(2, minmax(132px, 190px));
        gap: clamp(16px, 4vw, 40px);
        margin-bottom: clamp(18px, 4vw, 34px);
        justify-content: center;
        width: 100%;
    }

    .br-banner-prize {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 8px 22px rgba(0, 0, 0, 0.16);
        padding: 10px 12px 12px;
        width: clamp(142px, 24vw, 190px);
        text-align: center;
    }

    .br-banner-prize img {
        width: 100%;
        height: clamp(86px, 13vw, 122px);
        object-fit: contain;
        display: block;
        margin-bottom: 6px;
    }

    .br-banner-prize strong {
        display: block;
        color: var(--br-navy);
        font-size: clamp(0.68rem, 1.4vw, 0.78rem);
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 2px;
    }

    .br-banner-prize span {
        display: block;
        color: #666;
        font-size: clamp(0.62rem, 1.25vw, 0.72rem);
    }

    .br-banner-cta {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 14px;
        padding: clamp(22px, 4vw, 34px) 10px 0;
        width: min(100%, 340px);
    }

    .br-btn {
        display: block;
        text-decoration: none;
        width: 100%;
        border: none;
        cursor: pointer;
        font-family: inherit;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: 800;
        letter-spacing: 0.06em;
        padding: 15px 20px;
        text-align: center;
        transition: background .15s, transform .1s;
    }

    .br-btn:active {
        transform: scale(.98);
    }

    .br-btn--primary {
        color: #fff;
        background: var(--br-pink);
        box-shadow: 0 4px 18px rgba(233, 30, 140, 0.38);
    }

    .br-btn--primary:hover {
        background: var(--br-pink-d);
    }

    [dir="rtl"] .br-btn {
        letter-spacing: 0.01em;
    }

    .br-btn--outline {
        color: var(--br-pink);
        background: #fff;
        border: 2px solid var(--br-pink);
    }

    .br-banner-note {
        margin: 4px auto 0;
        max-width: 540px;
        text-align: center;
        color: #444;
        font-size: clamp(0.77rem, 1.5vw, 0.88rem);
        line-height: 1.7;
    }

    @media (max-width: 640px) {
        .br-banner-wrap {
            padding: 16px 0 22px;
        }

        .br-banner-page {
            height: auto;
            min-height: 100vh;
            overflow-y: auto;
        }

        .br-banner-top {
            top: 8px;
            right: 8px;
        }

        .br-banner-copy {
            margin-bottom: 14px;
            width: 100%;
            max-width: 100%;
            padding: 0 18px;
            margin-inline: auto;
        }

        .br-banner-copy p {
            text-align: center !important;
            max-width: 100%;
        }

        .br-banner-prizes {
            display: flex;
            flex-wrap: nowrap;
            justify-content: center;
            margin-bottom: 18px;
            gap: 14px;
            width: 100%;
            max-width: none;
            padding: 0 16px;
            box-sizing: border-box;
        }

        .br-banner-prize {
            width: clamp(128px, 42vw, 160px);
            min-width: 128px;
            padding: 10px 8px 12px;
        }

        .br-btn {
            width: 80%;
        }
    }
</style>
@endpush

@section('content')
<section class="br-banner-page" dir="{{ $isEn ? 'ltr' : 'rtl' }}">
    <div class="br-banner-wrap">
        <div class="br-banner-top">
            <nav class="br-banner-lang" aria-label="Language">
                <a href="{{ url()->current() }}?lang=ar" class="{{ $bannerLocale === 'ar' ? 'active' : '' }}">{{ trans('landing.zain.lang_ar', [], $bannerLocale) }}</a>
                <a href="{{ url()->current() }}?lang=en" class="{{ $bannerLocale === 'en' ? 'active' : '' }}">{{ trans('landing.zain.lang_en', [], $bannerLocale) }}</a>
            </nav>
        </div>

        <div class="br-banner-logo">
            <img src="{{ asset('images/brainiac/logo-brainiac.svg') }}" alt="Brainiac logo" width="220" height="88">
        </div>

        <div class="br-banner-copy" dir="{{ $isEn ? 'ltr' : 'rtl' }}">
            <p>{{ trans('landing.zain.brainiac_banner.terms_body', [], $bannerLocale) }}</p>
        </div>

        <div class="br-banner-prizes">
            <article class="br-banner-prize">
                <img src="{{ asset('images/brainiac/prize_weekly.png') }}" alt="">
                <strong>{{ trans('landing.zain.brainiac_banner.weekly_label', [], $bannerLocale) }}</strong>
                <span>{{ trans('landing.zain.brainiac_banner.weekly_sub', [], $bannerLocale) }}</span>
            </article>
            <article class="br-banner-prize">
                <img src="{{ asset('images/brainiac/prize_grand.png') }}" alt="">
                <strong>{{ trans('landing.zain.brainiac_banner.grand_label', [], $bannerLocale) }}</strong>
                <span>{{ trans('landing.zain.brainiac_banner.grand_sub', [], $bannerLocale) }}</span>
            </article>
        </div>

        <div class="br-banner-cta">
            <a class="br-btn br-btn--primary" href="/landing/brainiac-he?lang={{ $bannerLocale }}">
                {{ trans('landing.zain.brainiac_banner.cta', [], $bannerLocale) }}
            </a>
            <p class="br-banner-note">{{ trans('landing.zain.brainiac_banner.note', [], $bannerLocale) }}</p>
        </div>
    </div>
</section>
@endsection
