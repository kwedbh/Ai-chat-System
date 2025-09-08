<?php
// backend/get_conversations.php

include 'cors.php';

include 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not authenticated.']);
    exit;
}

header("Content-Type: application/json");

$user_id = $_SESSION['user_id'];

try {
    $stmt = $conn->prepare("SELECT id, title FROM chat_sessions WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'conversations' => $conversations]);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch conversations.']);
}
?>