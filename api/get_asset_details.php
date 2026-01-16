<?php
header('Content-Type: application/json');
require_once __DIR__ . '/session_handler.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

$pythonPath = __DIR__ . '/../.venv/Scripts/python.exe';
$scriptPath = __DIR__ . '/../atualizar_supabase.py';

/**
 * Executes the python crawler for a single ticker
 */
function crawlTicker($ticker, $pythonPath, $scriptPath)
{
    $command = escapeshellcmd("$pythonPath $scriptPath --ticker " . escapeshellarg($ticker));
    // Execute and capture output for debug logging if needed
    exec($command . " 2>&1", $output, $return_var);
    return $return_var === 0;
}

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

// Final check and freshness logic
$shouldCrawl = false;
if (empty($data)) {
    $shouldCrawl = true;
} else {
    // Check if data is stale (older than 6 hours)
    $updatedAt = new DateTime($data[0]['updated_at']);
    $now = new DateTime();
    $diff = $now->getTimestamp() - $updatedAt->getTimestamp();

    // 6 hours = 21600 seconds
    if ($diff > 21600) {
        $shouldCrawl = true;
    }
}

if ($shouldCrawl) {
    if (crawlTicker($ticker, $pythonPath, $scriptPath)) {
        // Re-fetch from Supabase after successful crawl
        $result = fetchFromSupabase($url, $supabaseKey, $authHeader);
        $data = json_decode($result['body'], true);
    }
}

// Final output
if (empty($data)) {
    http_response_code(404);
    echo json_encode(['error' => 'Asset not found even after crawl attempt']);
} else {
    echo json_encode($data[0]);
}
?>