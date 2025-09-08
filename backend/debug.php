<?php
// backend/debug.php - Include this at the top of files for debugging
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Also log errors to a file
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/php_errors.log');
