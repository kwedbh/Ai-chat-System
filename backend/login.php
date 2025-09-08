<?php
// backend/login.php
include 'cors.php';

include 'db.php';
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->email) || !isset($data->password)) {
    echo json_encode(['success' => false, 'message' => 'Email and password are required.']);
    exit;
}

$email = $data->email;
$password = $data->password;

try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Log the user in by setting session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['plan'] = $user['plan'];

        echo json_encode(['success' => true, 'message' => 'Login successful!', 'user' => ['id' => $user['id'], 'username' => $user['username']]]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
    }
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Login failed.']);
}
?>