<?php
header('Content-Type: application/json');
require_once __DIR__ . '/session_handler.php';
require_once __DIR__ . '/env.php';

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

// 12 top players to scan for dividends
$tickers = ['PETR4', 'VALE3', 'ITUB4', 'BBDC4', 'BBAS3', 'TAEE11', 'EGIE3', 'CPLE6', 'CMIG4', 'WEGE3', 'KLBN11', 'SUZB3'];

$mh = curl_multi_init();
$curl_handles = [];

foreach ($tickers as $ticker) {
    // Only fetch dividends=true
    $url = "https://brapi.dev/api/quote/{$ticker}?dividends=true&token={$brapiToken}";
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

$all_dividends = [];

foreach ($curl_handles as $ticker => $ch) {
    $response = curl_multi_getcontent($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_multi_remove_handle($mh, $ch);
    
    if ($httpcode == 200 && $response) {
        $data = json_decode($response, true);
        if (isset($data['results'][0]['dividendsData']['cashDividends'])) {
            $divs = $data['results'][0]['dividendsData']['cashDividends'];
            foreach ($divs as $div) {
                // Ignore missing dates
                if (!isset($div['paymentDate']) || !isset($div['approvedOn'])) continue;
                
                $all_dividends[] = [
                    'ticker' => $ticker,
                    'type' => $div['label'] ?? 'DIV', // JCP or DIVIDENDO
                    'value' => (float)($div['rate'] ?? 0),
                    'approvedOn' => $div['approvedOn'],
                    'lastDatePrior' => $div['lastDatePrior'] ?? $div['approvedOn'], // DateEx approx
                    'paymentDate' => $div['paymentDate']
                ];
            }
        }
    }
}
curl_multi_close($mh);

// Sort by paymentDate descending
usort($all_dividends, function ($a, $b) {
    $timeA = strtotime($a['paymentDate']);
    $timeB = strtotime($b['paymentDate']);
    return $timeB - $timeA; 
});

// Take the 15 most recent declarations to filter to nearest future/present
// Since Brapi free tier is historical, we show the most recent ones.
$recent_dividends = array_slice($all_dividends, 0, 7);

echo json_encode(["results" => $recent_dividends]);
?>
