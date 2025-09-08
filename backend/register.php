<?php

include 'cors.php';

include 'db.php';
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->username) || !isset($data->email) || !isset($data->password)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

$username = $data->username;
$email = $data->email;
$password = password_hash($data->password, PASSWORD_DEFAULT);

try {
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $password]);
    echo json_encode(['success' => true, 'message' => 'Registration successful!']);
} catch(PDOException $e) {
    if ($e->getCode() == 23000) { // Integrity constraint violation
        echo json_encode(['success' => false, 'message' => 'Username or email already exists.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Registration failed.']);
    }
}
?>