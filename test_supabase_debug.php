<?php
require 'api/env.php';
$url = $supabaseUrl ?? getenv('SUPABASE_URL');
$key = $supabaseKey ?? getenv('SUPABASE_KEY');
echo "URL: $url\nLength of Key: " . strlen($key) . "\n";
$ch = curl_init($url . '/rest/v1/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['apikey: ' . $key, 'Authorization: Bearer ' . $key]);
$res = curl_exec($ch);
echo 'HTTP Status: ' . curl_getinfo($ch, CURLINFO_HTTP_CODE) . "\n";
echo 'cURL Error: ' . curl_error($ch) . "\n";
echo "Response: $res\n";
?>
