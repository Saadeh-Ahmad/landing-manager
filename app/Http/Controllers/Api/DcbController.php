<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DcbService;
use App\Services\ServiceConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DcbController extends Controller
{
    protected $dcbService;

    public function __construct(DcbService $dcbService)
    {
        $this->dcbService = $dcbService;
    }

    /**
     * Send PIN code to user's phone
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendPincode(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'msisdn' => 'required|string|regex:/^964[0-9]{10}$/',
            'service' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $msisdn = $request->input('msisdn');
        $serviceName = $request->input('service', 'duel-otp');

        // Check if service exists and is active
        if (!ServiceConfig::isActive($serviceName)) {
            return response()->json([
                'success' => false,
                'message' => 'Service is not available',
            ], 503);
        }

        // Use DcbService to send pincode
        $result = $this->dcbService->sendPincode($serviceName, $msisdn);

        if ($result['status'] === 'success') {
            $responseData = [
                'success' => true,
                'message' => $result['message'],
                'data' => $result['data'] ?? [],
                'service' => $serviceName,
            ];

            if (isset($result['already_subscribed']) && $result['already_subscribed']) {
                $responseData['already_subscribed'] = true;
            }

            return response()->json($responseData);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? 'Failed to send PIN code',
            'error' => $result['error'] ?? null,
        ], $result['status_code'] ?? 500);
    }

    /**
     * Verify PIN code entered by user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyPincode(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'msisdn' => 'required|string|regex:/^964[0-9]{10}$/',
            'pincode' => 'required|string|size:5',
            'service' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $msisdn = $request->input('msisdn');
        $pincode = $request->input('pincode');
        $serviceName = $request->input('service', 'duel-otp');
        $ti = $request->input('ti') ?? null;
        $ts = $request->input('ts') ?? null;

        // Check if service exists and is active
        if (!ServiceConfig::isActive($serviceName)) {
            return response()->json([
                'success' => false,
                'message' => 'Service is not available',
            ], 503);
        }

        $args = ['serviceName' => $serviceName, 'msisdn' => $msisdn, 'pincode' => $pincode];
        if (!empty($ti) && !empty($ts)) {
            $args['ti'] = $ti;
            $args['ts'] = $ts;
        }
        // Use DcbService to verify pincode
        $result = $this->dcbService->verifyPincode(...$args);

        if ($result['verified']) {
            return response()->json([
                'success' => true,
                'message' => $result['message'] ?? 'PIN code verified successfully',
                'data' => $result['data'] ?? [],
                'service' => $serviceName,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? 'PIN code verification failed',
            'error' => $result['error'] ?? null,
        ], $result['status_code'] ?? 400);
    }

    /**
     * Unsubscribe user from service
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unsubscribe(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'msisdn' => 'required|string|regex:/^964[0-9]{10}$/',
            'service' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $msisdn = $request->input('msisdn');
        $serviceName = $request->input('service', 'duel-otp');

        // Check if service exists and is active
        if (!ServiceConfig::isActive($serviceName)) {
            return response()->json([
                'success' => false,
                'message' => 'Service is not available',
            ], 503);
        }

        // Use DcbService to unsubscribe
        $result = $this->dcbService->unsubscribe($serviceName, $msisdn);

        if ($result['status'] === 'success') {
            return response()->json([
                'success' => true,
                'message' => $result['message'] ?? 'User unsubscribed successfully',
                'data' => $result['data'] ?? [],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? 'Failed to unsubscribe user',
            'error' => $result['error'] ?? null,
        ], $result['status_code'] ?? 500);
    }

    /**
     * Get available services
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getServices()
    {
        try {
            $services = ServiceConfig::getAllActive()->whereIn('type', ['dcb', 'vas'])->values();

            $servicesData = $services->map(function ($service) {
                return [
                    'name' => $service->name,
                    'display_name' => $service->display_name,
                    'description' => $service->description,
                    'type' => $service->type,
                    'price' => $service->price,
                    'currency' => $service->currency,
                    'operator' => $service->operator,
                ];
            });

            return response()->json([
                'success' => true,
                'services' => $servicesData,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve services',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
