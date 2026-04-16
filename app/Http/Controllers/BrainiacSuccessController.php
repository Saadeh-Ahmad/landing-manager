<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BrainiacSuccessController extends SuccessController
{
    public function show(Request $request)
    {
        $msisdn     = $request->query('msisdn');
        $success    = $request->query('success');
        $servicename = $request->query('servicename');
        $ti         = $request->query('ti');

        app()->setLocale(session('locale', 'ar') ?: 'ar');

        Log::info('Brainiac success page accessed', [
            'msisdn'      => $msisdn,
            'success'     => $success,
            'servicename' => $servicename,
            'ti'          => $ti,
            'all_params'  => $request->all(),
        ]);

        if ($msisdn && $success == '1') {
            try {
                $service = null;

                if ($servicename) {
                    $service = Service::where('display_name', $servicename)
                        ->where('is_active', true)
                        ->first();

                    if (! $service) {
                        $service = Service::where('name', $servicename)
                            ->where('is_active', true)
                            ->first();
                    }
                }

                if (! $service) {
                    $service = Service::getByName('brainiac-he');
                }

                if ($service) {
                    $this->createOrUpdateSubscriber($msisdn, $service);
                } else {
                    Log::warning('Brainiac success page: Service not found', [
                        'msisdn'      => $msisdn,
                        'servicename' => $servicename,
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Brainiac success page error', [
                    'msisdn' => $msisdn,
                    'error'  => $e->getMessage(),
                    'trace'  => $e->getTraceAsString(),
                ]);
            }
        }

        return view('brainiac.success', [
            'alreadySubscribed' => $request->boolean('already_subscribed'),
        ]);
    }
}
