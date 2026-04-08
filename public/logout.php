<?php

/**
 * Logout Script
 * Destroys session and redirects to login page
 */

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define base URL
define('BASE_URL', 'http://localhost/iams/public/');

// Destroy session
session_unset();
session_destroy();

// Redirect to login
header("Location: " . BASE_URL . "login.php");
exit;
