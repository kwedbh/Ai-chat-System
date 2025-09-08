<?php
// backend/logout.php
include 'cors.php';

session_unset();
session_destroy();
echo json_encode(['success' => true]);
