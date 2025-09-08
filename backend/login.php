<?php
// backend/login.php
include 'session.php'; // This will include debug.php too
include 'cors.php';
include 'db.php';

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

error_log("=== LOGIN DEBUG ===");
error_log("Raw input: " . file_get_contents("php://input"));
error_log("Decoded data: " . print_r($data, true));
error_log("Session ID before login: " . session_id());

if (!isset($data['email']) || !isset($data['password'])) {
    echo json_encode(['success' => false, 'message' => 'Email and password are required.']);
    exit;
}

$email = $data['email'];
$password = $data['password'];

try {
    $stmt = $conn->prepare("SELECT id, username, email, password, plan FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    error_log("User found: " . ($user ? 'YES' : 'NO'));

    if ($user && password_verify($password, $user['password'])) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['plan'] = $user['plan'];

        // Debug: Log session creation
        error_log("Login successful - Session ID: " . session_id() . ", User ID: " . $user['id']);
        error_log("Session after login: " . print_r($_SESSION, true));
        
        echo json_encode([
            'success' => true,
            'message' => 'Login successful.',
            'user' => [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'plan' => $user['plan']
            ]
        ]);
    } else {
        error_log("Login failed - invalid credentials");
        echo json_encode(['success' => false, 'message' => 'Invalid credentials.']);
    }
} catch (PDOException $e) {
    error_log("Login error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error.']);
}
?>