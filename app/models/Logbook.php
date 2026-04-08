<?php

require_once '../core/Model.php';

/**
 * Logbook Model
 * Handles logbook-related database operations
 */
class Logbook extends Model
{
    protected $table = 'logbooks';

    /**
     * Get logbook with student details
     * 
     * @param int $id Logbook ID
     * @return array|null Logbook data
     */
    public function getWithStudent($id)
    {
        $db = connectToDatabase();
        $id = (int)$id;
        
        $sql = "SELECT l.*, 
                s.student_number, s.full_name as student_name, s.program,
                sup.full_name as reviewer_name
                FROM {$this->table} l
                LEFT JOIN students s ON l.student_id = s.id
                LEFT JOIN supervisors sup ON l.reviewed_by = sup.id
                WHERE l.id = {$id}";
        
        $result = mysqli_query($db, $sql);
        
        return mysqli_fetch_assoc($result);
    }

    /**
     * Get all logbooks with student details
     * 
     * @return array Array of logbooks
     */
    public function getAllWithStudent()
    {
        $db = connectToDatabase();
        
        $sql = "SELECT l.*, 
                s.student_number, s.full_name as student_name, s.program
                FROM {$this->table} l
                LEFT JOIN students s ON l.student_id = s.id
                ORDER BY l.id DESC";
        
        $result = mysqli_query($db, $sql);
        
        $logbooks = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $logbooks[] = $row;
        }
        
        return $logbooks;
    }

    /**
     * Get logbooks by student
     * 
     * @param int $studentId Student ID
     * @return array Array of logbooks
     */
    public function getByStudent($studentId)
    {
        $db = connectToDatabase();
        $studentId = (int)$studentId;
        
        $sql = "SELECT * FROM {$this->table} WHERE student_id = {$studentId} ORDER BY week_number DESC";
        
        $result = mysqli_query($db, $sql);
        
        $logbooks = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $logbooks[] = $row;
        }
        
        return $logbooks;
    }

    /**
     * Get logbook by student and week
     * 
     * @param int $studentId Student ID
     * @param int $weekNumber Week number
     * @return array|null Logbook data
     */
    public function getByStudentAndWeek($studentId, $weekNumber)
    {
        $db = connectToDatabase();
        $studentId = (int)$studentId;
        $weekNumber = (int)$weekNumber;
        
        $sql = "SELECT * FROM {$this->table} WHERE student_id = {$studentId} AND week_number = {$weekNumber}";
        
        $result = mysqli_query($db, $sql);
        
        return mysqli_fetch_assoc($result);
    }

    /**
     * Get pending logbooks (submitted but not reviewed)
     * 
     * @return array Array of pending logbooks
     */
    public function getPending()
    {
        $db = connectToDatabase();
        
        $sql = "SELECT l.*, 
                s.student_number, s.full_name as student_name, s.program
                FROM {$this->table} l
                LEFT JOIN students s ON l.student_id = s.id
                WHERE l.status = 'submitted'
                ORDER BY l.id DESC";
        
        $result = mysqli_query($db, $sql);
        
        $logbooks = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $logbooks[] = $row;
        }
        
        return $logbooks;
    }

    /**
     * Get logbooks by supervisor's students
     * 
     * @param int $supervisorId Supervisor ID
     * @return array Array of logbooks
     */
    public function getBySupervisorStudents($supervisorId)
    {
        $db = connectToDatabase();
        $supervisorId = (int)$supervisorId;
        
        $sql = "SELECT l.*, 
                s.student_number, s.full_name as student_name, s.program
                FROM {$this->table} l
                LEFT JOIN students s ON l.student_id = s.id
                LEFT JOIN placements p ON s.id = p.student_id
                WHERE p.supervisor_id = {$supervisorId}
                ORDER BY l.id DESC";
        
        $result = mysqli_query($db, $sql);
        
        $logbooks = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $logbooks[] = $row;
        }
        
        return $logbooks;
    }

    /**
     * Submit logbook for review
     * 
     * @param int $id Logbook ID
     * @return bool True on success
     */
    public function submitForReview($id)
    {
        $db = connectToDatabase();
        $id = (int)$id;
        
        $sql = "UPDATE {$this->table} SET status = 'submitted' WHERE id = {$id}";
        
        return mysqli_query($db, $sql);
    }

    /**
     * Review logbook
     * 
     * @param int $id Logbook ID
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
                    reviewed_at = NOW(),
                    comments = '{$comments}'
                WHERE id = {$id}";
        
        return mysqli_query($db, $sql);
    }

    /**
     * Get logbook statistics by student
     * 
     * @param int $studentId Student ID
     * @return array Statistics
     */
    public function getStudentStatistics($studentId)
    {
        $db = connectToDatabase();
        $studentId = (int)$studentId;
        
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'submitted' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
                FROM {$this->table}
                WHERE student_id = {$studentId}";
        
        $result = mysqli_query($db, $sql);
        
        return mysqli_fetch_assoc($result);
    }

    /**
     * Get current week number for student
     * 
     * @param int $studentId Student ID
     * @return int Current week number
     */
    public function getCurrentWeek($studentId)
    {
        $db = connectToDatabase();
        $studentId = (int)$studentId;
        
        $sql = "SELECT MAX(week_number) as max_week FROM {$this->table} WHERE student_id = {$studentId}";
        
        $result = mysqli_query($db, $sql);
        $row = mysqli_fetch_assoc($result);
        
        return ($row['max_week'] ?? 0) + 1;
    }
}
