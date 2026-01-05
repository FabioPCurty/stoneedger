<?php
header('Content-Type: application/json');
require_once __DIR__ . '/session_handler.php';

if (!isset($_SESSION['user_id'])) {
    exit('Unauthorized');
}

$token = $_SESSION['access_token'] ?? $supabaseKey;

function probeSupabase($url, $key, $token)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'apikey: ' . $key,
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $res = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['body' => json_decode($res, true), 'code' => $code];
}

// Probe 1: Get first 10 assets to see column names and values
$url = $supabaseUrl . '/rest/v1/stock_fundamentals?select=*&limit=10';
$probe1 = probeSupabase($url, $supabaseKey, $token);

// Probe 2: Try a search with a very loose filter
$url = $supabaseUrl . '/rest/v1/stock_fundamentals?papel=ilike.*BBS*&select=papel';
$probe2 = probeSupabase($url, $supabaseKey, $token);

echo json_encode([
    'probe1_first_10' => $probe1,
    'probe2_loose_search' => $probe2,
    'requested_ticker' => $_GET['ticker'] ?? 'None'
]);
?>