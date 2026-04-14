<?php

require __DIR__ . '/vendor/autoload.php';

use Firebase\JWT\JWT;

// Payload
$payload = [
    'expires_in' => 259200000,
    'user_id' => '9640000000001',
    'service_id' => 7,
];

// Load private key from /var/www/html/private_key.pem
$privateKeyPath = '/var/www/html/private_key.pem';

if (!file_exists($privateKeyPath)) {
    die("Error: Private key not found at: {$privateKeyPath}\n");
}

$privateKey = file_get_contents($privateKeyPath);

// Generate JWT token
$token = JWT::encode($payload, $privateKey, 'RS256');

echo "Generated JWT Token:\n";
echo str_repeat('=', 80) . "\n";
echo $token . "\n";
echo str_repeat('=', 80) . "\n\n";

echo "cURL Command:\n";
echo str_repeat('=', 80) . "\n";
echo "curl -X POST https://billing.quickfun.games/mediaworld/token \\\n";
echo "  -H 'Content-Type: application/jwt' \\\n";
echo "  --data-raw '{$token}'\n";
echo str_repeat('=', 80) . "\n\n";

echo "Testing the API...\n";
echo str_repeat('=', 80) . "\n";

// Make the API request
$ch = curl_init('https://billing.quickfun.games/mediaworld/token');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $token);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/jwt']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

curl_close($ch);

echo "HTTP Status Code: {$httpCode}\n";
if ($error) {
    echo "Error: {$error}\n";
}
echo "Response:\n";
echo $response . "\n";
echo str_repeat('=', 80) . "\n";

