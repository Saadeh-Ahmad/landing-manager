<?php

use Illuminate\Support\Facades\Route;
use App\Services\SessionService;
use App\Http\Controllers\ContentPortalController;
use App\Http\Controllers\ServiceController;
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
    return redirect()->route('landing.otp-subscription');
})->name('landing');

// Static OTP Landing
//Route::get('/landing/otp-subscription', function () {
//    return app(ApiOtpController::class)->showLanding(request(), 'otp-subscription', 'otp.landing');
//})->name('landing.otp-subscription');

// Static OTP Landing
Route::get('/landing/otp-subscription', function () {
    return app(ApiOtpController::class)->showLanding(request(), 'otp-subscription', 'otp.main');
})->name('landing.otp-subscription');

// Static HE Landing (Header Enrichment)
Route::get('/landing/he-subscription', function () {
    return app(ApiHeController::class)->showLanding(request(), 'he-subscription', 'he.landing', 'landing.otp-subscription');
})->name('landing.he-subscription');

// Subscription Success Page
Route::get('/success', [SuccessController::class, 'show'])->name('subscription.success');

// Subscription Failed Page
Route::get('/failed', [FailedController::class, 'show'])->name('subscription.failed');

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
// CONTENT PORTAL SESSION
// ============================================
Route::prefix('content-portal')->group(function () {
    // Request session ID (shows loading page)
    Route::get('/request-session', [ContentPortalController::class, 'requestSession'])
        ->name('content.portal.request');

    // Process session request (AJAX)
    Route::post('/process-session', [ContentPortalController::class, 'processSessionRequest'])
        ->name('content.portal.process');

    // Access content with session
    Route::get('/access', [ContentPortalController::class, 'accessContent'])
        ->name('content.portal');

    // Validate session
    Route::get('/validate', [ContentPortalController::class, 'validateSession'])
        ->name('content.portal.validate');

    // Terminate session
    Route::get('/terminate', [ContentPortalController::class, 'terminateSession'])
        ->name('content.portal.terminate');
});

// ============================================
// SERVICE CONTENT (Session-based)
// ============================================
Route::middleware(['web'])->prefix('service')->group(function () {
    Route::get('/content', [ServiceController::class, 'index'])->name('service.content');
    Route::get('/videos', [ServiceController::class, 'videos'])->name('service.videos');
    Route::get('/music', [ServiceController::class, 'music'])->name('service.music');
    Route::get('/logout', [ServiceController::class, 'logout'])->name('service.logout');
});

// ============================================
// ADMIN DASHBOARD
// ============================================
Route::prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/subscribers', [DashboardController::class, 'subscribers'])->name('dashboard.subscribers');
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('dashboard.analytics');
});

// ============================================
// PORTAL REDIRECT
// ============================================
Route::get('/portal/{sid}', function ($sid) {
    $sessionService = new SessionService();
    $portalUrl = $sessionService->generatePortalUrl($sid);

    // Log the portal redirect for debugging
    \Illuminate\Support\Facades\Log::info('========== PORTAL REDIRECT ==========', [
        'timestamp' => now()->toDateTimeString(),
        'session_id' => $sid,
        'portal_url' => $portalUrl,
        'referer' => request()->header('Referer'),
        'user_agent' => request()->userAgent(),
        'ip_address' => request()->ip(),
    ]);

    // Redirect to billing gateway
    return redirect()->away($portalUrl);
})->name('portal.redirect');
