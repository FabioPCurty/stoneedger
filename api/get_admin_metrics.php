<?php
header('Content-Type: application/json');

require_once __DIR__ . '/env.php';

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'apikey: ' . $supabaseKey,
    'Authorization: Bearer ' . $supabaseKey,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

function fetchSupabase($ch, $url)
{
    curl_setopt($ch, CURLOPT_URL, $url);
    $res = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        return ['error' => true, 'message' => curl_error($ch), 'type' => 'curl'];
    }

    $decoded = json_decode($res, true);

    if ($httpCode >= 400) {
        return [
            'error' => true,
            'message' => $decoded['message'] ?? $decoded['error'] ?? 'API Error',
            'status' => $httpCode,
            'type' => 'supabase'
        ];
    }

    return $decoded;
}

// 1. Fetch Portfolios
$portfoliosData = fetchSupabase($ch, $supabaseUrl . '/rest/v1/portfolios?select=id,user_id,name,created_at');

if (isset($portfoliosData['error'])) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Failed to fetch portfolios',
        'details' => $portfoliosData,
        'supabaseUrl' => substr($supabaseUrl, 0, 15) . '...',
        'hasKey' => !empty($supabaseKey)
    ]);
    exit;
}

// 2. Fetch User Names (Auth Admin API)
$usersData = fetchSupabase($ch, $supabaseUrl . '/auth/v1/admin/users');
$userNameMap = [];

if (isset($usersData['users']) && is_array($usersData['users'])) {
    foreach ($usersData['users'] as $user) {
        $fullName = $user['user_metadata']['full_name'] ?? $user['email'] ?? 'Usuário';
        $userNameMap[$user['id']] = $fullName;
    }
} else {
    // If Admin API is restricted or failing, we'll use portfolio names as fallback
}

$allUserIds = array_column($portfoliosData, 'user_id');
$uniqueUsers = array_unique($allUserIds);
$totalUsersCount = count($uniqueUsers);

// 3. Fetch Assets
$assetsData = fetchSupabase($ch, $supabaseUrl . '/rest/v1/user_assets?select=ticker,quantity,avg_price,portfolio_id');

if (!is_array($assetsData)) {
    $assetsData = [];
}

// 4. Fetch Quotes
$quotesData = fetchSupabase($ch, $supabaseUrl . '/rest/v1/stock_fundamentals?select=papel,cotacao');

$quotesMap = [];
if (is_array($quotesData)) {
    foreach ($quotesData as $q) {
        if (isset($q['papel'])) {
            $quotesMap[$q['papel']] = floatval($q['cotacao'] ?? 0);
        }
    }
}

$totalVolume = 0;
foreach ($assetsData as $asset) {
    if (isset($asset['ticker'], $asset['quantity'])) {
        $ticker = $asset['ticker'];
        $qty = floatval($asset['quantity']);
        $price = $quotesMap[$ticker] ?? 0;
        $totalVolume += ($qty * $price);
    }
}

// 5. Calculate Stats
$portfolioToUser = [];
foreach ($portfoliosData as $p) {
    $portfolioToUser[$p['id']] = $p['user_id'];
}

$activeUserIds = [];
foreach ($assetsData as $a) {
    if (isset($a['portfolio_id'])) {
        $pid = $a['portfolio_id'];
        if (isset($portfolioToUser[$pid])) {
            $activeUserIds[] = $portfolioToUser[$pid];
        }
    }
}
$uniqueActiveUsers = array_unique($activeUserIds);
$activeUsersCount = count($uniqueActiveUsers);

$adherenceRate = ($totalUsersCount > 0) ? ($activeUsersCount / $totalUsersCount) * 100 : 0;

// 5.1 Calculate New Users (last 7 days)
$newUsersCount = 0;
$sevenDaysAgo = strtotime('-7 days');
foreach ($portfoliosData as $p) {
    if (isset($p['created_at']) && strtotime($p['created_at']) > $sevenDaysAgo) {
        $newUsersCount++;
    }
}

// 6. Recent Users
usort($portfoliosData, function ($a, $b) {
    return strtotime($b['created_at']) - strtotime($a['created_at']);
});

$recentUsers = [];
$seenUsers = [];
foreach ($portfoliosData as $p) {
    if (!in_array($p['user_id'], $seenUsers) && count($recentUsers) < 5) {
        $uid = $p['user_id'];
        $seenUsers[] = $uid;

        $userInvested = 0;
        foreach ($assetsData as $asset) {
            if ($asset['portfolio_id'] === $p['id']) {
                $qty = floatval($asset['quantity']);
                $avgPrice = floatval($asset['avg_price'] ?? 0);
                $userInvested += ($qty * $avgPrice);
            }
        }

        $recentUsers[] = [
            'user_id' => $uid,
            'user_name' => $userNameMap[$uid] ?? $p['name'] ?? 'Usuário',
            'portfolio_name' => $p['name'] ?? 'Carteira',
            'created_at' => $p['created_at'] ?? date('c'),
            'total_value' => $userInvested,
            'status' => ($userInvested > 0) ? 'Ativo' : 'Novo'
        ];
    }
}

echo json_encode([
    'total_users' => $totalUsersCount,
    'total_volume' => $totalVolume,
    'adherence_rate' => $adherenceRate,
    'active_users' => $activeUsersCount,
    'new_users' => $newUsersCount,
    'recent_users' => $recentUsers,
    'debug' => [
        'portfolios_count' => count($portfoliosData),
        'assets_count' => count($assetsData),
        'users_found' => count($userNameMap),
        'new_users_found' => $newUsersCount
    ]
]);

curl_close($ch);
?>