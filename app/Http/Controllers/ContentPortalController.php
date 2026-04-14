<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ContentPortalService;
use App\Models\Subscriber;
use App\Models\Transaction;

class ContentPortalController extends Controller
{
    protected $contentPortalService;

    public function __construct(ContentPortalService $contentPortalService)
    {
        $this->contentPortalService = $contentPortalService;
    }

    /**
     * Request session ID from content portal
     */
    public function requestSession()
    {
        if (!session('otp_verified')) {
            return redirect()->route('landing.otp-subscription')
                ->with('error', 'Please verify OTP first');
        }

        $msisdn = session('msisdn');
        $subscriberId = session('subscriber_id');

        // Show loading page
        return view('content.requesting', [
            'msisdn' => $msisdn,
        ]);
    }

    /**
     * Process session request (AJAX endpoint)
     */
    public function processSessionRequest(Request $request)
    {
        if (!session('otp_verified')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Not authorized',
            ], 403);
        }

        $msisdn = session('msisdn');
        $subscriberId = session('subscriber_id');

        // Call dummy Content Portal API to get session ID
        $response = $this->contentPortalService->requestSessionId($msisdn, [
            'subscriber_id' => $subscriberId,
            'campaign' => session('campaign'),
        ]);

        if ($response['status'] === 'success') {
            $sessionId = $response['session_id'];

            // Store session ID
            session([
                'content_session_id' => $sessionId,
                'content_expires_at' => now()->addSeconds($response['expires_in']),
                'subscribed' => true,
            ]);

            // Create transaction record
            Transaction::create([
                'transaction_id' => 'SESS' . time() . rand(1000, 9999),
                'subscriber_id' => $subscriberId,
                'msisdn' => $msisdn,
                'type' => 'subscription',
                'status' => 'success',
                'amount' => 1.00,
                'currency' => 'IQD',
                'operator' => 'Content Portal',
                'processed_at' => now(),
                'response_payload' => json_encode($response),
            ]);

            return response()->json([
                'status' => 'success',
                'redirect_url' => route('service.content'),
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to create session',
        ], 500);
    }

    /**
     * Access content with session ID
     */
    public function accessContent(Request $request)
    {
        $sessionId = $request->query('session');

        /** @todo: handle session expiration */
        if (!$sessionId || $sessionId !== session('content_session_id')) {
            return redirect()->route('landing.otp-subscription')
                ->with('error', 'Invalid or expired session');
        }

        // Update last active
        if ($subscriberId = session('subscriber_id')) {
            Subscriber::where('id', $subscriberId)->update([
                'last_active_at' => now(),
            ]);
        }

        return redirect()->route('service.content');
    }

    /**
     * Validate current session
     */
    public function validateSession(Request $request)
    {
        $sessionId = session('content_session_id');

        if (!$sessionId) {
            return response()->json([
                'valid' => false,
                'message' => 'No active session',
            ]);
        }

        $response = $this->contentPortalService->validateSession($sessionId);

        return response()->json($response);
    }

    /**
     * Terminate session
     */
    public function terminateSession()
    {
        $sessionId = session('content_session_id');

        if ($sessionId) {
            $this->contentPortalService->terminateSession($sessionId);
        }

        session()->flush();

        return redirect()->route('landing.otp-subscription')
            ->with('success', 'Session terminated successfully');
    }
}
