<?php
header('Content-Type: application/json');
require_once __DIR__ . '/env.php';

$debug = [
    'supabase_url_exists' => !empty($supabaseUrl),
    'supabase_key_exists' => !empty($supabaseKey),
    'supabase_url_masked' => $supabaseUrl ? substr($supabaseUrl, 0, 15) . '...' : 'MISSING',
    'env_vars' => [
        'URL_VIA_GETENV' => getenv('SUPABASE_URL') ? 'YES' : 'NO',
        'KEY_VIA_GETENV' => getenv('SUPABASE_KEY') ? 'YES' : 'NO',
        'URL_VIA_ENV' => isset($_ENV['SUPABASE_URL']) ? 'YES' : 'NO',
        'KEY_VIA_ENV' => isset($_ENV['SUPABASE_KEY']) ? 'YES' : 'NO',
    ]
];

// Test connectivity
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $supabaseUrl . '/rest/v1/portfolios?select=id&limit=1');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'apikey: ' . $supabaseKey,
    'Authorization: Bearer ' . $supabaseKey,
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$res = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

$debug['connectivity_test'] = [
    'http_code' => $httpCode,
    'curl_error' => curl_error($ch),
    'response_preview' => substr($res, 0, 100)
];

curl_close($ch);

echo json_encode($debug);
