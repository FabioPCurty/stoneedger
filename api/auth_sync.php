<?php
header('Content-Type: application/json');
error_reporting(0); // Prevents warnings from breaking JSON
ini_set('display_errors', 0);
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $access_token = $_POST['access_token'] ?? '';
    $user_id = $_POST['user_id'] ?? '';
    $email = $_POST['email'] ?? '';
    $full_name = $_POST['full_name'] ?? '';

    if (!empty($user_id) && !empty($access_token)) {
        require_once __DIR__ . '/../config.php';

        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = $full_name;
        $_SESSION['access_token'] = $access_token;
        $_SESSION['login_time'] = time();

        // Verificar se é administrador
        if (isset($admin_emails) && in_array($email, $admin_emails)) {
            $_SESSION['is_admin'] = true;
            $_SESSION['admin_logged_in'] = true; // Para compatibilidade com admin.php
        } else {
            $_SESSION['is_admin'] = false;
            $_SESSION['admin_logged_in'] = false;
        }

        http_response_code(200);
        echo json_encode(['status' => 'success', 'is_admin' => $_SESSION['is_admin']]);
        exit;
    }
}

http_response_code(400);
echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
