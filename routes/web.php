<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DuelSuccessController;
use App\Http\Controllers\DuelFailedController;
use App\Http\Controllers\BrainiacSuccessController;
use App\Http\Controllers\BrainiacFailedController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Api\OtpController as ApiOtpController;
use App\Http\Controllers\Api\HeController as ApiHeController;
// ============================================
// LANDING PAGES
// ============================================

Route::get('/', function () {
    // Redirect to OTP landing
    return redirect()->route('landing.duel-otp');
})->name('landing');

// Static OTP Landing
Route::get('/landing/duel-otp', function () {
    return app(ApiOtpController::class)->showLanding(request(), 'duel-otp', 'otp.main');
})->name('landing.duel-otp');

// Static HE Landing (Header Enrichment)
Route::get('/landing/duel-he', function () {
    return app(ApiHeController::class)->showLanding(request(), 'duel-he', 'he.landing', 'landing.duel-otp');
})->name('landing.duel-he');

// Static HE banner landing (no backend logic)
Route::get('/landing/duel', function () {
    return view('he.banner');
})->name('landing.duel');

// Static OTP Landing — Brainiac
Route::get('/landing/brainiac-otp', function () {
    return app(ApiOtpController::class)->showLanding(request(), 'brainiac-otp', 'brainiac.otp.main');
})->name('landing.brainiac-otp');

// Static HE Landing — Brainiac (Header Enrichment)
Route::get('/landing/brainiac-he', function () {
    return app(ApiHeController::class)->showLanding(request(), 'brainiac-he', 'brainiac.he.landing', 'landing.brainiac-otp');
})->name('landing.brainiac-he');

// Static HE banner landing for Brainiac (no backend logic)
Route::get('/landing/brainiac', function () {
    return view('brainiac.he.banner');
})->name('landing.  ');

// Subscription Success Page
Route::get('/duel-success', [DuelSuccessController::class, 'show'])->name('duel.success');

// Subscription Failed Page
Route::get('/duel-failed', [DuelFailedController::class, 'show'])->name('duel.failed');

// Brainiac Subscription Success Page
Route::get('/brainiac-success', [BrainiacSuccessController::class, 'show'])->name('brainiac.success');

// Brainiac Subscription Failed Page
Route::get('/brainiac-failed', [BrainiacFailedController::class, 'show'])->name('brainiac.failed');

// Privacy Policy Page
Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

// Terms & Conditions Page
Route::get('/terms', function () {
    return view('terms');
})->name('terms');

// FAQ Page
Route::get('/faq', function () {
    return view('faq');
})->name('faq');

// Language Switcher
Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

// ============================================
// ADMIN DASHBOARD
// ============================================
Route::prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/subscribers', [DashboardController::class, 'subscribers'])->name('dashboard.subscribers');
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('dashboard.analytics');
});
