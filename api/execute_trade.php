<?php
header('Content-Type: application/json');

require_once __DIR__ . '/session_handler.php';
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    http_response_code(401);
    echo json_encode(['error' => 'Não autorizado']);
    exit;
}

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);
$ticker = strtoupper($input['ticker'] ?? '');
$quantity = floatval($input['quantity'] ?? 0);
$price = floatval($input['price'] ?? 0);
$date = $input['date'] ?? date('Y-m-d');
$type = $input['type'] ?? 'buy'; // 'buy' or 'sell'

if (empty($ticker) || $quantity <= 0 || $price <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Dados inválidos. Verifique ticker, quantidade e preço.']);
    exit;
}

// Authorization header: Use user's access token if available, otherwise use public key
$authHeader = isset($_SESSION['access_token']) ? 'Authorization: Bearer ' . $_SESSION['access_token'] : 'Authorization: Bearer ' . $supabaseKey;

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'apikey: ' . $supabaseKey,
    $authHeader,
    'Content-Type: application/json',
    'Prefer: return=representation'
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// 1. Get Portfolio ID
$portfolioUrl = $supabaseUrl . '/rest/v1/portfolios?user_id=eq.' . $user_id . '&select=id';
curl_setopt($ch, CURLOPT_URL, $portfolioUrl);
$portfolioRes = curl_exec($ch);
$portfolioData = json_decode($portfolioRes, true);
$portfolioId = $portfolioData[0]['id'] ?? null;

if (!$portfolioId) {
    // Try to create a default portfolio if not exists
    $createUrl = $supabaseUrl . '/rest/v1/portfolios';
    curl_setopt($ch, CURLOPT_URL, $createUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'user_id' => $user_id,
        'name' => 'Meu Portfólio Principal'
    ]));
    $createRes = curl_exec($ch);
    $createData = json_decode($createRes, true);
    $portfolioId = $createData[0]['id'] ?? null;

    if (!$portfolioId) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao obter/criar portfólio.']);
        exit;
    }
    // Reset for next calls
    curl_setopt($ch, CURLOPT_POST, false);
}

// 2. Check if asset already exists in the portfolio
$checkUrl = $supabaseUrl . '/rest/v1/user_assets?portfolio_id=eq.' . $portfolioId . '&ticker=eq.' . $ticker . '&select=*';
curl_setopt($ch, CURLOPT_URL, $checkUrl);
$checkRes = curl_exec($ch);
$checkData = json_decode($checkRes, true);

if (!empty($checkData)) {
    // 3. Update existing asset (Consolidate)
    $asset = $checkData[0];
    $oldQty = floatval($asset['quantity']);
    $oldAvg = floatval($asset['avg_price']);

    if ($type === 'buy') {
        $newQty = $oldQty + $quantity;
        $newAvg = (($oldQty * $oldAvg) + ($quantity * $price)) / $newQty;
    } else {
        // Sell
        if ($oldQty < $quantity) {
            http_response_code(400);
            echo json_encode(['error' => 'Quantidade insuficiente para venda.']);
            exit;
        }
        $newQty = $oldQty - $quantity;
        $newAvg = $oldAvg;
    }

    if ($newQty > 0) {
        $updateUrl = $supabaseUrl . '/rest/v1/user_assets?id=eq.' . $asset['id'];
        curl_setopt($ch, CURLOPT_URL, $updateUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'quantity' => $newQty,
            'avg_price' => $newAvg,
            'purchase_date' => $date
        ]));
    } else {
        // Remove asset if quantity reaches 0
        $deleteUrl = $supabaseUrl . '/rest/v1/user_assets?id=eq.' . $asset['id'];
        curl_setopt($ch, CURLOPT_URL, $deleteUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }
    $finalRes = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
} else {
    // 4. Insert new asset
    if ($type === 'sell') {
        http_response_code(400);
        echo json_encode(['error' => 'Você não possui este ativo para vender.']);
        exit;
    }
    $insertUrl = $supabaseUrl . '/rest/v1/user_assets';
    curl_setopt($ch, CURLOPT_URL, $insertUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'portfolio_id' => $portfolioId,
        'ticker' => $ticker,
        'quantity' => $quantity,
        'avg_price' => $price,
        'purchase_date' => $date
    ]));
    $finalRes = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
}

if ($httpCode >= 200 && $httpCode < 300) {
    echo json_encode(['success' => true, 'message' => 'Operação realizada com sucesso!']);
} else {
    http_response_code($httpCode);
    echo json_encode(['error' => 'Erro ao salvar ativo no banco.', 'details' => $finalRes]);
}

curl_close($ch);
?>