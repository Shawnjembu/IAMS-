<?php

require_once '../core/Model.php';

/**
 * Report Model
 * Handles report-related database operations
 */
class Report extends Model
{
    protected $table = 'reports';

    /**
     * Get report with student details
     * 
     * @param int $id Report ID
     * @return array|null Report data
     */
    public function getWithStudent($id)
    {
        $db = connectToDatabase();
        $id = (int)$id;
        
        $sql = "SELECT r.*, 
                s.student_number, s.full_name as student_name, s.program,
                sup.full_name as reviewer_name
                FROM {$this->table} r
                LEFT JOIN students s ON r.student_id = s.id
                LEFT JOIN supervisors sup ON r.reviewed_by = sup.id
                WHERE r.id = {$id}";
        
        $result = mysqli_query($db, $sql);
        
        return mysqli_fetch_assoc($result);
    }

    /**
     * Get all reports with student details
     * 
     * @return array Array of reports
     */
    public function getAllWithStudent()
    {
        $db = connectToDatabase();
        
        $sql = "SELECT r.*, 
                s.student_number, s.full_name as student_name, s.program
                FROM {$this->table} r
                LEFT JOIN students s ON r.student_id = s.id
                ORDER BY r.id DESC";
        
        $result = mysqli_query($db, $sql);
        
        $reports = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $reports[] = $row;
        }
        
        return $reports;
    }

    /**
     * Get report by student
     * 
     * @param int $studentId Student ID
     * @return array Array of reports
     */
    public function getByStudent($studentId)
    {
        $db = connectToDatabase();
        $studentId = (int)$studentId;
        
        $sql = "SELECT * FROM {$this->table} WHERE student_id = {$studentId} ORDER BY id DESC";
        
        $result = mysqli_query($db, $sql);
        
        $reports = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $reports[] = $row;
        }
        
        return $reports;
    }

    /**
     * Get latest report by student
     * 
     * @param int $studentId Student ID
     * @return array|null Latest report
     */
    public function getLatestByStudent($studentId)
    {
        $db = connectToDatabase();
        $studentId = (int)$studentId;
        
        $sql = "SELECT * FROM {$this->table} WHERE student_id = {$studentId} ORDER BY id DESC LIMIT 1";
        
        $result = mysqli_query($db, $sql);
        
        return mysqli_fetch_assoc($result);
    }

    /**
     * Get pending reports (submitted but not reviewed)
     * 
     * @return array Array of pending reports
     */
    public function getPending()
    {
        $db = connectToDatabase();
        
        $sql = "SELECT r.*, 
                s.student_number, s.full_name as student_name, s.program
                FROM {$this->table} r
                LEFT JOIN students s ON r.student_id = s.id
                WHERE r.status = 'submitted'
                ORDER BY r.id DESC";
        
        $result = mysqli_query($db, $sql);
        
        $reports = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $reports[] = $row;
        }
        
        return $reports;
    }

    /**
     * Upload new report
     * 
     * @param array $data Report data
     * @return int|bool Insert ID or false
     */
    public function upload($data)
    {
        $db = connectToDatabase();
        
        $columns = implode(', ', array_keys($data));
        $values = "'" . implode("', '", array_map([$db, 'real_escape_string'], array_values($data))) . "'";
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$values})";
        
        if (mysqli_query($db, $sql)) {
            return mysqli_insert_id($db);
        }
        return false;
    }

    /**
     * Review report
     * 
     * @param int $id Report ID
     * @param int $reviewerId Reviewer ID (supervisor)
     * @param string $status Status (approved/rejected)
     * @param string $comments Comments
     * @return bool True on success
     */
    public function review($id, $reviewerId, $status, $comments = '')
    {
        $db = connectToDatabase();
        $id = (int)$id;
        $reviewerId = (int)$reviewerId;
        $status = mysqli_real_escape_string($db, $status);
        $comments = mysqli_real_escape_string($db, $comments);
        
        $sql = "UPDATE {$this->table} 
                SET status = '{$status}', 
                    reviewed_by = {$reviewerId}, 
                    reviewed_at = NOW()
                WHERE id = {$id}";
        
        if ($comments !== '') {
            $sql = "UPDATE {$this->table} 
                    SET status = '{$status}', 
                        reviewed_by = {$reviewerId}, 
                        reviewed_at = NOW(),
                        description = CONCAT(IFNULL(description, ''), ' | Review: {$comments}')
                    WHERE id = {$id}";
        }
        
        return mysqli_query($db, $sql);
    }

    /**
     * Delete report
     * 
     * @param int $id Report ID
     * @return bool True on success
     */
    public function deleteReport($id)
    {
        $db = connectToDatabase();
        $id = (int)$id;
        
        // Get file path first
        $sql = "SELECT file_path FROM {$this->table} WHERE id = {$id}";
        $result = mysqli_query($db, $sql);
        $row = mysqli_fetch_assoc($result);
        
        if ($row && file_exists($row['file_path'])) {
            unlink($row['file_path']);
        }
        
        $sql = "DELETE FROM {$this->table} WHERE id = {$id}";
        
        return mysqli_query($db, $sql);
    }

    /**
     * Get report statistics
     * 
     * @return array Statistics
     */
    public function getStatistics()
    {
        $db = connectToDatabase();
        
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'submitted' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
                FROM {$this->table}";
        
        $result = mysqli_query($db, $sql);
        
        return mysqli_fetch_assoc($result);
    }
}
