<?php
// backend/manual_test.php
include 'session.php';
include 'cors.php';

header("Content-Type: application/json");

// Manually set session data to test
$_SESSION['user_id'] = 1;
$_SESSION['username'] = 'testuser';

echo json_encode([
    'message' => 'Session manually set',
    'session_id' => session_id(),
    'session_data' => $_SESSION,
    'cookie_params' => session_get_cookie_params()
]);