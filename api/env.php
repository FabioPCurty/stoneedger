<?php
// Load environment variables from .env file (for local development)
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && substr($line, 0, 1) !== '#') {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            if (!isset($_ENV[$key])) {
                $_ENV[$key] = $value;
                putenv("$key=$value");
            }
        }
    }
}

function get_env_var($key, $default = '')
{
    return getenv($key) ?: ($_ENV[$key] ?? $default);
}

$supabaseUrl = get_env_var('SUPABASE_URL');
$supabaseKey = get_env_var('SUPABASE_KEY');

if (empty($supabaseUrl) || empty($supabaseKey)) {
    http_response_code(500);
    echo json_encode(['error' => 'Supabase credentials not configured or environment not set']);
    exit;
}
?>