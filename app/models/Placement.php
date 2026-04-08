<?php

require_once '../core/Model.php';

/**
 * Placement Model
 * Handles placement-related database operations
 */
class Placement extends Model
{
    protected $table = 'placements';

    /**
     * Get placement with student and organization details
     * 
     * @param int $id Placement ID
     * @return array|null Placement data with relations
     */
    public function getWithDetails($id)
    {
        $db = connectToDatabase();
        $id = (int)$id;
        
        $sql = "SELECT p.*, 
                s.student_number, s.full_name as student_name, s.email as student_email, s.program,
                o.organization_name, o.address as org_address, o.contact_person,
                sup.full_name as supervisor_name, sup.email as supervisor_email
                FROM {$this->table} p
                LEFT JOIN students s ON p.student_id = s.id
                LEFT JOIN organizations o ON p.organization_id = o.id
                LEFT JOIN supervisors sup ON p.supervisor_id = sup.id
                WHERE p.id = {$id}";
        
        $result = mysqli_query($db, $sql);
        
        return mysqli_fetch_assoc($result);
    }

    /**
     * Get all placements with details
     * 
     * @return array Array of placements
     */
    public function getAllWithDetails()
    {
        $db = connectToDatabase();
        
        $sql = "SELECT p.*, 
                s.student_number, s.full_name as student_name, s.email as student_email, s.program,
                o.organization_name, o.address as org_address,
                sup.full_name as supervisor_name
                FROM {$this->table} p
                LEFT JOIN students s ON p.student_id = s.id
                LEFT JOIN organizations o ON p.organization_id = o.id
                LEFT JOIN supervisors sup ON p.supervisor_id = sup.id
                ORDER BY p.id DESC";
        
        $result = mysqli_query($db, $sql);
        
        $placements = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $placements[] = $row;
        }
        
        return $placements;
    }

    /**
     * Get placements by student
     * 
     * @param int $studentId Student ID
     * @return array Array of placements
     */
    public function getByStudent($studentId)
    {
        $db = connectToDatabase();
        $studentId = (int)$studentId;
        
        $sql = "SELECT p.*, 
                o.organization_name, o.address as org_address,
                sup.full_name as supervisor_name
                FROM {$this->table} p
                LEFT JOIN organizations o ON p.organization_id = o.id
                LEFT JOIN supervisors sup ON p.supervisor_id = sup.id
                WHERE p.student_id = {$studentId}
                ORDER BY p.id DESC";
        
        $result = mysqli_query($db, $sql);
        
        $placements = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $placements[] = $row;
        }
        
        return $placements;
    }

    /**
     * Get placements by organization
     * 
     * @param int $organizationId Organization ID
     * @return array Array of placements
     */
    public function getByOrganization($organizationId)
    {
        $db = connectToDatabase();
        $organizationId = (int)$organizationId;
        
        $sql = "SELECT p.*, 
                s.student_number, s.full_name as student_name, s.email as student_email, s.program,
                sup.full_name as supervisor_name
                FROM {$this->table} p
                LEFT JOIN students s ON p.student_id = s.id
                LEFT JOIN supervisors sup ON p.supervisor_id = sup.id
                WHERE p.organization_id = {$organizationId}
                ORDER BY p.id DESC";
        
        $result = mysqli_query($db, $sql);
        
        $placements = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $placements[] = $row;
        }
        
        return $placements;
    }

    /**
     * Get placements by supervisor
     * 
     * @param int $supervisorId Supervisor ID
     * @return array Array of placements
     */
    public function getBySupervisor($supervisorId)
    {
        $db = connectToDatabase();
        $supervisorId = (int)$supervisorId;
        
        $sql = "SELECT p.*, 
                s.student_number, s.full_name as student_name, s.email as student_email, s.program,
                o.organization_name
                FROM {$this->table} p
                LEFT JOIN students s ON p.student_id = s.id
                LEFT JOIN organizations o ON p.organization_id = o.id
                WHERE p.supervisor_id = {$supervisorId}
                ORDER BY p.id DESC";
        
        $result = mysqli_query($db, $sql);
        
        $placements = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $placements[] = $row;
        }
        
        return $placements;
    }

    /**
     * Get pending placements
     * 
     * @return array Array of pending placements
     */
    public function getPending()
    {
        $db = connectToDatabase();
        
        $sql = "SELECT p.*, 
                s.student_number, s.full_name as student_name, s.email as student_email, s.program,
                o.organization_name
                FROM {$this->table} p
                LEFT JOIN students s ON p.student_id = s.id
                LEFT JOIN organizations o ON p.organization_id = o.id
                WHERE p.status = 'pending'
                ORDER BY p.id DESC";
        
        $result = mysqli_query($db, $sql);
        
        $placements = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $placements[] = $row;
        }
        
        return $placements;
    }

    /**
     * Get active placements
     * 
     * @return array Array of active placements
     */
    public function getActive()
    {
        $db = connectToDatabase();
        
        $sql = "SELECT p.*, 
                s.student_number, s.full_name as student_name, s.email as student_email, s.program,
                o.organization_name
                FROM {$this->table} p
                LEFT JOIN students s ON p.student_id = s.id
                LEFT JOIN organizations o ON p.organization_id = o.id
                WHERE p.status = 'active'
                ORDER BY p.id DESC";
        
        $result = mysqli_query($db, $sql);
        
        $placements = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $placements[] = $row;
        }
        
        return $placements;
    }

    /**
     * Update placement status
     * 
     * @param int $id Placement ID
     * @param string $status New status
     * @param string $comments Optional comments
     * @return bool True on success
     */
    public function updateStatus($id, $status, $comments = '')
    {
        $db = connectToDatabase();
        $id = (int)$id;
        $status = mysqli_real_escape_string($db, $status);
        $comments = mysqli_real_escape_string($db, $comments);
        
        $sql = "UPDATE {$this->table} SET status = '{$status}'";
        if ($comments !== '') {
            $sql .= ", comments = '{$comments}'";
        }
        $sql .= " WHERE id = {$id}";
        
        return mysqli_query($db, $sql);
    }

    /**
     * Get placement statistics
     * 
     * @return array Statistics
     */
    public function getStatistics()
    {
        $db = connectToDatabase();
        
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
                FROM {$this->table}";
        
        $result = mysqli_query($db, $sql);
        
        return mysqli_fetch_assoc($result);
    }
}
