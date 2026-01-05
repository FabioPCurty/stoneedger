<?php
header('Content-Type: application/json');

require_once __DIR__ . '/session_handler.php';

// Prevent advertisements or notices from breaking JSON output
error_reporting(0);
ini_set('display_errors', 0);

// Tickers update logic (JIT)
// Note: Vercel does not allow local Python execution in the same instance easily.
$isLocal = in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']);

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Não autorizado']);
    exit;
}

$ticker = trim($_GET['ticker'] ?? '');

if (empty($ticker)) {
    http_response_code(400);
    echo json_encode(['error' => 'Ticker parameter is required']);
    exit;
}

// Prepare Supabase API URL - Using 'eq' to match the dashboard's successful logic
$url = $supabaseUrl . '/rest/v1/stock_fundamentals?papel=eq.' . urlencode($ticker) . '&select=*';

function fetchFromSupabase($url, $key)
{
    // Authorization header: Use user's access token if available, otherwise use public key
    $authHeader = isset($_SESSION['access_token']) ? 'Authorization: Bearer ' . $_SESSION['access_token'] : 'Authorization: Bearer ' . $key;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'apikey: ' . $key,
        $authHeader,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_errno($ch) ? curl_error($ch) : null;
    curl_close($ch);
    return ['response' => $response, 'httpCode' => $httpCode, 'error' => $error];
}

// First fetch attempt
$fetchResult = fetchFromSupabase($url, $supabaseKey);
$response = $fetchResult['response'];
$httpCode = $fetchResult['httpCode'];

if ($fetchResult['error']) {
    error_log("Supabase Fetch Error: " . $fetchResult['error']);
    http_response_code(500);
    echo json_encode(['error' => 'Curl error: ' . $fetchResult['error']]);
    exit;
}

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

// Trigger Python update if needed (ONLY on local or if explicitly enabled)
if ($needsUpdate && $isLocal) {
    // Detect Python executable
    $pythonExe = $_ENV['PYTHON_EXE'] ?? 'python'; // Try from env first

    // Check if on Windows for default path fallback if 'python' fails
    $isWindows = strncasecmp(PHP_OS, 'WIN', 3) === 0;
    if ($isWindows && !file_exists($pythonExe) && $pythonExe === 'python') {
        // Fallback to the known local path if provided in code previously, 
        // but prefer searching in PATH
        $localPath = 'C:\\Users\\fabio\\AppData\\Local\\Programs\\Python\\Python311\\python.exe';
        if (file_exists($localPath)) {
            $pythonExe = $localPath;
        }
    }

    $tickerEscaped = escapeshellarg($ticker);
    $scriptPath = __DIR__ . '/../atualizar_supabase.py';
    $siteRoot = dirname(__DIR__);

    if (file_exists($scriptPath)) {
        // Using absolute paths and changing directory to site root
        $cmdCd = $isWindows ? "cd /d " : "cd ";
        $command = $cmdCd . escapeshellarg($siteRoot) . " && " . escapeshellarg($pythonExe) . " " . escapeshellarg($scriptPath) . " --ticker $tickerEscaped 2>&1";

        $output = shell_exec($command);

        // Log the update attempt if error occurs
        if (!$output)
            error_log("JIT Update for $ticker failed or returned no output.");

        // Fetch again from Supabase after update to get fresh data
        $finalFetch = fetchFromSupabase($url, $supabaseKey);

        // Only override original response if update was successful OR if we had no data before
        if ($finalFetch['httpCode'] === 200 && !empty(json_decode($finalFetch['response'], true))) {
            $response = $finalFetch['response'];
            $httpCode = $finalFetch['httpCode'];
        } elseif (empty($data)) {
            // If we had no data and update failed, return the error response
            $response = $finalFetch['response'];
            $httpCode = $finalFetch['httpCode'];
        }
    }
}
// JIT update skipping is normal in serverless

if ($httpCode !== 200 && $httpCode !== 201) {
    error_log("Supabase Final Error: Status $httpCode - Response: $response");
}

http_response_code($httpCode);
echo $response;
?>