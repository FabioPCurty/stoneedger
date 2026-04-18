<?php
header('Content-Type: application/json');
require_once __DIR__ . '/session_handler.php';
require_once __DIR__ . '/env.php';

// Endpoint can only be called if authenticated
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Não autorizado']);
    exit;
}

$brapiToken = get_env_var('BRAPI_TOKEN');

if (empty($brapiToken)) {
    http_response_code(500);
    echo json_encode(['error' => 'BRAPI_TOKEN não está configurado no .env']);
    exit;
}

// Tickers for International Indices
$tickers = ['^DJI', '^GSPC', '^IXIC', '^FTSE', '^N225', '^GDAXI'];
$results = [];

$mh = curl_multi_init();
$curl_handles = [];

foreach ($tickers as $ticker) {
    $url = "https://brapi.dev/api/quote/{$ticker}?token={$brapiToken}";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $curl_handles[$ticker] = $ch;
    curl_multi_add_handle($mh, $ch);
}

$active = null;
do {
    $status = curl_multi_exec($mh, $active);
    if ($active) {
        curl_multi_select($mh);
    }
} while ($active && $status == CURLM_OK);

foreach ($curl_handles as $ticker => $ch) {
    $response = curl_multi_getcontent($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_multi_remove_handle($mh, $ch);
    
    if ($httpcode == 200 && $response) {
        $data = json_decode($response, true);
        if (isset($data['results'][0])) {
            $results[] = $data['results'][0];
        }
    }
}

curl_multi_close($mh);

echo json_encode(["results" => $results]);
?>
