<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Callback;
use App\Services\DcbService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CallbackController extends Controller
{
    protected DcbService $dcbService;

    public function __construct(DcbService $dcbService)
    {
        $this->dcbService = $dcbService;
    }
    /**
     * Normalize MSISDN by adding country code 964 if missing
     */
    protected function normalizeMsisdn(string $msisdn): string
    {
        // Remove any non-numeric characters
        $msisdn = preg_replace('/[^0-9]/', '', $msisdn);
        
        // If it doesn't start with 964, add it
        if (!str_starts_with($msisdn, '964')) {
            $msisdn = '964' . $msisdn;
        }
        
        return $msisdn;
    }

    /**
     * Receive callback request and queue for processing
     */
    public function handle(Request $request)
    {
        // Normalize MSISDN to always include country code 964
        $msisdn = $this->normalizeMsisdn($request->input('msisdn', ''));
        
        // Save all callback data to database
        $callback = Callback::create([
            'username' => $request->input('username'),
            'password' => $request->input('password'),
            'msisdn' => $msisdn,
            'action_type' => $request->input('actionType'),
            'service_id' => $request->input('serviceId'),
            'sp_id' => $request->input('spId'),
            'date' => $request->input('date'),
            'requestid' => $request->input('requestid'),
            'sc' => $request->input('sc'),
            'status' => 'pending',
        ]);

        Log::info('Callback received', [
            'callback_id' => $callback->id,
            'msisdn' => $callback->msisdn,
            'action_type' => $callback->action_type,
            'service_id' => $callback->service_id,
        ]);

        /**
         * @todo Re-enable background processing when carrier callbacks must update subscribers
         *       or send post-subscription SMS again. Example:
         *       ProcessCallbackJob::dispatch($callback->id, $this->dcbService);
         */
        Log::notice('Callback stored; ProcessCallbackJob dispatch is intentionally disabled.', [
            'callback_id' => $callback->id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Callback received and queued for processing',
            'callback_id' => $callback->id,
        ]);
    }
}
