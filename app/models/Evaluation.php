<?php

require_once '../core/Model.php';

/**
 * Evaluation Model
 * Handles evaluation-related database operations
 */
class Evaluation extends Model
{
    protected $table = 'evaluations';

    /**
     * Get evaluation with student and supervisor details
     * 
     * @param int $id Evaluation ID
     * @return array|null Evaluation data
     */
    public function getWithDetails($id)
    {
        $db = connectToDatabase();
        $id = (int)$id;
        
        $sql = "SELECT e.*, 
                s.student_number, s.full_name as student_name, s.email as student_email, s.program,
                sup.full_name as supervisor_name, sup.email as supervisor_email,
                o.organization_name
                FROM {$this->table} e
                LEFT JOIN students s ON e.student_id = s.id
                LEFT JOIN supervisors sup ON e.supervisor_id = sup.id
                LEFT JOIN placements p ON e.placement_id = p.id
                LEFT JOIN organizations o ON p.organization_id = o.id
                WHERE e.id = {$id}";
        
        $result = mysqli_query($db, $sql);
        
        return mysqli_fetch_assoc($result);
    }

    /**
     * Get all evaluations with details
     * 
     * @return array Array of evaluations
     */
    public function getAllWithDetails()
    {
        $db = connectToDatabase();
        
        $sql = "SELECT e.*, 
                s.student_number, s.full_name as student_name, s.program,
                sup.full_name as supervisor_name
                FROM {$this->table} e
                LEFT JOIN students s ON e.student_id = s.id
                LEFT JOIN supervisors sup ON e.supervisor_id = sup.id
                ORDER BY e.id DESC";
        
        $result = mysqli_query($db, $sql);
        
        $evaluations = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $evaluations[] = $row;
        }
        
        return $evaluations;
    }

    /**
     * Get evaluation by student
     * 
     * @param int $studentId Student ID
     * @return array Array of evaluations
     */
    public function getByStudent($studentId)
    {
        $db = connectToDatabase();
        $studentId = (int)$studentId;
        
        $sql = "SELECT e.*, 
                sup.full_name as supervisor_name
                FROM {$this->table} e
                LEFT JOIN supervisors sup ON e.supervisor_id = sup.id
                WHERE e.student_id = {$studentId}
                ORDER BY e.id DESC";
        
        $result = mysqli_query($db, $sql);
        
        $evaluations = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $evaluations[] = $row;
        }
        
        return $evaluations;
    }

    /**
     * Get evaluation by supervisor
     * 
     * @param int $supervisorId Supervisor ID
     * @return array Array of evaluations
     */
    public function getBySupervisor($supervisorId)
    {
        $db = connectToDatabase();
        $supervisorId = (int)$supervisorId;
        
        $sql = "SELECT e.*, 
                s.student_number, s.full_name as student_name, s.program
                FROM {$this->table} e
                LEFT JOIN students s ON e.student_id = s.id
                WHERE e.supervisor_id = {$supervisorId}
                ORDER BY e.id DESC";
        
        $result = mysqli_query($db, $sql);
        
        $evaluations = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $evaluations[] = $row;
        }
        
        return $evaluations;
    }

    /**
     * Get latest evaluation by student
     * 
     * @param int $studentId Student ID
     * @return array|null Latest evaluation
     */
    public function getLatestByStudent($studentId)
    {
        $db = connectToDatabase();
        $studentId = (int)$studentId;
        
        $sql = "SELECT e.*, 
                sup.full_name as supervisor_name
                FROM {$this->table} e
                LEFT JOIN supervisors sup ON e.supervisor_id = sup.id
                WHERE e.student_id = {$studentId} AND e.status = 'submitted'
                ORDER BY e.id DESC LIMIT 1";
        
        $result = mysqli_query($db, $sql);
        
        return mysqli_fetch_assoc($result);
    }

    /**
     * Check if evaluation exists for student by supervisor
     * 
     * @param int $studentId Student ID
     * @param int $supervisorId Supervisor ID
     * @return bool True if exists
     */
    public function exists($studentId, $supervisorId)
    {
        $db = connectToDatabase();
        $studentId = (int)$studentId;
        $supervisorId = (int)$supervisorId;
        
        $sql = "SELECT id FROM {$this->table} 
                WHERE student_id = {$studentId} AND supervisor_id = {$supervisorId}";
        
        $result = mysqli_query($db, $sql);
        
        return mysqli_num_rows($result) > 0;
    }

    /**
     * Create or update evaluation
     * 
     * @param array $data Evaluation data
     * @return int|bool Insert ID or false
     */
    public function save($data)
    {
        $db = connectToDatabase();
        
        // Check if evaluation exists
        if (isset($data['student_id']) && isset($data['supervisor_id'])) {
            $studentId = (int)$data['student_id'];
            $supervisorId = (int)$data['supervisor_id'];
            
            $checkSql = "SELECT id FROM {$this->table} 
                        WHERE student_id = {$studentId} AND supervisor_id = {$supervisorId}";
            $checkResult = mysqli_query($db, $checkSql);
            
            if (mysqli_num_rows($checkResult) > 0) {
                // Update existing
                $row = mysqli_fetch_assoc($checkResult);
                return $this->update($row['id'], $data);
            }
        }
        
        // Insert new
        $columns = implode(', ', array_keys($data));
        $values = "'" . implode("', '", array_map([$db, 'real_escape_string'], array_values($data))) . "'";
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$values})";
        
        if (mysqli_query($db, $sql)) {
            return mysqli_insert_id($db);
        }
        return false;
    }

    /**
     * Submit evaluation
     * 
     * @param int $id Evaluation ID
     * @return bool True on success
     */
    public function submit($id)
    {
        $db = connectToDatabase();
        $id = (int)$id;
        
        // Calculate overall score
        $sql = "UPDATE {$this->table} 
                SET status = 'submitted',
                    overall_score = (attendance_score + performance_score + professionalism_score + learning_score) / 4
                WHERE id = {$id}";
        
        return mysqli_query($db, $sql);
    }

    /**
     * Get evaluation statistics
     * 
     * @return array Statistics
     */
    public function getStatistics()
    {
        $db = connectToDatabase();
        
        $sql = "SELECT 
                COUNT(*) as total,
                AVG(overall_score) as average_score,
                SUM(CASE WHEN status = 'submitted' THEN 1 ELSE 0 END) as submitted,
                SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) as draft
                FROM {$this->table}
                WHERE status = 'submitted'";
        
        $result = mysqli_query($db, $sql);
        
        return mysqli_fetch_assoc($result);
    }

    /**
     * Get average scores by category
     * 
     * @return array Average scores
     */
    public function getAverageScores()
    {
        $db = connectToDatabase();
        
        $sql = "SELECT 
                AVG(attendance_score) as attendance,
                AVG(performance_score) as performance,
                AVG(professionalism_score) as professionalism,
                AVG(learning_score) as learning,
                AVG(overall_score) as overall
                FROM {$this->table}
                WHERE status = 'submitted' AND overall_score IS NOT NULL";
        
        $result = mysqli_query($db, $sql);
        
        return mysqli_fetch_assoc($result);
    }
}
