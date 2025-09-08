<?php
// backend/get_conversations.php
include 'session.php'; // This will include debug.php too
include 'cors.php';
include 'db.php';

// Debug information
error_log("=== GET_CONVERSATIONS DEBUG ===");
error_log("Session ID: " . session_id());
error_log("Session data: " . print_r($_SESSION, true));
error_log("User ID isset: " . (isset($_SESSION['user_id']) ? 'YES' : 'NO'));
error_log("User ID value: " . ($_SESSION['user_id'] ?? 'NOT SET'));
error_log("All cookies: " . print_r($_COOKIE, true));

if (!isset($_SESSION['user_id'])) {
    error_log("Authentication failed - no user_id in session");
    http_response_code(401);
    echo json_encode([
        'success' => false, 
        'message' => 'Not authenticated.',
        'debug' => [
            'session_id' => session_id(),
            'session_data' => $_SESSION,
            'cookies' => $_COOKIE
        ]
    ]);
    exit;
}

header("Content-Type: application/json");

$user_id = $_SESSION['user_id'];
error_log("Authenticated user ID: " . $user_id);

try {
    $stmt = $conn->prepare("SELECT id, title FROM chat_sessions WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    error_log("Found " . count($conversations) . " conversations");
    echo json_encode(['success' => true, 'conversations' => $conversations]);
} catch(PDOException $e) {
    error_log("Database error in get_conversations.php: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Failed to fetch conversations.']);
}
?>