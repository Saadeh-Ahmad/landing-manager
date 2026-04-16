<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class BrainiacFailedController extends Controller
{
    public function show(Request $request)
    {
        app()->setLocale(session('locale', 'ar') ?: 'ar');

        $reason = $request->get('reason') ?? $request->get('error') ?? $request->get('error_reason');

        if ($reason && strtolower($reason) === 'nohe') {
            $otpRouteName = $request->get('otp_landing', 'landing.brainiac-otp');

            return redirect()->route(
                Route::has($otpRouteName)
                    ? $otpRouteName
                    : 'landing.brainiac-otp'
            );
        }

        return view('brainiac.failed');
    }
}
