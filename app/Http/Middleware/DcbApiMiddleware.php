<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DcbApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log incoming request
        Log::info('DCB API Request', [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'input' => $this->sanitizeInput($request->all()),
        ]);

        // Ensure JSON response
        $request->headers->set('Accept', 'application/json');

        // Add request timestamp
        $startTime = microtime(true);

        // Process request
        $response = $next($request);

        // Calculate processing time
        $processingTime = round((microtime(true) - $startTime) * 1000, 2);

        // Add custom headers to response
        $response->headers->set('X-Processing-Time', $processingTime . 'ms');
        $response->headers->set('X-API-Version', '1.0');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');

        // Log response
        Log::info('DCB API Response', [
            'status' => $response->getStatusCode(),
            'processing_time_ms' => $processingTime,
        ]);

        return $response;
    }

    /**
     * Sanitize input for logging (remove sensitive data)
     *
     * @param array $input
     * @return array
     */
    private function sanitizeInput(array $input): array
    {
        $sanitized = $input;
        
        // Hide sensitive fields
        $sensitiveFields = ['pincode', 'password', 'token', 'api_key'];
        
        foreach ($sensitiveFields as $field) {
            if (isset($sanitized[$field])) {
                $sanitized[$field] = '****';
            }
        }
        
        return $sanitized;
    }
}
