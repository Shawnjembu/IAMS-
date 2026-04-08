<?php

/**
 * Helper Functions
 * Utility functions used throughout the application
 */

// Define base URL if not already defined
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/IAMS/public/');
}

/**
 * Sanitize input data
 * 
 * @param mixed $data Data to sanitize
 * @return mixed Sanitized data
 */
function sanitize($data)
{
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Redirect to a URL
 * 
 * @param string $url URL to redirect to
 */
function redirect($url)
{
    header("Location: " . BASE_URL . $url);
    exit();
}

/**
 * Get current URL
 * 
 * @return string Current URL
 */
function currentUrl()
{
    return $_SERVER['REQUEST_URI'];
}

/**
 * Display flash message
 * 
 * @param string $message Message to display
 * @param string $type Message type (success, error, warning, info)
 */
function flash($message, $type = 'info')
{
    $_SESSION['flash'] = [
        'message' => $message,
        'type' => $type
    ];
}

/**
 * Get and display flash message
 * 
 * @return string HTML for flash message
 */
function getFlash()
{
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return "<div class='alert alert-{$flash['type']}'>{$flash['message']}</div>";
    }
    return '';
}

/**
 * Check if request is POST
 * 
 * @return bool True if POST request
 */
function isPost()
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/**
 * Check if request is GET
 * 
 * @return bool True if GET request
 */
function isGet()
{
    return $_SERVER['REQUEST_METHOD'] === 'GET';
}

/**
 * Get input value
 * 
 * @param string $key Input key
 * @param mixed $default Default value if not found
 * @return mixed Input value
 */
function input($key, $default = '')
{
    if (isPost()) {
        return $_POST[$key] ?? $default;
    }
    return $_GET[$key] ?? $default;
}

/**
 * Generate CSRF token
 * 
 * @return string CSRF token
 */
function csrf_token()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 * 
 * @param string $token Token to verify
 * @return bool True if valid
 */
function verify_csrf($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Format date
 * 
 * @param string $date Date string
 * @param string $format Output format
 * @return string Formatted date
 */
function formatDate($date, $format = 'Y-m-d')
{
    return date($format, strtotime($date));
}

/**
 * Format datetime
 * 
 * @param string $datetime Datetime string
 * @param string $format Output format
 * @return string Formatted datetime
 */
function formatDateTime($datetime, $format = 'Y-m-d H:i:s')
{
    return date($format, strtotime($datetime));
}

/**
 * Get user IP address
 * 
 * @return string IP address
 */
function getIP()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    return $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
}

/**
 * Check if user is logged in
 * 
 * @return bool True if logged in
 */
function isLoggedIn()
{
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

/**
 * Get user role
 * 
 * @return string|null User role
 */
function userRole()
{
    return $_SESSION['role'] ?? null;
}

/**
 * Require authentication
 * Redirect to login if not authenticated
 */
function requireAuth()
{
    if (!isLoggedIn()) {
        redirect('auth/login');
    }
}

/**
 * Require guest
 * Redirect to dashboard if already logged in
 */
function requireGuest()
{
    if (isLoggedIn()) {
        redirect('dashboard');
    }
}
