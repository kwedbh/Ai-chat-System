<?php

include 'session.php'; 
include 'cors.php';

if (isset($_SESSION['user_id'])) {
    echo json_encode([
        'isAuthenticated' => true,
        'user' => [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username']
        ]
    ]);
} else {
    echo json_encode(['isAuthenticated' => false]);
}