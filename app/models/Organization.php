<?php

require_once '../core/Model.php';

/**
 * Organization Model
 * Handles organization-related database operations
 */
class Organization extends Model
{
    protected $table = 'organizations';

    /**
     * Get organization by email
     * 
     * @param string $email Organization email
     * @return array|null Organization data or null
     */
    public function getByEmail($email)
    {
        $db = connectToDatabase();
        $email = mysqli_real_escape_string($db, $email);
        
        $sql = "SELECT * FROM {$this->table} WHERE email = '{$email}'";
        $result = mysqli_query($db, $sql);
        
        return mysqli_fetch_assoc($result);
    }

    /**
     * Get organization with student count
     * 
     * @param int $id Organization ID
     * @return array|null Organization data with student count
     */
    public function getWithStudentCount($id)
    {
        $db = connectToDatabase();
        
        $sql = "SELECT o.*, COUNT(s.id) as student_count 
                FROM {$this->table} o 
                LEFT JOIN students s ON o.id = s.organization_id 
                WHERE o.id = {$id} 
                GROUP BY o.id";
        
        $result = mysqli_query($db, $sql);
        
        return mysqli_fetch_assoc($result);
    }

    /**
     * Get all organizations with student counts
     * 
     * @return array Array of organizations with student counts
     */
    public function getAllWithStudentCounts()
    {
        $db = connectToDatabase();
        
        $sql = "SELECT o.*, COUNT(s.id) as student_count 
                FROM {$this->table} o 
                LEFT JOIN students s ON o.id = s.organization_id 
                GROUP BY o.id 
                ORDER BY o.id DESC";
        
        $result = mysqli_query($db, $sql);
        
        $organizations = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $organizations[] = $row;
        }
        
        return $organizations;
    }

    /**
     * Update organization profile
     * 
     * @param int $id Organization ID
     * @param array $data Data to update
     * @return bool True on success
     */
    public function updateProfile($id, $data)
    {
        $db = connectToDatabase();
        
        $updates = [];
        foreach ($data as $key => $value) {
            $value = mysqli_real_escape_string($db, $value);
            $updates[] = "{$key} = '{$value}'";
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $updates) . " WHERE id = {$id}";
        
        return mysqli_query($db, $sql);
    }

    /**
     * Check if email already exists
     * 
     * @param string $email Email to check
     * @param int|null $excludeId Exclude this ID from check
     * @return bool True if email exists
     */
    public function emailExists($email, $excludeId = null)
    {
        $db = connectToDatabase();
        $email = mysqli_real_escape_string($db, $email);
        
        $sql = "SELECT id FROM {$this->table} WHERE email = '{$email}'";
        if ($excludeId) {
            $sql .= " AND id != {$excludeId}";
        }
        
        $result = mysqli_query($db, $sql);
        
        return mysqli_num_rows($result) > 0;
    }

    /**
     * Approve or reject organization
     * 
     * @param int $id Organization ID
     * @param string $status Status (approved, rejected)
     * @return bool True on success
     */
    public function updateStatus($id, $status)
    {
        $db = connectToDatabase();
        
        $sql = "UPDATE {$this->table} SET status = '{$status}' WHERE id = {$id}";
        
        return mysqli_query($db, $sql);
    }

    /**
     * Get pending organizations
     * 
     * @return array Array of pending organizations
     */
    public function getPending()
    {
        $db = connectToDatabase();
        
        $sql = "SELECT * FROM {$this->table} WHERE status = 'pending' ORDER BY id DESC";
        $result = mysqli_query($db, $sql);
        
        $organizations = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $organizations[] = $row;
        }
        
        return $organizations;
    }
}
