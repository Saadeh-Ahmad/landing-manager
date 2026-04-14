<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DcbController;
use App\Http\Controllers\Api\ServiceConfigController;
use App\Http\Controllers\Api\CallbackController;

/*
|--------------------------------------------------------------------------
| API Routes - Media World Platform
|--------------------------------------------------------------------------
|
| All routes are prefixed with /api
|
*/

// ============================================
// SERVICE CONFIGURATION API
// ============================================

Route::prefix('service-config')->group(function () {
    // Get specific service configuration
    Route::get('/{serviceName?}', [ServiceConfigController::class, 'getConfig'])
        ->name('api.service-config.get');
    
    // Get all active services configuration
    Route::get('/all/configs', [ServiceConfigController::class, 'getAllConfigs'])
        ->name('api.service-config.all');
});

// ============================================
// DCB (Direct Carrier Billing) API
// ============================================

Route::prefix('dcb')->middleware('dcb.api')->group(function () {
    
    // Send PIN code to user
    Route::post('/send-pincode', [DcbController::class, 'sendPincode'])
        ->name('api.dcb.send-pincode');
    
    // Verify PIN code
    Route::post('/verify-pincode', [DcbController::class, 'verifyPincode'])
        ->name('api.dcb.verify-pincode');
    
    // Unsubscribe user
    Route::post('/unsubscribe', [DcbController::class, 'unsubscribe'])
        ->name('api.dcb.unsubscribe');
    
    // Get available services
    Route::get('/services', [DcbController::class, 'getServices'])
        ->name('api.dcb.services');
});

// ============================================
// CALLBACK API
// ============================================

Route::get('/callback', [CallbackController::class, 'handle'])
    ->name('api.callback');

