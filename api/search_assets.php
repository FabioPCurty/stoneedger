<?php
header('Content-Type: application/json');

require_once __DIR__ . '/session_handler.php';
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Não autorizado']);
    exit;
}

$query = $_GET['q'] ?? '';
if (strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

// Search for assets in stock_fundamentals
// We search both ticker (papel) and company name (empresa)
$url = $supabaseUrl . '/rest/v1/stock_fundamentals?or=(papel.ilike.*' . urlencode($query) . '*,empresa.ilike.*' . urlencode($query) . '*)&select=papel,empresa,cotacao&limit=10';

// Authorization header: Use user's access token if available, otherwise use public key
$authHeader = isset($_SESSION['access_token']) ? 'Authorization: Bearer ' . $_SESSION['access_token'] : 'Authorization: Bearer ' . $supabaseKey;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'apikey: ' . $supabaseKey,
    $authHeader,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    http_response_code(500);
    echo json_encode(['error' => 'Curl error: ' . curl_error($ch)]);
} else {
    http_response_code($httpCode);
    echo $response;
}
curl_close($ch);
?>