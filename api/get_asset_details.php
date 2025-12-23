<?php
header('Content-Type: application/json');

// Load environment variables from .env file
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && substr($line, 0, 1) !== '#') {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
        }
    }
}

$supabaseUrl = $_ENV['SUPABASE_URL'] ?? '';
$supabaseKey = $_ENV['SUPABASE_KEY'] ?? '';

if (empty($supabaseUrl) || empty($supabaseKey)) {
    http_response_code(500);
    echo json_encode(['error' => 'Supabase credentials not configured']);
    exit;
}

$ticker = $_GET['ticker'] ?? '';

if (empty($ticker)) {
    http_response_code(400);
    echo json_encode(['error' => 'Ticker parameter is required']);
    exit;
}

// Prepare Supabase API URL
// Table name is 'stock_fundamentals' and we filter by 'papel' column (ticker)
$url = $supabaseUrl . '/rest/v1/stock_fundamentals?papel=eq.' . urlencode($ticker) . '&select=*';

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'apikey: ' . $supabaseKey,
    'Authorization: Bearer ' . $supabaseKey,
    'Content-Type: application/json'
]);

// Disable SSL verification for local XAMPP development
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    $error = curl_error($ch);
    file_put_contents(__DIR__ . '/api_debug.log', date('Y-m-d H:i:s') . " - Curl Error: " . $error . "\n", FILE_APPEND);
    http_response_code(500);
    echo json_encode(['error' => 'Curl error: ' . $error]);
    curl_close($ch);
    exit;
}
curl_close($ch);

$data = json_decode($response, true);
$needsUpdate = false;

// Determine if we need to update
if (empty($data) || $httpCode !== 200) {
    $needsUpdate = true;
} else {
    $asset = $data[0] ?? null;
    if ($asset) {
        $updatedAt = $asset['updated_at'] ?? null;
        if ($updatedAt) {
            try {
                $lastUpdate = new DateTime($updatedAt);
                $now = new DateTime();
                $diff = $now->getTimestamp() - $lastUpdate->getTimestamp();
                if ($diff > 86400) { // 24 hours
                    $needsUpdate = true;
                }
            } catch (Exception $e) {
                $needsUpdate = true;
            }
        } else {
            $needsUpdate = true;
        }
    } else {
        $needsUpdate = true;
    }
}

// Trigger Python update if needed
if ($needsUpdate) {
    // Run python script for this specific ticker
    $pythonExe = 'C:\\Users\\fabio\\AppData\\Local\\Programs\\Python\\Python311\\python.exe';
    $tickerEscaped = escapeshellarg($ticker);
    $scriptPath = __DIR__ . '/../atualizar_supabase.py';
    $siteRoot = dirname(__DIR__);

    // Using absolute paths and changing directory to site root
    $command = "cd /d " . escapeshellarg($siteRoot) . " && " . escapeshellarg($pythonExe) . " " . escapeshellarg($scriptPath) . " --ticker $tickerEscaped 2>&1";
    $output = shell_exec($command);

    // Log the update attempt
    file_put_contents(__DIR__ . '/api_debug.log', date('Y-m-d H:i:s') . " - JIT Update for $ticker - Output: " . $output . "\n", FILE_APPEND);

    // Fetch again from Supabase after update
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'apikey: ' . $supabaseKey,
        'Authorization: Bearer ' . $supabaseKey,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
}

if ($httpCode !== 200 && $httpCode !== 201) {
    file_put_contents(__DIR__ . '/api_debug.log', date('Y-m-d H:i:s') . " - API Error: " . $httpCode . " - Response: " . $response . "\n", FILE_APPEND);
}

http_response_code($httpCode);
echo $response;
?>