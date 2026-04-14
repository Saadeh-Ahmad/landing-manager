<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceConfigController extends Controller
{
    /**
     * Get service configuration for frontend
     *
     * @param string $serviceName
     * @return \Illuminate\Http\JsonResponse
     */
    public function getConfig(Request $request, $serviceName = null)
    {
        // If no service name provided, try to determine from context
        if (!$serviceName) {
            $serviceName = $request->input('service', 'duel-otp');
        }

        $service = Service::getByName($serviceName);

        if (!$service) {
            return response()->json([
                'error' => 'Service not found',
                'pin_length' => 4, // Default fallback
            ], 404);
        }

        return response()->json([
            'service_name' => $service->name,
            'service_type' => $service->type,
            'pin_length' => $service->settings['pin_length'] ?? 4,
            'subscription_type' => $service->subscribtion_type,
            'enable_evina_fraud' => $service->enable_evina_fraud,
            'mode' => $service->mode,
        ]);
    }

    /**
     * Get all active services configuration
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllConfigs()
    {
        $services = Service::getAllActive();

        $configs = $services->map(function ($service) {
            return [
                'name' => $service->name,
                'type' => $service->type,
                'display_name' => $service->display_name,
                'pin_length' => $service->settings['pin_length'] ?? 4,
                'subscription_type' => $service->subscribtion_type,
                'enable_evina_fraud' => $service->enable_evina_fraud,
            ];
        });

        return response()->json([
            'services' => $configs,
        ]);
    }
}
