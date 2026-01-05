<?php
require_once __DIR__ . '/env.php';

class SupabaseSessionHandler implements SessionHandlerInterface
{
    private $url;
    private $key;

    public function __construct($url, $key)
    {
        $this->url = rtrim($url, '/') . '/rest/v1/php_sessions';
        $this->key = $key;
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
        curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ($statusCode >= 200 && $statusCode < 300);
    }

    public function gc($maxlifetime): int|false
    {
        // Simple GC could be implemented here to delete old sessions
        return 0;
    }
}

// Register the handler only if we are in Vercel (or if we want to test DB sessions)
if (getenv('VERCEL') == '1' || true) { // Force for now to test
    $handler = new SupabaseSessionHandler($supabaseUrl, $supabaseKey);
    session_set_save_handler($handler, true);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>