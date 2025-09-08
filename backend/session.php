<?php
// backend/session.php
include_once 'debug.php'; // Enable error reporting

// Configure session before starting
ini_set('session.cookie_lifetime', 3600); // 1 hour
ini_set('session.cookie_path', '/');
ini_set('session.cookie_domain', ''); // Empty for localhost
ini_set('session.cookie_secure', 0); // 0 for HTTP (development)
ini_set('session.cookie_httponly', 1); // Prevent XSS
ini_set('session.cookie_samesite', 'Lax'); // Allow cross-origin

if (session_status() === PHP_SESSION_NONE) {
    session_start();
    error_log("Session started - ID: " . session_id() . ", Cookie params: " . print_r(session_get_cookie_params(), true));
} else {
    error_log("Session already active - ID: " . session_id());
}
?>