<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SuccessController;
use App\Http\Controllers\FailedController;
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

// Static OTP Landing — Brainiac
Route::get('/landing/brainiac-otp', function () {
    return app(ApiOtpController::class)->showLanding(request(), 'brainiac-otp', 'brainiac.otp.main');
})->name('landing.brainiac-otp');

// Static HE Landing — Brainiac (Header Enrichment)
Route::get('/landing/brainiac-he', function () {
    return app(ApiHeController::class)->showLanding(request(), 'brainiac-he', 'brainiac.he.landing', 'landing.brainiac-otp');
})->name('landing.brainiac-he');

// Subscription Success Page
Route::get('/duel-success', [SuccessController::class, 'show'])->name('duel.success');

// Subscription Failed Page
Route::get('/duel-failed', [FailedController::class, 'show'])->name('duel.failed');

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
