<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class FailedController extends Controller
{
    /**
     * Handle failed subscription page
     * 
     * Expected query parameters:
     * - reason: Failure reason (e.g., 'nohe')
     * - error: Alternative error parameter
     * - error_reason: Alternative error reason parameter
     * - otp_landing: Route name for OTP landing page redirect (optional)
     */
    public function show(Request $request)
    {
        // Check for "nohe" reason and auto-redirect to OTP subscription
        $reason = $request->get('reason') ?? $request->get('error') ?? $request->get('error_reason');
        
        if ($reason && strtolower($reason) === 'nohe') {
            $otpRouteName = $request->get('otp_landing', 'landing.otp-subscription');
            
            return redirect()->route(
                Route::has($otpRouteName)
                    ? $otpRouteName
                    : 'landing.otp-subscription'
            );
        }
        
        return view('failed');
    }
}

