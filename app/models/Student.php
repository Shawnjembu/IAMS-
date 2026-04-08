<?php

require_once '../core/Model.php';

/**
 * Student Model
 * Handles student-related database operations
 */
class Student extends Model
{
    protected $table = 'students';

    /**
     * Get student by email
     * 
     * @param string $email Student email
     * @return array|null Student data or null
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
     * Get student with organization details
     * 
     * @param int $id Student ID
     * @return array|null Student data with organization
     */
    public function getWithOrganization($id)
    {
        $db = connectToDatabase();
        
        $sql = "SELECT s.*, o.organization_name, o.address as org_address 
                FROM {$this->table} s 
                LEFT JOIN organizations o ON s.organization_id = o.id 
                WHERE s.id = {$id}";
        
        $result = mysqli_query($db, $sql);
        
        return mysqli_fetch_assoc($result);
    }

    /**
     * Get all students with their organizations
     * 
     * @return array Array of students with organizations
     */
    public function getAllWithOrganizations()
    {
        $db = connectToDatabase();
        
        $sql = "SELECT s.*, o.organization_name, o.address as org_address 
                FROM {$this->table} s 
                LEFT JOIN organizations o ON s.organization_id = o.id 
                ORDER BY s.id DESC";
        
        $result = mysqli_query($db, $sql);
        
        $students = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $students[] = $row;
        }
        
        return $students;
    }

    /**
     * Get students by organization
     * 
     * @param int $organizationId Organization ID
     * @return array Array of students in the organization
     */
    public function getByOrganization($organizationId)
    {
        $db = connectToDatabase();
        
        $sql = "SELECT * FROM {$this->table} WHERE organization_id = {$organizationId}";
        $result = mysqli_query($db, $sql);
        
        $students = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $students[] = $row;
        }
        
        return $students;
    }

    /**
     * Get students by supervisor
     * 
     * @param int $supervisorId Supervisor ID
     * @return array Array of supervised students
     */
    public function getBySupervisor($supervisorId)
    {
        $db = connectToDatabase();
        
        $sql = "SELECT * FROM {$this->table} WHERE supervisor_id = {$supervisorId}";
        $result = mysqli_query($db, $sql);
        
        $students = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $students[] = $row;
        }
        
        return $students;
    }

    /**
     * Update student profile
     * 
     * @param int $id Student ID
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
}
