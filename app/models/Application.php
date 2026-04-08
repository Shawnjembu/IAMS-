<?php

require_once '../core/Model.php';

/**
 * Application Model
 * Handles student application-related database operations
 */
class Application extends Model
{
    protected $table = 'applications';

    /**
     * Get application with student and organization details
     * 
     * @param int $id Application ID
     * @return array|null Application data
     */
    public function getWithDetails($id)
    {
        $db = connectToDatabase();
        $id = (int)$id;
        
        $sql = "SELECT a.*, 
                s.student_number, s.full_name as student_name, s.email as student_email, s.program,
                o.organization_name, o.address as org_address, o.contact_person
                FROM {$this->table} a
                LEFT JOIN students s ON a.student_id = s.id
                LEFT JOIN organizations o ON a.organization_id = o.id
                WHERE a.id = {$id}";
        
        $result = mysqli_query($db, $sql);
        
        return mysqli_fetch_assoc($result);
    }

    /**
     * Get all applications with details
     * 
     * @return array Array of applications
     */
    public function getAllWithDetails()
    {
        $db = connectToDatabase();
        
        $sql = "SELECT a.*, 
                s.student_number, s.full_name as student_name, s.email as student_email, s.program,
                o.organization_name, o.address as org_address
                FROM {$this->table} a
                LEFT JOIN students s ON a.student_id = s.id
                LEFT JOIN organizations o ON a.organization_id = o.id
                ORDER BY a.id DESC";
        
        $result = mysqli_query($db, $sql);
        
        $applications = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $applications[] = $row;
        }
        
        return $applications;
    }

    /**
     * Get applications by student
     * 
     * @param int $studentId Student ID
     * @return array Array of applications
     */
    public function getByStudent($studentId)
    {
        $db = connectToDatabase();
        $studentId = (int)$studentId;
        
        $sql = "SELECT a.*, 
                o.organization_name, o.address as org_address
                FROM {$this->table} a
                LEFT JOIN organizations o ON a.organization_id = o.id
                WHERE a.student_id = {$studentId}
                ORDER BY a.id DESC";
        
        $result = mysqli_query($db, $sql);
        
        $applications = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $applications[] = $row;
        }
        
        return $applications;
    }

    /**
     * Get applications by organization
     * 
     * @param int $organizationId Organization ID
     * @return array Array of applications
     */
    public function getByOrganization($organizationId)
    {
        $db = connectToDatabase();
        $organizationId = (int)$organizationId;
        
        $sql = "SELECT a.*, 
                s.student_number, s.full_name as student_name, s.email as student_email, s.program
                FROM {$this->table} a
                LEFT JOIN students s ON a.student_id = s.id
                WHERE a.organization_id = {$organizationId}
                ORDER BY a.id DESC";
        
        $result = mysqli_query($db, $sql);
        
        $applications = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $applications[] = $row;
        }
        
        return $applications;
    }

    /**
     * Get pending applications by organization
     * 
     * @param int $organizationId Organization ID
     * @return array Array of pending applications
     */
    public function getPendingByOrganization($organizationId)
    {
        $db = connectToDatabase();
        $organizationId = (int)$organizationId;
        
        $sql = "SELECT a.*, 
                s.student_number, s.full_name as student_name, s.email as student_email, s.program
                FROM {$this->table} a
                LEFT JOIN students s ON a.student_id = s.id
                WHERE a.organization_id = {$organizationId} AND a.status = 'pending'
                ORDER BY a.id DESC";
        
        $result = mysqli_query($db, $sql);
        
        $applications = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $applications[] = $row;
        }
        
        return $applications;
    }

    /**
     * Check if student has applied to organization
     * 
     * @param int $studentId Student ID
     * @param int $organizationId Organization ID
     * @return bool True if applied
     */
    public function hasApplied($studentId, $organizationId)
    {
        $db = connectToDatabase();
        $studentId = (int)$studentId;
        $organizationId = (int)$organizationId;
        
        $sql = "SELECT id FROM {$this->table} 
                WHERE student_id = {$studentId} AND organization_id = {$organizationId}";
        
        $result = mysqli_query($db, $sql);
        
        return mysqli_num_rows($result) > 0;
    }

    /**
     * Update application status
     * 
     * @param int $id Application ID
     * @param string $status New status
     * @return bool True on success
     */
    public function updateStatus($id, $status)
    {
        $db = connectToDatabase();
        $id = (int)$id;
        $status = mysqli_real_escape_string($db, $status);
        
        $sql = "UPDATE {$this->table} 
                SET status = '{$status}', response_date = NOW()
                WHERE id = {$id}";
        
        return mysqli_query($db, $sql);
    }

    /**
     * Get application statistics
     * 
     * @return array Statistics
     */
    public function getStatistics()
    {
        $db = connectToDatabase();
        
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'accepted' THEN 1 ELSE 0 END) as accepted,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected,
                SUM(CASE WHEN status = 'withdrawn' THEN 1 ELSE 0 END) as withdrawn
                FROM {$this->table}";
        
        $result = mysqli_query($db, $sql);
        
        return mysqli_fetch_assoc($result);
    }
}
