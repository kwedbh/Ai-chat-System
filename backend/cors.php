<?php
// backend/cors.php
// Allow multiple origins for development
$allowed_origins = [
    'http://localhost:5173',
    'http://localhost:3000',
    'http://127.0.0.1:5173',
    'http://127.0.0.1:3000'
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
} else {
    // Fallback - log this for debugging
    error_log("CORS: Origin not allowed: " . $origin);
    header("Access-Control-Allow-Origin: http://localhost:5173");
}

header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Debug CORS
error_log("CORS Debug - Origin: " . ($_SERVER['HTTP_ORIGIN'] ?? 'none') . ", User-Agent: " . ($_SERVER['HTTP_USER_AGENT'] ?? 'none'));
