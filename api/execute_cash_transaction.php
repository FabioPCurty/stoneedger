<?php
header('Content-Type: application/json');

require_once __DIR__ . '/session_handler.php';
// Explicitly load env vars (defines $supabaseUrl and $supabaseKey)
if (file_exists(__DIR__ . '/env.php')) {
    require_once __DIR__ . '/env.php';
}
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    http_response_code(401);
    echo json_encode(['error' => 'Não autorizado']);
    exit;
}

// Get POST data
$input = json_decode(file_get_contents('php://input'), true);
$type = $input['type'] ?? ''; // 'deposit', 'withdrawal', 'dividend', 'jcp', 'rent'
$amount = floatval($input['amount'] ?? 0);
$description = trim($input['description'] ?? '');
$date = $input['date'] ?? date('Y-m-d');

$valid_types = ['deposit', 'withdrawal', 'dividend', 'jcp', 'rent', 'buy', 'sell'];
if (!in_array($type, $valid_types) || $amount <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Dados inválidos. Verifique o tipo e o valor.']);
    exit;
}

// Authorization header: Use user's access token if available, otherwise use public key
$authHeader = isset($_SESSION['access_token']) ? 'Authorization: Bearer ' . $_SESSION['access_token'] : 'Authorization: Bearer ' . $supabaseKey;

// Early diagnostic - remove after debugging
if (empty($supabaseUrl)) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Configuração interna inválida: supabaseUrl não encontrado.',
        'debug' => [
            'supabaseUrl_empty' => empty($supabaseUrl),
            'supabaseKey_empty' => empty($supabaseKey),
            'env_url' => getenv('SUPABASE_URL'),
            'user_id' => $user_id,
            'session_keys' => array_keys($_SESSION)
        ]
    ]);
    exit;
}

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
$curlErr = curl_error($ch);
$httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$portfolioData = json_decode($portfolioRes, true);
$portfolioId = $portfolioData[0]['id'] ?? null;

if (!$portfolioId) {
    http_response_code(404);
    echo json_encode([
        'error' => 'Portfólio não encontrado para este usuário.',
        'debug' => [
            'user_id' => $user_id,
            'url' => $portfolioUrl,
            'http_status' => $httpStatus,
            'response' => $portfolioRes,
            'curl_error' => $curlErr
        ]
    ]);
    curl_close($ch);
    exit;
}

// 2. Fetch all cash transactions to calculate current balance
$txUrl = $supabaseUrl . '/rest/v1/cash_transactions?portfolio_id=eq.' . $portfolioId . '&select=type,amount';
curl_setopt($ch, CURLOPT_URL, $txUrl);
$txRes = curl_exec($ch);
$txData = json_decode($txRes, true) ?? [];

$currentBalance = 0;
foreach ($txData as $tx) {
    $txType = $tx['type'];
    $txAmount = floatval($tx['amount']);
    
    if (in_array($txType, ['deposit', 'sell', 'dividend', 'jcp', 'rent'])) {
        $currentBalance += $txAmount;
    } elseif (in_array($txType, ['withdrawal', 'buy'])) {
        $currentBalance -= $txAmount;
    }
}

// 3. If withdrawal, check if user has enough balance
if ($type === 'withdrawal' && $amount > $currentBalance) {
    http_response_code(400);
    echo json_encode(['error' => 'Saldo insuficiente para a retirada desejada.', 'current_balance' => $currentBalance]);
    exit;
}

// 4. Insert new transaction
$insertUrl = $supabaseUrl . '/rest/v1/cash_transactions';
curl_setopt($ch, CURLOPT_URL, $insertUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'portfolio_id' => $portfolioId,
    'type' => $type,
    'amount' => $amount,
    'description' => $description ?: null,
    'transaction_date' => $date
]));

$finalRes = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode >= 200 && $httpCode < 300) {
    echo json_encode(['success' => true, 'message' => 'Movimentação registrada com sucesso!', 'new_balance' => ($type === 'withdrawal' ? $currentBalance - $amount : $currentBalance + $amount)]);
} else {
    http_response_code($httpCode);
    echo json_encode(['error' => 'Erro ao registrar a movimentação no banco.', 'details' => $finalRes]);
}
?>
