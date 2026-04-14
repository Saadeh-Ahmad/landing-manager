<?php

namespace App\Jobs;

use App\Models\Callback;
use App\Models\Subscriber;
use App\Models\Service;
use App\Services\DcbService;
use App\Services\SessionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessCallbackJob implements ShouldQueue
{
    use Queueable;

    protected $callbackId;
    protected DcbService $dcbService;

    /**
     * Create a new job instance.
     */
    public function __construct(int $callbackId, DcbService $dcbService)
    {
        $this->callbackId = $callbackId;
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
     * Execute the job.
     */
    public function handle(): void
    {
        $callback = Callback::find($this->callbackId);

        if (!$callback) {
            Log::error('Callback not found', ['callback_id' => $this->callbackId]);
            return;
        }

        // Update status to processing
        $callback->update(['status' => 'processing']);

        try {
            // Find service by service_id
            $service = Service::where('service_id', $callback->service_id)->first();

            if (!$service) {
                $errorMessage = "Service not found for service_id: {$callback->service_id}";
                $callback->update([
                    'status' => 'failed',
                    'error_message' => $errorMessage,
                    'processed_at' => now(),
                ]);

                Log::error('Callback processing failed: Service not found', [
                    'callback_id' => $callback->id,
                    'service_id' => $callback->service_id,
                    'msisdn' => $callback->msisdn,
                ]);

                return;
            }

            // Process based on action type
            switch ($callback->action_type) {
                case Callback::ACTION_SUB:
                    $this->handleSubscription($callback, $service);
                    break;
                case Callback::ACTION_UNSUB:
                    $this->handleUnsubscription($callback, $service);
                    break;
                case Callback::ACTION_RENEWAL:
                    $this->handleRenewal($callback, $service);
                    break;
                case Callback::ACTION_OUT_OF_BALANCE:
                    break;
                default:
                    $errorMessage = "Unknown action type: {$callback->action_type}";
                    $callback->update([
                        'status' => 'failed',
                        'error_message' => $errorMessage,
                        'processed_at' => now(),
                    ]);

                    Log::error('Callback processing failed: Unknown action type', [
                        'callback_id' => $callback->id,
                        'action_type' => $callback->action_type,
                        'msisdn' => $callback->msisdn,
                    ]);

                    return;
            }

            // Mark as completed
            $callback->update([
                'status' => 'completed',
                'processed_at' => now(),
            ]);

            Log::info('Callback processed successfully', [
                'callback_id' => $callback->id,
                'action_type' => $callback->action_type,
                'msisdn' => $callback->msisdn,
            ]);

        } catch (\Exception $e) {
            // Mark as failed
            $callback->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'processed_at' => now(),
            ]);

            Log::error('Callback processing failed', [
                'callback_id' => $callback->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Handle subscription (actionType = 1)
     */
    protected function handleSubscription(Callback $callback, Service $service): void
    {
        // Normalize MSISDN to ensure consistency
        $normalizedMsisdn = $this->normalizeMsisdn($callback->msisdn);

        $subscriber = Subscriber::where('msisdn', $normalizedMsisdn)
            ->where('service_id', $service->service_id)
            ->first();

        // If already subscribed, do nothing
        if ($subscriber && $subscriber->status === 'active') {
            Log::info('Subscriber already active, skipping', [
                'msisdn' => $normalizedMsisdn,
                'service_id' => $service->service_id,
            ]);
            return;
        }

        // Create or update subscriber
        if ($subscriber) {
            // Update existing subscriber
            $subscriber->update([
                'status' => 'active',
                'last_active_at' => now(),
            ]);
            Log::info('Subscriber updated to active', [
                'subscriber_id' => $subscriber->id,
                'msisdn' => $normalizedMsisdn,
            ]);
        } else {
            // Create new subscriber
            Subscriber::create([
                'msisdn' => $normalizedMsisdn,
                'service_id' => $service->service_id,
                'status' => 'active',
                'subscription_plan' => $service->subscribtion_type ?? 'daily',
                'rate' => $service->price ?? 1.00,
                'subscribed_at' => now(),
                'last_active_at' => now(),
                'operator' => $service->operator,
            ]);
            Log::info('New subscriber created', [
                'msisdn' => $normalizedMsisdn,
                'service_id' => $service->service_id,
            ]);
        }

        if ($service->is_smart) {
            $sessionService = new SessionService();
            $sessionResult = $sessionService->requestSessionId($normalizedMsisdn, $service->name);
            if (isset($sessionResult['sid'])) {
                $portalUrl = $sessionService->generateLocalPortalUrl($sessionResult['sid']);
                $this->dcbService->sendSMS($service, $normalizedMsisdn, $portalUrl);
            }
        }
    }

    /**
     * Handle unsubscription (actionType = 2)
     */
    protected function handleUnsubscription(Callback $callback, Service $service): void
    {
        // Normalize MSISDN to ensure consistency
        $normalizedMsisdn = $this->normalizeMsisdn($callback->msisdn);

        $subscriber = Subscriber::where('msisdn', $normalizedMsisdn)
            ->where('service_id', $service->service_id)
            ->first();

        // If subscriber doesn't exist, create it with inactive status
        if (!$subscriber) {
            $subscriber = Subscriber::create([
                'msisdn' => $normalizedMsisdn,
                'service_id' => $service->service_id,
                'status' => 'inactive',
                'subscription_plan' => $service->subscribtion_type ?? 'daily',
                'rate' => $service->price ?? 1.00,
                'unsubscribed_at' => now(),
                'operator' => $service->operator,
            ]);
            Log::info('Subscriber created as inactive (unsubscription callback)', [
                'subscriber_id' => $subscriber->id,
                'msisdn' => $normalizedMsisdn,
                'service_id' => $service->service_id,
            ]);
            return;
        }

        // If already unsubscribed, do nothing
        if ($subscriber->status === 'inactive') {
            Log::info('Subscriber already inactive, skipping', [
                'msisdn' => $normalizedMsisdn,
                'service_id' => $service->service_id,
            ]);
            return;
        }

        // Unsubscribe
        $subscriber->update([
            'status' => 'inactive',
            'unsubscribed_at' => now(),
        ]);

        Log::info('Subscriber unsubscribed', [
            'subscriber_id' => $subscriber->id,
            'msisdn' => $normalizedMsisdn,
        ]);
    }

    /**
     * Handle renewal (actionType = 3)
     */
    protected function handleRenewal(Callback $callback, Service $service): void
    {
        // Normalize MSISDN to ensure consistency
        $normalizedMsisdn = $this->normalizeMsisdn($callback->msisdn);

        $subscriber = Subscriber::where('msisdn', $normalizedMsisdn)
            ->where('service_id', $service->service_id)
            ->first();

        // If subscriber doesn't exist, create it with active status
        if (!$subscriber) {
            $subscriber = Subscriber::create([
                'msisdn' => $normalizedMsisdn,
                'service_id' => $service->service_id,
                'status' => 'active',
                'subscription_plan' => $service->subscribtion_type ?? 'daily',
                'rate' => $service->price ?? 1.00,
                'subscribed_at' => now(),
                'last_billing_date' => now(),
                'last_active_at' => now(),
                'operator' => $service->operator,
            ]);
            Log::info('Subscriber created for renewal callback', [
                'subscriber_id' => $subscriber->id,
                'msisdn' => $normalizedMsisdn,
                'service_id' => $service->service_id,
            ]);
            return;
        }

        // Update last billing date
        $subscriber->update([
            'last_billing_date' => now(),
            'last_active_at' => now(),
        ]);

        Log::info('Subscriber renewal processed', [
            'subscriber_id' => $subscriber->id,
            'msisdn' => $normalizedMsisdn,
            'last_billing_date' => $subscriber->last_billing_date,
        ]);
    }
}
