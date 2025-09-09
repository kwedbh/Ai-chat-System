<?php
// backend/frontend_test.php
include 'session.php';
include 'cors.php';

header("Content-Type: application/json");

// Set a test session if none exists
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
    $_SESSION['username'] = 'testuser';
}

echo json_encode([
    'message' => 'Frontend session test',
    'session_id' => session_id(),
    'session_data' => $_SESSION,
    'origin' => $_SERVER['HTTP_ORIGIN'] ?? 'none',
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'none',
    'cookies_received' => $_COOKIE,
    'headers' => getallheaders()
]);
?>