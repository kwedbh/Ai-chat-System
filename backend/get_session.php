<?php

include 'session.php'; 

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
    $stmt = $conn->prepare("SELECT sender, content FROM messages WHERE session_id = ? ORDER BY created_at ASC");
    $stmt->execute([$session_id]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'messages' => $messages]);
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch messages.']);
}
?>