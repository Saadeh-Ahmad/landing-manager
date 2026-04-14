<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Configuration - Media World Platform
    |--------------------------------------------------------------------------
    |
    | This file contains all API configurations for the Media World platform.
    | You can easily switch between dummy/test APIs and production APIs by
    | changing the environment variables.
    |
    | DYNAMIC SERVICES:
    | Services (DCB, Evina, etc.) are now stored in the database.
    | Use the ServiceConfig helper class to access them:
    |
    | Example Usage:
    |   use App\Services\ServiceConfig;
    |   $config = ServiceConfig::get('dcb_mediaworld');
    |   $url = ServiceConfig::buildSendPincodeUrl('dcb_mediaworld', $msisdn);
    |
    | To add a new service, insert it into the 'services' table or use the seeder.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Dynamic Services Configuration
    |--------------------------------------------------------------------------
    |
    | Services are managed in the database (services table).
    | This provides flexibility to add/modify services without code changes.
    |
    | Available services can be retrieved using:
    |   ServiceConfig::getAllActive()
    |   ServiceConfig::getAllActive('dcb') // Filter by type
    |
    */
    'dynamic_services' => [
        'enabled' => env('DYNAMIC_SERVICES_ENABLED', true),
        'cache_ttl' => 3600, // Cache services for 1 hour
        'default_service' => env('DEFAULT_SERVICE', 'dcb_mediaworld'),
    ],

    /*
    |--------------------------------------------------------------------------
    | OTP Service Configuration
    |--------------------------------------------------------------------------
    |
    | Configure your SMS/OTP provider here. 
    | Set 'mode' to 'dummy' for testing or 'live' for production.
    |
    */
    'otp' => [
        'mode' => env('OTP_MODE', 'dummy'), // 'dummy' or 'live'
        
        // OTP Settings
        'code_length' => env('OTP_CODE_LENGTH', 5),
        'expiry_minutes' => env('OTP_EXPIRY_MINUTES', 5),
        
        // Live OTP Service (Production)
        'live' => [
            'provider' => env('OTP_PROVIDER', 'sms_gateway'), // Provider name
            'api_url' => env('OTP_API_URL', 'https://api.smsprovider.com/send'),
            'api_key' => env('OTP_API_KEY', ''),
            'api_secret' => env('OTP_API_SECRET', ''),
            'sender_id' => env('OTP_SENDER_ID', 'MediaWorld'),
            'timeout' => env('OTP_TIMEOUT', 30), // seconds
        ],
        
        // Dummy OTP Service (Testing)
        'dummy' => [
            'auto_generate' => true,
            'default_code' => env('OTP_DEFAULT_CODE', null), // Set fixed OTP for testing
            'delay_ms' => 500, // Simulate API delay in milliseconds
            'log_otp' => env('OTP_LOG_CODES', true), // Log OTP codes in storage/logs
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Content Portal Configuration
    |--------------------------------------------------------------------------
    |
    | Configure your content portal/streaming service API here.
    | This handles session creation and content access.
    |
    */
    'content_portal' => [
        'mode' => env('CONTENT_PORTAL_MODE', 'dummy'), // 'dummy' or 'live'
        
        // Session Settings
        'session_expiry' => env('CONTENT_SESSION_EXPIRY', 86400), // 24 hours in seconds
        
        // Live Content Portal (Production)
        'live' => [
            'api_url' => env('CONTENT_PORTAL_API_URL', 'https://portal.mediaworld.com/api'),
            'api_key' => env('CONTENT_PORTAL_API_KEY', ''),
            'api_secret' => env('CONTENT_PORTAL_API_SECRET', ''),
            'timeout' => env('CONTENT_PORTAL_TIMEOUT', 30),
            
            // Endpoints
            'endpoints' => [
                'create_session' => '/session/create',
                'validate_session' => '/session/validate',
                'refresh_session' => '/session/refresh',
                'terminate_session' => '/session/terminate',
            ],
        ],
        
        // Dummy Content Portal (Testing)
        'dummy' => [
            'session_id_length' => 32,
            'delay_ms' => 700, // Simulate API delay
            'auto_success' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Zain Iraq Renewal API Configuration
    |--------------------------------------------------------------------------
    |
    | Configure settings for receiving renewal notifications from Zain Iraq.
    | This is your webhook endpoint that Zain will call.
    |
    */
    'zain_iraq' => [
        // Authentication
        'authentication' => [
            'enabled' => env('ZAIN_AUTH_ENABLED', false),
            'type' => env('ZAIN_AUTH_TYPE', 'bearer'), // 'bearer', 'basic', 'signature'
            'token' => env('ZAIN_API_TOKEN', ''),
            'secret' => env('ZAIN_API_SECRET', ''),
        ],
        
        // IP Whitelisting
        'ip_whitelist' => [
            'enabled' => env('ZAIN_IP_WHITELIST_ENABLED', false),
            'allowed_ips' => array_filter(explode(',', env('ZAIN_ALLOWED_IPS', ''))),
        ],
        
        // Request Settings
        'request' => [
            'log_all_requests' => env('ZAIN_LOG_REQUESTS', true),
            'validate_signature' => env('ZAIN_VALIDATE_SIGNATURE', false),
            'signature_header' => env('ZAIN_SIGNATURE_HEADER', 'X-Zain-Signature'),
        ],
        
        // Default Values
        'defaults' => [
            'currency' => 'IQD',
            'default_amount' => 1000, // IQD
            'operator_name' => 'Zain Iraq',
        ],
        
        // Retry Logic (for outgoing notifications to Zain)
        'retry' => [
            'enabled' => env('ZAIN_RETRY_ENABLED', false),
            'max_attempts' => env('ZAIN_RETRY_ATTEMPTS', 3),
            'delay_seconds' => env('ZAIN_RETRY_DELAY', 60),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | DCB (Direct Carrier Billing) Configuration - LEGACY
    |--------------------------------------------------------------------------
    |
    | DEPRECATED: Use dynamic services from database instead.
    | See ServiceConfig::get('dcb_mediaworld') for new approach.
    |
    | This configuration is kept for backward compatibility.
    |
    */
    'dcb' => [
        'mode' => env('DCB_MODE', 'live'), // 'dummy' or 'live'
        
        // Live DCB Service (Legacy - use database services instead)
        'live' => [
            'host' => env('DCB_HOST', 'https://services.mediaworldiq.com:456'),
            'username' => env('DCB_USERNAME', 'MWtest'),
            'password' => env('DCB_PASSWORD', 'MWtest24'),
            'sp_id' => env('DCB_SP_ID', '2'),
            'service_id' => env('DCB_SERVICE_ID', '295'),
            'shortcode' => env('DCB_SHORTCODE', '4089'),
            'timeout' => env('DCB_TIMEOUT', 30),
            
            // Endpoints
            'endpoints' => [
                'send_pincode' => '/dcb/API/VMS-DCBSubscription/actions/sendPincode',
                'verify_pincode' => '/dcb/API/VMS-DCBSubscription/actions/verifyPincode',
                'unsubscribe' => '/dcb/API/VMS-DCBSubscription/actions/unsubscribe',
            ],
        ],
        
        // Dummy DCB
        'dummy' => [
            'auto_confirm' => true,
            'delay_ms' => 500,
            'default_pincode' => '1234',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | General API Settings
    |--------------------------------------------------------------------------
    */
    'general' => [
        // Country & Currency
        'country_code' => env('APP_COUNTRY_CODE', '964'), // Iraq
        'currency' => env('APP_CURRENCY', 'IQD'),
        'operator' => env('APP_OPERATOR', 'Zain Iraq'),
        
        // Phone Number Validation
        'phone' => [
            'country_code' => '964',
            'pattern' => '^7[0-9]{9}$', // Zain Iraq: 7xxxxxxxxx
            'format' => '+964 7XXXXXXXXX',
        ],
        
        // Rate Limiting
        'rate_limit' => [
            'otp_requests_per_minute' => env('RATE_LIMIT_OTP', 3),
            'api_requests_per_minute' => env('RATE_LIMIT_API', 60),
        ],
        
        // Global Timeouts
        'timeouts' => [
            'default' => 30,
            'otp' => 30,
            'content_portal' => 30,
            'zain_webhook' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Webhook URLs (For external services to call)
    |--------------------------------------------------------------------------
    */
    'webhooks' => [
        'zain_renewal' => env('APP_URL', 'http://localhost:8000') . '/api/zain/renewal/sync',
        'zain_bulk' => env('APP_URL', 'http://localhost:8000') . '/api/zain/renewal/bulk-sync',
        'dcb_callback' => env('APP_URL', 'http://localhost:8000') . '/dcb/callback',
    ],

    /*
    |--------------------------------------------------------------------------
    | External API URLs (For outgoing requests)
    |--------------------------------------------------------------------------
    */
    'external' => [
        'zain_status_check' => env('ZAIN_STATUS_API', ''),
        'zain_subscription' => env('ZAIN_SUBSCRIBE_API', ''),
        'zain_cancellation' => env('ZAIN_CANCEL_API', ''),
    ],

    /*
    |--------------------------------------------------------------------------
    | Evina DCB Integration Configuration
    |--------------------------------------------------------------------------
    |
    | Evina provides DCB (Direct Carrier Billing) with anti-fraud protection.
    | Supports OTP flow and Header Enrichment (HE) flow.
    |
    */
    'evina' => [
        'mode' => env('EVINA_MODE', 'dummy'), // 'dummy' or 'live'
        
        // Service Credentials
        'credentials' => [
            'username' => env('EVINA_USERNAME', ''),
            'password' => env('EVINA_PASSWORD', ''),
            'service_id' => env('EVINA_SERVICE_ID', ''),
            'sp_id' => env('EVINA_SP_ID', ''),
            'shortcode' => env('EVINA_SHORTCODE', ''),
            'merchant_name' => env('EVINA_MERCHANT_NAME', 'MediaWorld'),
            'service_name' => env('EVINA_SERVICE_NAME', 'MediaWorldIQ'),
            'transaction_prefix' => env('EVINA_TRANSACTION_PREFIX', 'MW'),
        ],
        
        // API Endpoints
        'endpoints' => [
            'base_url' => env('EVINA_BASE_URL', 'https://www.social-sms.com/iq-dcb'),
            'send_pincode' => '/dcb/API/VMSDCBSubscription/actions/sendPincode',
            'verify_pincode' => '/dcb/API/VMS-DCBSubscription/actions/verifyPincode',
            'unsubscribe' => '/dcb/API/VMSDCBSubscription/actions/unsubscribeUser',
            'get_script' => '/dcbprotect.php',
            'he_redirect' => '/HE/v1.3/doubleclick/sub.php',
        ],
        
        // Anti-Fraud Settings
        'antifraud' => [
            'enabled' => env('EVINA_ANTIFRAUD_ENABLED', true),
            'type_otp' => 'pin',
            'type_he' => 'he',
            'cta_button_id' => 'cta_button', // CTA button DOM ID
        ],
        
        // Flow Settings
        'flow' => [
            'default_flow' => env('EVINA_DEFAULT_FLOW', 'otp'), // 'otp' or 'he'
            'otp_page_type' => env('EVINA_OTP_PAGE_TYPE', 'single'), // 'single' or 'two_page'
        ],
        
        // Redirect URLs
        'redirect' => [
            'success_url' => env('APP_URL', 'http://localhost:8000') . '/evina/success',
            'failure_url' => env('APP_URL', 'http://localhost:8000') . '/evina/failure',
        ],
        
        // Dummy Mode Settings
        'dummy' => [
            'delay_ms' => 500,
            'default_pincode' => '1234',
            'auto_success' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    */
    'logging' => [
        'log_requests' => env('API_LOG_REQUESTS', true),
        'log_responses' => env('API_LOG_RESPONSES', true),
        'log_channel' => env('API_LOG_CHANNEL', 'daily'),
        'sensitive_fields' => ['password', 'api_key', 'api_secret', 'token'], // Don't log these
    ],

];

