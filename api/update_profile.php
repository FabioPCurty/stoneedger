<?php
header('Content-Type: application/json');
require_once __DIR__ . '/session_handler.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Only allow if user is already logged in
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        exit;
    }

    $profile = $_POST['investor_profile'] ?? '';

    // Basic validation
    $valid_profiles = ['Conservador', 'Moderado', 'Arrojado'];
    if (in_array($profile, $valid_profiles)) {
        $_SESSION['investor_profile'] = $profile;
        $_SESSION['profile_updated_at'] = date('c'); // Store current server time ISO 8601

        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'Profile updated in session', 'profile' => $profile]);
        exit;
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid profile type']);
        exit;
    }
}

http_response_code(400);
echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
