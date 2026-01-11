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

// Test connectivity (REST API)
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

$debug['rest_auth_test'] = [
    'http_code' => $httpCode,
    'count' => is_array(json_decode($res, true)) ? count(json_decode($res, true)) : 0,
    'response_preview' => substr($res, 0, 100)
];

// Test Admin Auth access (requires Service Role Key)
curl_setopt($ch, CURLOPT_URL, $supabaseUrl . '/auth/v1/admin/users?limit=1');
$resAdmin = curl_exec($ch);
$httpCodeAdmin = curl_getinfo($ch, CURLINFO_HTTP_CODE);

$debug['admin_auth_test'] = [
    'http_code' => $httpCodeAdmin,
    'is_service_role' => ($httpCodeAdmin === 200),
    'response_preview' => substr($resAdmin, 0, 100)
];

curl_close($ch);

echo json_encode($debug);
