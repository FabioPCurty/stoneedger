<?php
// Only load env.php if it exists (Local Development)
if (file_exists(__DIR__ . '/env.php')) {
    require_once __DIR__ . '/env.php';
}

class SupabaseSessionHandler implements SessionHandlerInterface
{
    private $url;
    private $key;

    public function __construct($url, $key)
    {
        // Allow env vars to be set via Vercel (getenv) if global var is missing
        $finalUrl = $url ?: getenv('SUPABASE_URL');
        $finalKey = $key ?: getenv('SUPABASE_KEY');

        $this->url = rtrim($finalUrl, '/') . '/rest/v1/php_sessions';
        $this->key = $finalKey;
    }

    public function open($savePath, $sessionName): bool
    {
        return true;
    }

    public function close(): bool
    {
        return true;
    }

    public function read($id): string
    {
        $ch = curl_init($this->url . '?id=eq.' . $id . '&select=data');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'apikey: ' . $this->key,
            'Authorization: Bearer ' . $this->key
        ]);
        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($statusCode === 200) {
            $data = json_decode($response, true);
            return isset($data[0]['data']) ? $data[0]['data'] : '';
        }
        return '';
    }

    public function write($id, $data): bool
    {
        $payload = json_encode([
            'id' => $id,
            'data' => $data,
            'updated_at' => date('c')
        ]);

        $ch = curl_init($this->url . '?id=eq.' . $id);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'apikey: ' . $this->key,
            'Authorization: Bearer ' . $this->key,
            'Content-Type: application/json',
            'Prefer: resolution=merge-duplicates'
        ]);
        curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ($statusCode >= 200 && $statusCode < 300);
    }

    public function destroy($id): bool
    {
        $ch = curl_init($this->url . '?id=eq.' . $id);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'apikey: ' . $this->key,
            'Authorization: Bearer ' . $this->key
        ]);
        $exec = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ($statusCode >= 200 && $statusCode < 300);
    }

    public function gc($maxlifetime): int|false
    {
        // Simple GC
        return 0;
    }
}

// Instantiate Handler
// Prioritize Environment Variables (Vercel) over Global Variables (env.php)
$envUrl = getenv('SUPABASE_URL');
$envKey = getenv('SUPABASE_KEY');

// If env vars are empty, fallback to variables potentially defined in env.php
// Note: $supabaseUrl and $supabaseKey come from env.php usually
if (empty($envUrl) && isset($supabaseUrl)) {
    $envUrl = $supabaseUrl;
}
if (empty($envKey) && isset($supabaseKey)) {
    $envKey = $supabaseKey;
}

// Register if we have credentials
if (!empty($envUrl) && !empty($envKey)) {
    $handler = new SupabaseSessionHandler($envUrl, $envKey);
    session_set_save_handler($handler, true);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>