<?php
header('Content-Type: application/json');
require_once __DIR__ . '/env.php';

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);
$email = $input['email'] ?? '';

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Por favor, insira um e-mail válido.']);
    exit;
}

// 1. Check if already exists or insert
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'apikey: ' . $supabaseKey,
    'Authorization: Bearer ' . $supabaseKey,
    'Content-Type: application/json',
    'Prefer: resolution=ignore-duplicates' // Handle unique constraint gracefully
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_URL, $supabaseUrl . '/rest/v1/newsletter_subscriptions');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['email' => $email]));

$res = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode >= 200 && $httpCode < 300) {
    echo json_encode(['success' => 'Inscrição realizada com sucesso! Você receberá nossas atualizações.']);
} else {
    // Check if it's a conflict (already subscribed)
    $decoded = json_decode($res, true);
    if ($httpCode === 409) {
        echo json_encode(['success' => 'Você já está inscrito em nossa newsletter!']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao realizar inscrição. Tente novamente mais tarde.', 'details' => $decoded]);
    }
}
?>