<?php

/**
 * IAMS - Industrial Attachment Management System
 * Main Entry Point
 */

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define base URL
define('BASE_URL', 'http://localhost:8000/');

// Include database configuration
require_once '../config/database.php';

// Include core files
require_once '../core/Router.php';
require_once '../core/Controller.php';
require_once '../core/Model.php';
require_once '../core/Auth.php';

// Include models
require_once '../app/models/Student.php';
require_once '../app/models/Organization.php';
require_once '../app/models/Placement.php';
require_once '../app/models/Logbook.php';
require_once '../app/models/Report.php';
require_once '../app/models/Evaluation.php';
require_once '../app/models/Notification.php';
require_once '../app/models/Application.php';

// Include controllers
require_once '../app/controllers/AuthController.php';
require_once '../app/controllers/StudentController.php';
require_once '../app/controllers/OrganizationController.php';
require_once '../app/controllers/CoordinatorController.php';
require_once '../app/controllers/SupervisorController.php';

// Get URL from query string
$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);

// Create router
$router = new Router();

// Define routes
$router->add('', ['controller' => 'auth', 'action' => 'login']);
$router->add('auth/login', ['controller' => 'auth', 'action' => 'login']);
$router->add('auth/register-student', ['controller' => 'auth', 'action' => 'registerStudent']);
$router->add('auth/register-organization', ['controller' => 'auth', 'action' => 'registerOrganization']);
$router->add('auth/authenticate', ['controller' => 'auth', 'action' => 'authenticate']);
$router->add('auth/logout', ['controller' => 'auth', 'action' => 'logout']);
$router->add('auth/change-password', ['controller' => 'auth', 'action' => 'changePassword']);
$router->add('auth/update-password', ['controller' => 'auth', 'action' => 'updatePassword']);
$router->add('auth/forgot-password', ['controller' => 'auth', 'action' => 'forgotPassword']);

// Student routes
$router->add('student/dashboard', ['controller' => 'student', 'action' => 'dashboard']);
$router->add('student/profile', ['controller' => 'student', 'action' => 'profile']);
$router->add('student/edit-profile', ['controller' => 'student', 'action' => 'editProfile']);
$router->add('student/organizations', ['controller' => 'student', 'action' => 'organizations']);
$router->add('student/apply', ['controller' => 'student', 'action' => 'apply']);
$router->add('student/applications', ['controller' => 'student', 'action' => 'applications']);
$router->add('student/logbooks', ['controller' => 'student', 'action' => 'logbooks']);
$router->add('student/create-logbook', ['controller' => 'student', 'action' => 'createLogbook']);
$router->add('student/reports', ['controller' => 'student', 'action' => 'reports']);
$router->add('student/upload-report', ['controller' => 'student', 'action' => 'uploadReport']);
$router->add('student/notifications', ['controller' => 'student', 'action' => 'notifications']);
$router->add('student/mark-notification-read', ['controller' => 'student', 'action' => 'markNotificationRead']);

// Organization routes
$router->add('organization/dashboard', ['controller' => 'organization', 'action' => 'dashboard']);
$router->add('organization/profile', ['controller' => 'organization', 'action' => 'profile']);
$router->add('organization/edit-profile', ['controller' => 'organization', 'action' => 'editProfile']);
$router->add('organization/applications', ['controller' => 'organization', 'action' => 'applications']);
$router->add('organization/process-application', ['controller' => 'organization', 'action' => 'processApplication']);
$router->add('organization/students', ['controller' => 'organization', 'action' => 'students']);
$router->add('organization/notifications', ['controller' => 'organization', 'action' => 'notifications']);
$router->add('organization/mark-notification-read', ['controller' => 'organization', 'action' => 'markNotificationRead']);

// Coordinator routes
$router->add('coordinator/dashboard', ['controller' => 'coordinator', 'action' => 'dashboard']);
$router->add('coordinator/students', ['controller' => 'coordinator', 'action' => 'students']);
$router->add('coordinator/view-student', ['controller' => 'coordinator', 'action' => 'viewStudent']);
$router->add('coordinator/organizations', ['controller' => 'coordinator', 'action' => 'organizations']);
$router->add('coordinator/process-organization', ['controller' => 'coordinator', 'action' => 'processOrganization']);
$router->add('coordinator/placements', ['controller' => 'coordinator', 'action' => 'placements']);
$router->add('coordinator/create-placement', ['controller' => 'coordinator', 'action' => 'createPlacement']);
$router->add('coordinator/logbooks', ['controller' => 'coordinator', 'action' => 'logbooks']);
$router->add('coordinator/pendingReports', ['controller' => 'coordinator', 'action' => 'pendingReports']);
$router->add('coordinator/evaluations', ['controller' => 'coordinator', 'action' => 'evaluations']);
$router->add('coordinator/analytics', ['controller' => 'coordinator', 'action' => 'analytics']);
$router->add('coordinator/notifications', ['controller' => 'coordinator', 'action' => 'notifications']);
$router->add('coordinator/mark-notification-read', ['controller' => 'coordinator', 'action' => 'markNotificationRead']);

// Supervisor routes
$router->add('supervisor/dashboard', ['controller' => 'supervisor', 'action' => 'dashboard']);
$router->add('supervisor/students', ['controller' => 'supervisor', 'action' => 'students']);
$router->add('supervisor/view-student', ['controller' => 'supervisor', 'action' => 'viewStudent']);
$router->add('supervisor/logbooks', ['controller' => 'supervisor', 'action' => 'logbooks']);
$router->add('supervisor/view-logbook', ['controller' => 'supervisor', 'action' => 'viewLogbook']);
$router->add('supervisor/review-logbook', ['controller' => 'supervisor', 'action' => 'reviewLogbook']);
$router->add('supervisor/reports', ['controller' => 'supervisor', 'action' => 'reports']);
$router->add('supervisor/view-report', ['controller' => 'supervisor', 'action' => 'viewReport']);
$router->add('supervisor/review-report', ['controller' => 'supervisor', 'action' => 'reviewReport']);
$router->add('supervisor/evaluate', ['controller' => 'supervisor', 'action' => 'evaluate']);
$router->add('supervisor/profile', ['controller' => 'supervisor', 'action' => 'profile']);
$router->add('supervisor/edit-profile', ['controller' => 'supervisor', 'action' => 'editProfile']);
$router->add('supervisor/notifications', ['controller' => 'supervisor', 'action' => 'notifications']);
$router->add('supervisor/mark-notification-read', ['controller' => 'supervisor', 'action' => 'markNotificationRead']);

// Dispatch the route
$router->dispatch($url);
