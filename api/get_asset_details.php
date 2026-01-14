<?php
header('Content-Type: application/json');
require_once __DIR__ . '/session_handler.php';

// Disable error reporting for production output
error_reporting(0);
ini_set('display_errors', 0);

// allow public access for search, but use user token if available
// if (!isset($_SESSION['user_id'])) {
//     http_response_code(401);
//     echo json_encode(['error' => 'Não autorizado']);
//     exit;
// }

$ticker = trim($_GET['ticker'] ?? '');
if (empty($ticker)) {
    http_response_code(400);
    echo json_encode(['error' => 'Ticker não fornecido']);
    exit;
}

$token = $_SESSION['access_token'] ?? null;
$authHeader = 'Authorization: Bearer ' . ($token ?: $supabaseKey);

/**
 * Helper to fetch from Supabase
 */
function fetchFromSupabase($url, $key, $authHeader)
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
    curl_close($ch);
    return ['body' => $res, 'code' => $code];
}

// Attempt 1: Exact match
$url = $supabaseUrl . '/rest/v1/stock_fundamentals?papel=eq.' . urlencode($ticker) . '&select=*';
$result = fetchFromSupabase($url, $supabaseKey, $authHeader);
$data = json_decode($result['body'], true);

// Attempt 2: Case-insensitive match if empty
if (empty($data)) {
    $url = $supabaseUrl . '/rest/v1/stock_fundamentals?papel=ilike.' . urlencode($ticker) . '&select=*';
    $result = fetchFromSupabase($url, $supabaseKey, $authHeader);
    $data = json_decode($result['body'], true);
}

// Final output
if (empty($data)) {
    http_response_code(404);
    echo json_encode(['error' => 'Asset not found']);
} else {
    echo json_encode($data[0]);
}
?>