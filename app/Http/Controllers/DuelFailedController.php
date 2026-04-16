<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class DuelFailedController extends Controller
{
    public function show(Request $request)
    {
        $reason = $request->get('reason') ?? $request->get('error') ?? $request->get('error_reason');

        if ($reason && strtolower($reason) === 'nohe') {
            $otpRouteName = $request->get('otp_landing', 'landing.duel-otp');

            return redirect()->route(
                Route::has($otpRouteName)
                    ? $otpRouteName
                    : 'landing.duel-otp'
            );
        }

        return view('duel.failed');
    }
}
