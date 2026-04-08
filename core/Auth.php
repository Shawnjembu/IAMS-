<?php

/**
 * Authentication Class
 * Handles user authentication and session management
 */
class Auth
{
    /**
     * Start session if not already started
     */
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Attempt to log in a user
     * 
     * @param string $email User email
     * @param string $password User password
     * @return bool True if login successful, false otherwise
     */
    public function login($email, $password)
    {
        $db = connectToDatabase();
        
        // Check in students table
        $sql = "SELECT * FROM students WHERE email = ?";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {
                $this->setSession($user, 'student');
                return true;
            }
        }

        // Check in organizations table
        $sql = "SELECT * FROM organizations WHERE email = ?";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {
                $this->setSession($user, 'organization');
                return true;
            }
        }

        // Check in coordinators table
        $sql = "SELECT * FROM coordinators WHERE email = ?";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {
                $this->setSession($user, 'coordinator');
                return true;
            }
        }

        // Check in supervisors table
        $sql = "SELECT * FROM supervisors WHERE email = ?";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {
                $this->setSession($user, 'supervisor');
                return true;
            }
        }

        return false;
    }

    /**
     * Set user session data
     * 
     * @param array $user User data
     * @param string $role User role
     */
    private function setSession($user, $role)
    {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['name'] = $user['name'] ?? $user['organization_name'] ?? $user['full_name'];
        $_SESSION['role'] = $role;
        $_SESSION['logged_in'] = true;
    }

    /**
     * Check if user is logged in
     * 
     * @return bool True if logged in, false otherwise
     */
    public function isLoggedIn()
    {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }

    /**
     * Get current user role
     * 
     * @return string|null User role or null
     */
    public function getRole()
    {
        return $_SESSION['role'] ?? null;
    }

    /**
     * Get current user ID
     * 
     * @return int|null User ID or null
     */
    public function getUserId()
    {
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Get current user data
     * 
     * @return array|null User data or null
     */
    public function getUser()
    {
        if (!$this->isLoggedIn()) {
            return null;
        }

        $db = connectToDatabase();
        $role = $this->getRole();
        $id = $this->getUserId();

        $tables = [
            'student' => 'students',
            'organization' => 'organizations',
            'coordinator' => 'coordinators',
            'supervisor' => 'supervisors'
        ];

        $sql = "SELECT * FROM {$tables[$role]} WHERE id = ?";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        return mysqli_fetch_assoc($result);
    }

    /**
     * Check if user has specific role
     * 
     * @param string $role Role to check
     * @return bool True if user has role, false otherwise
     */
    public function hasRole($role)
    {
        return $this->getRole() === $role;
    }

    /**
     * Log out the user
     */
    public function logout()
    {
        session_unset();
        session_destroy();
    }

    /**
     * Register a new student
     * 
     * @param array $data Student data
     * @return int|bool Insert ID or false on failure
     */
    public function registerStudent($data)
    {
        $db = connectToDatabase();
        
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['created_at'] = date('Y-m-d H:i:s');
        
        $columns = implode(', ', array_keys($data));
        $values = "'" . implode("', '", array_map([$db, 'real_escape_string'], array_values($data))) . "'";
        
        $sql = "INSERT INTO students ({$columns}) VALUES ({$values})";
        
        if (mysqli_query($db, $sql)) {
            return mysqli_insert_id($db);
        }
        return false;
    }

    /**
     * Register a new organization
     * 
     * @param array $data Organization data
     * @return int|bool Insert ID or false on failure
     */
    public function registerOrganization($data)
    {
        $db = connectToDatabase();
        
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['created_at'] = date('Y-m-d H:i:s');
        
        $columns = implode(', ', array_keys($data));
        $values = "'" . implode("', '", array_map([$db, 'real_escape_string'], array_values($data))) . "'";
        
        $sql = "INSERT INTO organizations ({$columns}) VALUES ({$values})";
        
        if (mysqli_query($db, $sql)) {
            return mysqli_insert_id($db);
        }
        return false;
    }
}
