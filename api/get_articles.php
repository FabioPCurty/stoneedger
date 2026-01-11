<?php
header('Content-Type: application/json');
require_once __DIR__ . '/env.php';

$id = $_GET['id'] ?? null;
$category = $_GET['category'] ?? null;
$limit = $_GET['limit'] ?? 100;

$url = $supabaseUrl . '/rest/v1/articles?select=*';

if ($id) {
    $url .= '&id=eq.' . $id;
}
if ($category) {
    $url .= '&categoria=eq.' . urlencode($category);
}

$url .= '&order=data_publicacao.desc&limit=' . $limit;

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'apikey: ' . $supabaseKey,
    'Authorization: Bearer ' . $supabaseKey
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_URL, $url);

$res = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode >= 200 && $httpCode < 300) {
    echo $res;
} else {
    http_response_code($httpCode);
    echo json_encode(['error' => 'Erro ao buscar artigos', 'details' => json_decode($res)]);
}
?>