<?php
// backend/clear_session.php

include 'cors.php';

include 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not authenticated.']);
    exit;
}

if (!isset($_GET['sessionId'])) {
    echo json_encode(['success' => false, 'message' => 'Session ID is required.']);
    exit;
}

header("Content-Type: application/json");

$user_id = $_SESSION['user_id'];
$session_id = $_GET['sessionId'];

try {
    $stmt = $conn->prepare("DELETE FROM chat_sessions WHERE id = ? AND user_id = ?");
    $stmt->execute([$session_id, $user_id]);

    echo json_encode(['success' => true, 'message' => 'Conversation deleted.']);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to delete conversation.']);
}
?>