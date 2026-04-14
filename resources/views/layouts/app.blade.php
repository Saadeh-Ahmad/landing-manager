<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="referrer" content="unsafe-url">
    <title>@yield('title', 'Media World')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        /* RTL Support */
        [dir="rtl"] {
            direction: rtl;
        }
        
        /* Reverse margins for RTL - using CSS logical properties approach */
        [dir="rtl"] .mr-1 { margin-right: 0; margin-left: 0.25rem; }
        [dir="rtl"] .mr-2 { margin-right: 0; margin-left: 0.5rem; }
        [dir="rtl"] .mr-3 { margin-right: 0; margin-left: 0.75rem; }
        [dir="rtl"] .ml-1 { margin-left: 0; margin-right: 0.25rem; }
        [dir="rtl"] .ml-2 { margin-left: 0; margin-right: 0.5rem; }
        [dir="rtl"] .ml-3 { margin-left: 0; margin-right: 0.75rem; }
        
        /* Reverse padding for RTL */
        [dir="rtl"] .pr-1 { padding-right: 0; padding-left: 0.25rem; }
        [dir="rtl"] .pr-2 { padding-right: 0; padding-left: 0.5rem; }
        [dir="rtl"] .pr-3 { padding-right: 0; padding-left: 0.75rem; }
        [dir="rtl"] .pl-1 { padding-left: 0; padding-right: 0.25rem; }
        [dir="rtl"] .pl-2 { padding-left: 0; padding-right: 0.5rem; }
        [dir="rtl"] .pl-3 { padding-left: 0; padding-right: 0.75rem; }
        
        /* Text alignment */
        [dir="rtl"] .text-left { text-align: right; }
        [dir="rtl"] .text-right { text-align: left; }
        
        /* Keep header/navbar in LTR direction */
        [dir="rtl"] header {
            direction: ltr;
        }
        
        /* Keep language switcher in LTR direction (for OTP pages) */
        [dir="rtl"] .language-switcher {
            direction: ltr;
        }
        
        /* Keep phone number container in LTR direction */
        [dir="rtl"] .phone-input-container {
            direction: ltr;
            flex-direction: row;
        }
        
        /* Keep phone number fields and country code in LTR */
        [dir="rtl"] #msisdn,
        [dir="rtl"] input[name="msisdn"],
        [dir="rtl"] #pincode,
        [dir="rtl"] input[name="pincode"],
        [dir="rtl"] .country-code {
            direction: ltr;
            text-align: left;
        }
        
        [dir="rtl"] #pincode,
        [dir="rtl"] input[name="pincode"] {
            text-align: center;
        }
    </style>
</head>
<body class="bg-gray-100">
    @yield('content')
</body>
</html>

