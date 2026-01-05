<?php
header('Content-Type: application/json');

require_once __DIR__ . '/session_handler.php';
$user_id = $_SESSION['user_id'] ?? ($_GET['user_id'] ?? null);

if (!$user_id) {
    http_response_code(401);
    echo json_encode(['error' => 'Não autorizado']);
    exit;
}

// 1. Fetch Portfolio ID
$portfolioUrl = $supabaseUrl . '/rest/v1/portfolios?user_id=eq.' . $user_id . '&select=id';
// Authorization header: Use user's access token if available, otherwise use public key
$authHeader = isset($_SESSION['access_token']) ? 'Authorization: Bearer ' . $_SESSION['access_token'] : 'Authorization: Bearer ' . $supabaseKey;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $portfolioUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['apikey: ' . $supabaseKey, $authHeader]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$portfolioRes = curl_exec($ch);
$portfolioData = json_decode($portfolioRes, true);
$portfolioId = $portfolioData[0]['id'] ?? null;

if (!$portfolioId) {
    echo json_encode([
        'summary' => [
            'total_value' => 0,
            'daily_gain' => 0,
            'daily_gain_pct' => 0,
            'total_gain' => 0,
            'total_gain_pct' => 0
        ],
        'assets' => []
    ]);
    exit;
}

// 2. Fetch User Assets
$assetsUrl = $supabaseUrl . '/rest/v1/user_assets?portfolio_id=eq.' . $portfolioId . '&select=*';
curl_setopt($ch, CURLOPT_URL, $assetsUrl);
$assetsRes = curl_exec($ch);
$userAssets = json_decode($assetsRes, true);

// 3. Fetch Fundamental Data for all assets in portfolio
$tickers = array_map(function ($a) {
    return $a['ticker'];
}, $userAssets);
$tickersFilter = implode(',', array_map(function ($t) {
    return '"' . $t . '"';
}, $tickers));
$fundamentalsUrl = $supabaseUrl . '/rest/v1/stock_fundamentals?papel=in.(' . urlencode(implode(',', $tickers)) . ')&select=*';
curl_setopt($ch, CURLOPT_URL, $fundamentalsUrl);
$fundamentalsRes = curl_exec($ch);
$fundamentals = json_decode($fundamentalsRes, true);

// Map fundamentals by ticker for easy access
$fundamentalsMap = [];
foreach ($fundamentals as $f) {
    $fundamentalsMap[$f['papel']] = $f;
}

// 4. Consolidate Data
$assetsList = [];
$totalValue = 0;
$totalInvested = 0;
$totalPrevValue = 0; // For daily change calculation

foreach ($userAssets as $ua) {
    $ticker = $ua['ticker'];
    $fund = $fundamentalsMap[$ticker] ?? null;

    $qty = floatval($ua['quantity']);
    $avgPrice = floatval($ua['avg_price']);
    $invested = $qty * $avgPrice;

    $currentPrice = $fund ? floatval($fund['cotacao']) : $avgPrice;
    $oscDia = $fund ? floatval($fund['osc_dia']) : 0;

    $currentValue = $qty * $currentPrice;
    $totalValue += $currentValue;
    $totalInvested += $invested;

    // Calculate previous day value for daily change
    // If osc_dia is 0.0125 (1.25%), then current = prev * (1 + 0.0125)
    // prev = current / (1 + osc_dia)
    $prevPrice = $currentPrice / (1 + $oscDia);
    $totalPrevValue += ($qty * $prevPrice);

    $assetsList[] = [
        'ticker' => $ticker,
        'empresa' => $fund['empresa'] ?? 'N/A',
        'setor' => $fund['setor'] ?? 'N/A',
        'quantidade' => $qty,
        'preco_medio' => $avgPrice,
        'cotacao' => $currentPrice,
        'valor_atual' => $currentValue,
        'ganho_total' => $currentValue - $invested,
        'ganho_total_pct' => $invested > 0 ? ($currentValue - $invested) / $invested : 0,
        'osc_dia' => $oscDia,
        'p_l' => $fund['p_l'] ?? null,
        'p_vp' => $fund['p_vp'] ?? null,
        'div_yield' => $fund['div_yield'] ?? null,
        'marg_liquida' => $fund['marg_liquida'] ?? null
    ];
}

$dailyGain = $totalValue - $totalPrevValue;
$dailyGainPct = $totalPrevValue > 0 ? ($totalValue - $totalPrevValue) / $totalPrevValue : 0;
$totalGain = $totalValue - $totalInvested;
$totalGainPct = $totalInvested > 0 ? ($totalValue - $totalInvested) / $totalInvested : 0;

echo json_encode([
    'summary' => [
        'total_value' => $totalValue,
        'total_invested' => $totalInvested,
        'daily_gain' => $dailyGain,
        'daily_gain_pct' => $dailyGainPct,
        'total_gain' => $totalGain,
        'total_gain_pct' => $totalGainPct,
        'asset_count' => count($userAssets)
    ],
    'assets' => $assetsList
]);

curl_close($ch);
