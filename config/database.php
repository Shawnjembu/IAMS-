<?php

/**
 * Database Configuration
 * Contains database connection settings and helper functions
 */

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'iams');

// Database connection function
function connectToDatabase()
{
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    // Set charset to UTF-8
    mysqli_set_charset($conn, "utf8mb4");
    
    return $conn;
}

/**
 * Close database connection
 * 
 * @param mysqli $conn Database connection
 */
function closeDatabase($conn)
{
    if ($conn) {
        mysqli_close($conn);
    }
}

/**
 * Get database connection (singleton pattern)
 * 
 * @return mysqli Database connection
 */
function getDB()
{
    static $conn = null;
    
    if ($conn === null) {
        $conn = connectToDatabase();
    }
    
    return $conn;
}
