<?php
// backend/cors.php
$allowed_origins = [
    'http://localhost:5173',
    'http://localhost:3000',
    'http://127.0.0.1:5173',
    'http://127.0.0.1:3000',
    'https://https://kwed-ai-chat-system.vercel.app/',
];

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

// Debug: Log the origin for troubleshooting
error_log("CORS Debug - Incoming origin: " . $origin);

if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
    error_log("CORS: Allowed origin: " . $origin);
} else {
    error_log("CORS: Origin not in allowed list: " . $origin);
    // For development, be more permissive with localhost
    if (strpos($origin, 'localhost') !== false || 
        strpos($origin, '127.0.0.1') !== false ||
        strpos($origin, 'vercel.app') !== false) {
        header("Access-Control-Allow-Origin: $origin");
        error_log("CORS: Allowed development/vercel origin: " . $origin);
    } else {
        // Fallback
        header("Access-Control-Allow-Origin: http://localhost:5173");
    }
}

header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}
?>