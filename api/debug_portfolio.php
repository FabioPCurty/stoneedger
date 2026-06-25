<?php
header('Content-Type: application/json');

require_once __DIR__ . '/session_handler.php';
if (file_exists(__DIR__ . '/env.php')) {
    require_once __DIR__ . '/env.php';
}

$user_id = $_SESSION['user_id'] ?? null;
$access_token = $_SESSION['access_token'] ?? null;

// Test 1: Using user's access_token (same as the real API)
$resultWithUserToken = null;
$statusWithUserToken = null;
if ($access_token) {
    $ch = curl_init();
    $url = $supabaseUrl . '/rest/v1/portfolios?user_id=eq.' . $user_id . '&select=id,name';
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'apikey: ' . $supabaseKey,
        'Authorization: Bearer ' . $access_token,
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $resultWithUserToken = curl_exec($ch);
    $statusWithUserToken = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $errWithUserToken = curl_error($ch);
    curl_close($ch);
}

// Test 2: Using service_role key (bypasses RLS)
$serviceKey = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InB1eHVpbGtleG1qcGpucmtxeXNxIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc2MjczNTY4OCwiZXhwIjoyMDc4MzExNjg4fQ.U2OCQ4GIiHIAClFbHsRl1cA5ol8yPLkkAkxgipA1B5Y';
$ch2 = curl_init();
$url2 = $supabaseUrl . '/rest/v1/portfolios?user_id=eq.' . $user_id . '&select=id,name';
curl_setopt($ch2, CURLOPT_URL, $url2);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_HTTPHEADER, [
    'apikey: ' . $serviceKey,
    'Authorization: Bearer ' . $serviceKey,
]);
curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
$resultWithServiceKey = curl_exec($ch2);
$statusWithServiceKey = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
curl_close($ch2);

echo json_encode([
    'session' => [
        'user_id' => $user_id,
        'has_access_token' => !empty($access_token),
        'access_token_preview' => $access_token ? substr($access_token, 0, 30) . '...' : null,
    ],
    'env' => [
        'supabaseUrl' => $supabaseUrl,
        'supabaseKey_preview' => substr($supabaseKey, 0, 30) . '...',
    ],
    'test_with_user_token' => [
        'http_status' => $statusWithUserToken,
        'response' => $resultWithUserToken ? json_decode($resultWithUserToken) : null,
        'curl_error' => $errWithUserToken ?? null,
    ],
    'test_with_service_key' => [
        'http_status' => $statusWithServiceKey,
        'response' => $resultWithServiceKey ? json_decode($resultWithServiceKey) : null,
    ],
], JSON_PRETTY_PRINT);
?>
