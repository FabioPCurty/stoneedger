<?php
header('Content-Type: application/json');
require_once __DIR__ . '/session_handler.php';

// Suppress errors to keep JSON clean, but they will be in Vercel logs
error_reporting(E_ALL);
ini_set('display_errors', 0);

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Não autorizado']);
    exit;
}

$ticker = trim($_GET['ticker'] ?? '');
if (empty($ticker)) {
    http_response_code(400);
    echo json_encode(['error' => 'Ticker não fornecido']);
    exit;
}

/**
 * Helper to fetch from Supabase
 */
function querySupabase($url, $key, $token)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'apikey: ' . $key,
        'Authorization: Bearer ' . ($token ?: $key),
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $res = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);
    return ['body' => $res, 'code' => $code, 'error' => $err];
}

$token = $_SESSION['access_token'] ?? null;

// 1. First attempt: Use exact match with 'in' operator (same as dashboard)
$url = $supabaseUrl . '/rest/v1/stock_fundamentals?papel=in.(' . urlencode($ticker) . ')&select=*';
$result = querySupabase($url, $supabaseKey, $token);
$data = json_decode($result['body'], true);

// 2. Second attempt: If empty, try case-insensitive wildcard match
if ((empty($data) || !is_array($data)) && $result['code'] === 200) {
    $url = $supabaseUrl . '/rest/v1/stock_fundamentals?papel=ilike.*' . urlencode($ticker) . '*&select=*';
    $result = querySupabase($url, $supabaseKey, $token);
    $data = json_decode($result['body'], true);
}

// Ensure we return an array
if (!is_array($data)) {
    $data = [];
}

// If we still have no data but the dashboard works, it might be a token permission issue
// 3. Third attempt: try without user token (only public key) if allowed
if (empty($data) && $token) {
    $url = $supabaseUrl . '/rest/v1/stock_fundamentals?papel=ilike.*' . urlencode($ticker) . '*&select=*';
    $result = querySupabase($url, $supabaseKey, null);
    $data = json_decode($result['body'], true);
}

http_response_code($result['code']);
echo json_encode($data);
?>