<?php
header('Content-Type: application/json');
require_once __DIR__ . '/session_handler.php';
require_once __DIR__ . '/env.php';

// Protection
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$id = $input['id'] ?? null;

// Map JS names to DB names if needed
$data = [
    'titulo' => $input['titulo'],
    'categoria' => $input['categoria'],
    'tempo_leitura' => $input['tempoLeitura'],
    'imagem_url' => $input['imagemUrl'],
    'descricao' => $input['descricao'],
    'conteudo' => $input['conteudo'],
    'autor' => $input['autor'],
    'cargo_autor' => $input['cargoAutor'],
    'avatar_autor' => $input['avatarAutor'],
    'data_publicacao' => $input['dataPublicacao'],
    'destaque' => $input['destaque'] ?? false
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'apikey: ' . $supabaseKey,
    'Authorization: Bearer ' . $supabaseKey,
    'Content-Type: application/json',
    'Prefer: return=representation'
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

if ($id) {
    // Update
    curl_setopt($ch, CURLOPT_URL, $supabaseUrl . '/rest/v1/articles?id=eq.' . $id);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
} else {
    // Insert
    curl_setopt($ch, CURLOPT_URL, $supabaseUrl . '/rest/v1/articles');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
}

$res = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode >= 200 && $httpCode < 300) {
    $savedArticle = json_decode($res, true)[0] ?? null;

    // NEW ARTICLE: Trigger Newsletter
    if (!$id && $savedArticle) {
        require_once __DIR__ . '/newsletter_notify.php';
        $newsletterTriggered = notifySubscribers($savedArticle);
    }

    echo json_encode([
        'success' => true,
        'data' => $savedArticle,
        'newsletter' => $newsletterTriggered ?? 'n/a'
    ]);
} else {
    http_response_code($httpCode);
    echo json_encode(['error' => 'Erro ao salvar artigo', 'details' => json_decode($res)]);
}
?>