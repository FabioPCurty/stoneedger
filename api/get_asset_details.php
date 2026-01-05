<?php
header('Content-Type: application/json');
require_once __DIR__ . '/session_handler.php';

// Disable error suppression for debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Não autorizado', 'session' => $_SESSION]);
    exit;
}

$ticker = trim($_GET['ticker'] ?? '');
if (empty($ticker)) {
    http_response_code(400);
    echo json_encode(['error' => 'Ticker não fornecido']);
    exit;
}

$token = $_SESSION['access_token'] ?? null;
$authHeader = 'Authorization: Bearer ' . ($token ?: $supabaseKey);

// Debug info container
$debug = [
    'ticker' => $ticker,
    'has_token' => !empty($token),
    'supabase_url' => $supabaseUrl,
    'attempts' => []
];

/**
 * Helper with debug
 */
function fetchDebug($url, $key, $authHeader)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'apikey: ' . $key,
        $authHeader,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $res = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);
    return ['body' => $res, 'code' => $code, 'error' => $err];
}

// Attempt 1: Exact match (The most likely to work if standard)
$url1 = $supabaseUrl . '/rest/v1/stock_fundamentals?papel=eq.' . urlencode($ticker) . '&select=*';
$res1 = fetchDebug($url1, $supabaseKey, $authHeader);
$debug['attempts'][] = ['url' => $url1, 'code' => $res1['code'], 'error' => $res1['error'], 'response' => json_decode($res1['body'], true)];

// Attempt 2: Case-insensitive match
if (empty($debug['attempts'][0]['response'])) {
    $url2 = $supabaseUrl . '/rest/v1/stock_fundamentals?papel=ilike.' . urlencode($ticker) . '&select=*';
    $res2 = fetchDebug($url2, $supabaseKey, $authHeader);
    if ($res2['code'] === 200) {
        $debug['attempts'][] = ['url' => $url2, 'code' => $res2['code'], 'response' => json_decode($res2['body'], true)];
    }
}

// Final decision logic
$finalData = [];
foreach ($debug['attempts'] as $attempt) {
    if (!empty($attempt['response']) && is_array($attempt['response'])) {
        $finalData = $attempt['response'];
        break;
    }
}

// If STILL empty, let's try a wildcard search for any ticker that CONTAINS the string
if (empty($finalData)) {
    $url3 = $supabaseUrl . '/rest/v1/stock_fundamentals?papel=ilike.*' . urlencode($ticker) . '*&select=*';
    $res3 = fetchDebug($url3, $supabaseKey, $authHeader);
    $finalData = json_decode($res3['body'], true) ?: [];
    $debug['attempts'][] = ['url' => $url3, 'code' => $res3['code'], 'response' => $finalData];
}

if (empty($finalData)) {
    http_response_code(404);
    echo json_encode(['error' => 'Asset not found', 'debug' => $debug]);
} else {
    // Return the first match but include debug for me to see
    $response = $finalData[0];
    $response['_debug'] = $debug;
    echo json_encode($response);
}
?>