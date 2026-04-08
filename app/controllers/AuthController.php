<?php

require_once '../core/Controller.php';
require_once '../core/Auth.php';

/**
 * AuthController
 * Handles authentication (login, logout, register)
 */
class AuthController extends Controller
{
    private $auth;

    public function __construct($params = [])
    {
        parent::__construct($params);
        $this->auth = new Auth();
    }

    /**
     * Login page
     */
    public function login()
    {
        // If already logged in, redirect to dashboard
        if ($this->auth->isLoggedIn()) {
            $this->redirect($this->auth->getRole() . '/dashboard');
        }

        $this->view('auth.login');
    }

    /**
     * Process login
     */
    public function authenticate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Validate inputs
            if (empty($email) || empty($password)) {
                $_SESSION['error'] = 'Email and password are required';
                $this->redirect('auth/login');
            }

            // Attempt login
            if ($this->auth->login($email, $password)) {
                // Redirect based on role
                $role = $this->auth->getRole();
                $this->redirect($role . '/dashboard');
            } else {
                $_SESSION['error'] = 'Invalid email or password';
                $this->redirect('auth/login');
            }
        } else {
            $this->redirect('auth/login');
        }
    }

    /**
     * Logout
     */
    public function logout()
    {
        $this->auth->logout();
        session_destroy();
        $this->redirect('auth/login');
    }

    /**
     * Register student page
     */
    public function registerStudent()
    {
        if ($this->auth->isLoggedIn()) {
            $this->redirect($this->auth->getRole() . '/dashboard');
        }

        $this->view('auth.register-student');
    }

    /**
     * Process student registration
     */
    public function createStudent()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'student_number' => $_POST['student_number'] ?? '',
                'full_name' => $_POST['full_name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'program' => $_POST['program'] ?? '',
                'year' => $_POST['year'] ?? '',
                'semester' => $_POST['semester'] ?? '',
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Validate required fields
            if (empty($data['student_number']) || empty($data['full_name']) || 
                empty($data['email']) || empty($data['password'])) {
                $_SESSION['error'] = 'All required fields must be filled';
                $this->redirect('auth/register-student');
            }

            // Check if email already exists
            $student = new Student();
            if ($student->emailExists($data['email'])) {
                $_SESSION['error'] = 'Email already registered';
                $this->redirect('auth/register-student');
            }

            // Register student
            if ($this->auth->registerStudent($data)) {
                $_SESSION['success'] = 'Registration successful! Please login.';
                $this->redirect('auth/login');
            } else {
                $_SESSION['error'] = 'Registration failed. Please try again.';
                $this->redirect('auth/register-student');
            }
        } else {
            $this->redirect('auth/register-student');
        }
    }

    /**
     * Register organization page
     */
    public function registerOrganization()
    {
        if ($this->auth->isLoggedIn()) {
            $this->redirect($this->auth->getRole() . '/dashboard');
        }

        $this->view('auth.register-organization');
    }

    /**
     * Process organization registration
     */
    public function createOrganization()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'organization_name' => $_POST['organization_name'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? '',
                'address' => $_POST['address'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'contact_person' => $_POST['contact_person'] ?? '',
                'industry_type' => $_POST['industry_type'] ?? '',
                'description' => $_POST['description'] ?? '',
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s')
            ];

            // Validate required fields
            if (empty($data['organization_name']) || empty($data['email']) || 
                empty($data['password'])) {
                $_SESSION['error'] = 'All required fields must be filled';
                $this->redirect('auth/register-organization');
            }

            // Check if email already exists
            $organization = new Organization();
            if ($organization->emailExists($data['email'])) {
                $_SESSION['error'] = 'Email already registered';
                $this->redirect('auth/register-organization');
            }

            // Register organization
            if ($this->auth->registerOrganization($data)) {
                $_SESSION['success'] = 'Registration submitted! Your account is pending approval.';
                $this->redirect('auth/login');
            } else {
                $_SESSION['error'] = 'Registration failed. Please try again.';
                $this->redirect('auth/register-organization');
            }
        } else {
            $this->redirect('auth/register-organization');
        }
    }

    /**
     * Forgot password page
     */
    public function forgotPassword()
    {
        $this->view('auth.forgot-password');
    }

    /**
     * Change password page
     */
    public function changePassword()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect('auth/login');
        }

        $this->view('auth.change-password');
    }

    /**
     * Process password change
     */
    public function updatePassword()
    {
        if (!$this->auth->isLoggedIn()) {
            $this->redirect('auth/login');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // Validate inputs
            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                $_SESSION['error'] = 'All fields are required';
                $this->redirect('auth/change-password');
            }

            if ($newPassword !== $confirmPassword) {
                $_SESSION['error'] = 'New passwords do not match';
                $this->redirect('auth/change-password');
            }

            if (strlen($newPassword) < 6) {
                $_SESSION['error'] = 'Password must be at least 6 characters';
                $this->redirect('auth/change-password');
            }

            // Get current user and verify password
            $user = $this->auth->getUser();
            if (!$user || !password_verify($currentPassword, $user['password'])) {
                $_SESSION['error'] = 'Current password is incorrect';
                $this->redirect('auth/change-password');
            }

            // Update password
            $db = connectToDatabase();
            $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
            $userId = $this->auth->getUserId();
            $role = $this->auth->getRole();

            $tables = [
                'student' => 'students',
                'organization' => 'organizations',
                'coordinator' => 'coordinators',
                'supervisor' => 'supervisors'
            ];

            $sql = "UPDATE {$tables[$role]} SET password = '{$newHash}' WHERE id = {$userId}";
            
            if (mysqli_query($db, $sql)) {
                $_SESSION['success'] = 'Password changed successfully';
            } else {
                $_SESSION['error'] = 'Failed to change password';
            }

            $this->redirect($role . '/dashboard');
        } else {
            $this->redirect('auth/change-password');
        }
    }
}
