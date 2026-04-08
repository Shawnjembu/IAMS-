<?php

/**
 * Standalone Login Page
 * Can be accessed directly at http://localhost/iams/public/login.php
 */

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define base URL
define('BASE_URL', 'http://localhost/iams/public/');

// If already logged in, redirect to dashboard
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $role = $_SESSION['role'] ?? 'student';
    header("Location: " . BASE_URL . "index.php?url={$role}/dashboard");
    exit;
}

// Include database configuration
require_once '../config/database.php';

$error = '';
$success = '';

// Process login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Email and password are required';
    } else {
        // Try to login
        $db = connectToDatabase();
        
        // Check in students table
        $sql = "SELECT * FROM students WHERE email = ?";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $loggedIn = false;
        $role = '';
        
        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['name'] = $user['full_name'];
                $_SESSION['role'] = 'student';
                $_SESSION['logged_in'] = true;
                $loggedIn = true;
            }
        }
        
        // Check in organizations table
        if (!$loggedIn) {
            $sql = "SELECT * FROM organizations WHERE email = ?";
            $stmt = mysqli_prepare($db, $sql);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['name'] = $user['organization_name'];
                    $_SESSION['role'] = 'organization';
                    $_SESSION['logged_in'] = true;
                    $loggedIn = true;
                }
            }
        }
        
        // Check in coordinators table
        if (!$loggedIn) {
            $sql = "SELECT * FROM coordinators WHERE email = ?";
            $stmt = mysqli_prepare($db, $sql);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['name'] = $user['full_name'];
                    $_SESSION['role'] = 'coordinator';
                    $_SESSION['logged_in'] = true;
                    $loggedIn = true;
                }
            }
        }
        
        // Check in supervisors table
        if (!$loggedIn) {
            $sql = "SELECT * FROM supervisors WHERE email = ?";
            $stmt = mysqli_prepare($db, $sql);
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if (mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['name'] = $user['full_name'];
                    $_SESSION['role'] = 'supervisor';
                    $_SESSION['logged_in'] = true;
                    $loggedIn = true;
                }
            }
        }
        
        if ($loggedIn) {
            $role = $_SESSION['role'];
            header("Location: " . BASE_URL . "index.php?url={$role}/dashboard");
            exit;
        } else {
            $error = 'Invalid email or password';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - IAMS</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
        }
        
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .login-body {
            padding: 40px;
        }
        
        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #ddd;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
        }
        
        .btn-login:hover {
            opacity: 0.9;
        }
        
        .register-link {
            color: #667eea;
            text-decoration: none;
        }
        
        .register-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="login-card">
                    <div class="login-header">
                        <h3><i class="fas fa-graduation-cap"></i> IAMS</h3>
                        <p class="mb-0">Industrial Attachment Management System</p>
                    </div>
                    
                    <div class="login-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php echo $success; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           placeholder="Enter your email" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" 
                                           placeholder="Enter your password" required>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-login w-100">
                                <i class="fas fa-sign-in-alt me-2"></i> Login
                            </button>
                        </form>
                        
                        <div class="text-center mt-4">
                            <p class="text-muted small">Demo Accounts:</p>
                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                <span class="badge bg-secondary">admin@example.com</span>
                                <span class="badge bg-secondary">student@example.com</span>
                                <span class="badge bg-secondary">supervisor@example.com</span>
                            </div>
                            <p class="text-muted small mt-2">Password: password123</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
