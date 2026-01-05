<?php
header('Content-Type: application/json');
require_once __DIR__ . '/session_handler.php';

$debug = [
    'session_user' => $_SESSION['user_id'] ?? 'none',
    'has_token' => isset($_SESSION['access_token']),
    'results' => []
];

function tryFetch($name, $url, $key, $token = null)
{
    $headers = ['apikey: ' . $key];
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    } else {
        $headers[] = 'Authorization: Bearer ' . $key; // Fallback to anon role
    }

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $res = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return ['name' => $name, 'code' => $code, 'body' => json_decode($res, true)];
}

// 1. Check Row Count (Total)
$debug['results'][] = tryFetch('Count Total', $supabaseUrl . '/rest/v1/stock_fundamentals?select=count', $supabaseKey, $_SESSION['access_token'] ?? null);

// 2. Check Row Count (Anon Mode)
$debug['results'][] = tryFetch('Count Anon', $supabaseUrl . '/rest/v1/stock_fundamentals?select=count', $supabaseKey, null);

// 3. Simple Select first 3 (Anon Mode)
$debug['results'][] = tryFetch('Select 3 Anon', $supabaseUrl . '/rest/v1/stock_fundamentals?select=papel,empresa,cotacao&limit=3', $supabaseKey, null);

echo json_encode($debug);
?>