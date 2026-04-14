<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SuccessController extends Controller
{
    /**
     * Handle success page with query parameters
     *
     * Expected query parameters:
     * - msisdn: Phone number (e.g., 964111111111)
     * - success: Success flag (1)
     * - ti: Transaction ID
     * - servicename: Service display name
     * - merchantname: Merchant name
     * - additionalparameter1, additionalparameter2: Optional additional params
     *
     * Optional (OTP flow):
     * - already_subscribed: When set to 1, the success view shows an “already subscribed” notice.
     */
    public function show(Request $request)
    {
        $msisdn = $request->query('msisdn');
        $success = $request->query('success');
        $servicename = $request->query('servicename');
        $ti = $request->query('ti');

        // Log the success page request
        Log::info('Success page accessed', [
            'msisdn' => $msisdn,
            'success' => $success,
            'servicename' => $servicename,
            'ti' => $ti,
            'all_params' => $request->all(),
        ]);

        // If msisdn is provided, process subscription
        if ($msisdn && $success == '1') {
            try {
                // Find service by display_name or name
                $service = null;
                if ($servicename) {
                    // Try to find by display_name first (most common case)
                    $service = Service::where('display_name', $servicename)
                        ->where('is_active', true)
                        ->first();

                    // If not found, try by name
                    if (! $service) {
                        $service = Service::where('name', $servicename)
                            ->where('is_active', true)
                            ->first();
                    }
                }

                // If service not found, try to get default HE service (since this is HE flow)
                if (! $service) {
                    $service = Service::getByName('duel-he');
                }

                if ($service) {
                    $this->createOrUpdateSubscriber($msisdn, $service);
                } else {
                    Log::warning('Success page: Service not found', [
                        'msisdn' => $msisdn,
                        'servicename' => $servicename,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Success page error', [
                    'msisdn' => $msisdn,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }

        return view('success', [
            'alreadySubscribed' => $request->boolean('already_subscribed'),
        ]);
    }

    /**
     * Normalize MSISDN by adding country code 964 if missing
     */
    protected function normalizeMsisdn(string $msisdn): string
    {
        // Remove any non-numeric characters
        $msisdn = preg_replace('/[^0-9]/', '', $msisdn);

        // If it doesn't start with 964, add it
        if (! str_starts_with($msisdn, '964')) {
            $msisdn = '964'.$msisdn;
        }

        return $msisdn;
    }

    /**
     * Create or update subscriber
     */
    protected function createOrUpdateSubscriber(string $msisdn, Service $service): Subscriber
    {
        // Normalize MSISDN to ensure consistency
        $normalizedMsisdn = $this->normalizeMsisdn($msisdn);

        $subscriber = Subscriber::firstOrCreate(
            [
                'msisdn' => $normalizedMsisdn,
                'service_id' => $service->service_id,
            ],
            [
                'status' => 'active',
                'subscription_plan' => $service->subscribtion_type,
                'rate' => $service->price,
                'subscribed_at' => now(),
                'last_active_at' => now(),
                'operator' => $service->operator,
            ]
        );

        // Update existing subscriber if already exists
        $wasNew = $subscriber->wasRecentlyCreated;
        if (! $wasNew) {
            $subscriber->update([
                'status' => 'active',
                'last_active_at' => now(),
            ]);
        }

        Log::info('Subscriber created/updated from success page', [
            'msisdn' => $normalizedMsisdn,
            'service' => $service->name,
            'subscriber_id' => $subscriber->id,
            'was_recently_created' => $wasNew,
        ]);

        return $subscriber;
    }
}
