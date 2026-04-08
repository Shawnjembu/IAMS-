<?php

require_once '../core/Controller.php';
require_once '../core/Auth.php';

/**
 * SupervisorController
 * Handles supervisor-specific functionality
 */
class SupervisorController extends Controller
{
    private $auth;

    public function __construct($params = [])
    {
        parent::__construct($params);
        $this->auth = new Auth();
        
        // Check if user is logged in and is a supervisor
        if (!$this->auth->isLoggedIn() || !$this->auth->hasRole('supervisor')) {
            $this->redirect('auth/login');
        }
    }

    /**
     * Supervisor dashboard
     */
    public function dashboard()
    {
        $userId = $this->auth->getUserId();
        
        // Get assigned students
        $student = new Student();
        $assignedStudents = $student->getBySupervisor($userId);
        
        // Get pending logbooks count
        $db = connectToDatabase();
        $sql = "SELECT COUNT(*) as count FROM logbooks l
                LEFT JOIN placements p ON l.student_id = p.student_id
                WHERE p.supervisor_id = {$userId} AND l.status = 'submitted'";
        $result = mysqli_query($db, $sql);
        $pendingLogbooks = $result ? mysqli_fetch_assoc($result)['count'] : 0;
        
        // Get pending reports count
        $sql = "SELECT COUNT(*) as count FROM reports r
                LEFT JOIN placements p ON r.student_id = p.student_id
                WHERE p.supervisor_id = {$userId} AND r.status = 'submitted'";
        $result = mysqli_query($db, $sql);
        $pendingReports = $result ? mysqli_fetch_assoc($result)['count'] : 0;
        
        // Get notifications
        $notificationModel = new Notification();
        $notifications = $notificationModel->getUnread($userId, 'supervisor');
        
        $this->view('supervisor.dashboard', [
            'assignedStudents' => $assignedStudents,
            'pendingLogbooks' => $pendingLogbooks,
            'pendingReports' => $pendingReports,
            'notifications' => $notifications
        ]);
    }

    /**
     * View assigned students
     */
    public function students()
    {
        $userId = $this->auth->getUserId();
        
        $student = new Student();
        $assignedStudents = $student->getBySupervisor($userId);
        
        $this->view('supervisor.students', ['students' => $assignedStudents]);
    }

    /**
     * View student details
     */
    public function viewStudent()
    {
        $id = $_GET['id'] ?? 0;
        
        $student = new Student();
        $studentData = $student->getById($id);
        
        // Get placement
        $placementModel = new Placement();
        $placements = $placementModel->getByStudent($id);
        
        // Get logbooks
        $logbookModel = new Logbook();
        $logbooks = $logbookModel->getByStudent($id);
        
        // Get reports
        $reportModel = new Report();
        $reports = $reportModel->getByStudent($id);
        
        // Get evaluations
        $evaluationModel = new Evaluation();
        $evaluations = $evaluationModel->getByStudent($id);
        
        $this->view('supervisor.view-student', [
            'student' => $studentData,
            'placement' => !empty($placements) ? $placements[0] : null,
            'logbooks' => $logbooks,
            'reports' => $reports,
            'evaluations' => $evaluations
        ]);
    }

    /**
     * View logbooks
     */
    public function logbooks()
    {
        $userId = $this->auth->getUserId();
        
        $logbookModel = new Logbook();
        $logbooks = $logbookModel->getBySupervisorStudents($userId);
        
        $this->view('supervisor.logbooks', ['logbooks' => $logbooks]);
    }

    /**
     * View logbook details
     */
    public function viewLogbook()
    {
        $id = $_GET['id'] ?? 0;
        
        $logbookModel = new Logbook();
        $logbook = $logbookModel->getWithStudent($id);
        
        $this->view('supervisor.view-logbook', ['logbook' => $logbook]);
    }

    /**
     * Review logbook
     */
    public function reviewLogbook()
    {
        $userId = $this->auth->getUserId();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $logbookId = $_POST['logbook_id'] ?? 0;
            $action = $_POST['action'] ?? '';
            $comments = $_POST['comments'] ?? '';
            
            $logbookModel = new Logbook();
            $logbook = $logbookModel->getById($logbookId);
            
            if ($logbook) {
                $status = ($action === 'approve') ? 'approved' : 'rejected';
                $logbookModel->review($logbookId, $userId, $status, $comments);
                
                // Send notification to student
                $notificationModel = new Notification();
                $notificationModel->send($logbook['student_id'], 'student',
                    'Logbook ' . ucfirst($status),
                    'Your logbook entry has been ' . $status . '.',
                    $status === 'approved' ? 'success' : 'warning');
                
                $_SESSION['success'] = 'Logbook reviewed successfully';
            }
        }
        
        $this->redirect('supervisor/logbooks');
    }

    /**
     * View reports
     */
    public function reports()
    {
        $userId = $this->auth->getUserId();
        
        $db = connectToDatabase();
        $sql = "SELECT r.*, s.student_number, s.full_name as student_name, s.program
                FROM reports r
                LEFT JOIN placements p ON r.student_id = p.student_id
                LEFT JOIN students s ON r.student_id = s.id
                WHERE p.supervisor_id = {$userId}
                ORDER BY r.id DESC";
        
        $result = mysqli_query($db, $sql);
        $reports = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $reports[] = $row;
        }
        
        $this->view('supervisor.reports', ['reports' => $reports]);
    }

    /**
     * View report details
     */
    public function viewReport()
    {
        $id = $_GET['id'] ?? 0;
        
        $reportModel = new Report();
        $report = $reportModel->getWithStudent($id);
        
        $this->view('supervisor.view-report', ['report' => $report]);
    }

    /**
     * Review report
     */
    public function reviewReport()
    {
        $userId = $this->auth->getUserId();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reportId = $_POST['report_id'] ?? 0;
            $action = $_POST['action'] ?? '';
            $comments = $_POST['comments'] ?? '';
            
            $reportModel = new Report();
            $report = $reportModel->getById($reportId);
            
            if ($report) {
                $status = ($action === 'approve') ? 'approved' : 'rejected';
                $reportModel->review($reportId, $userId, $status, $comments);
                
                // Send notification to student
                $notificationModel = new Notification();
                $notificationModel->send($report['student_id'], 'student',
                    'Report ' . ucfirst($status),
                    'Your report has been ' . $status . '.',
                    $status === 'approved' ? 'success' : 'warning');
                
                $_SESSION['success'] = 'Report reviewed successfully';
            }
        }
        
        $this->redirect('supervisor/reports');
    }

    /**
     * Evaluate student
     */
    public function evaluate()
    {
        $userId = $this->auth->getUserId();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $studentId = $_POST['student_id'] ?? 0;
            $placementId = $_POST['placement_id'] ?? null;
            
            $data = [
                'student_id' => $studentId,
                'supervisor_id' => $userId,
                'placement_id' => $placementId,
                'attendance_score' => $_POST['attendance_score'] ?? 0,
                'performance_score' => $_POST['performance_score'] ?? 0,
                'professionalism_score' => $_POST['professionalism_score'] ?? 0,
                'learning_score' => $_POST['learning_score'] ?? 0,
                'strengths' => $_POST['strengths'] ?? '',
                'weaknesses' => $_POST['weaknesses'] ?? '',
                'comments' => $_POST['comments'] ?? '',
                'status' => 'submitted',
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $evaluationModel = new Evaluation();
            
            // Calculate overall score
            $data['overall_score'] = (
                $data['attendance_score'] + 
                $data['performance_score'] + 
                $data['professionalism_score'] + 
                $data['learning_score']
            ) / 4;
            
            if ($evaluationModel->save($data)) {
                // Send notification to student
                $notificationModel = new Notification();
                $notificationModel->send($studentId, 'student',
                    'New Evaluation',
                    'Your supervisor has submitted an evaluation for you.',
                    'info');
                
                $_SESSION['success'] = 'Evaluation submitted successfully';
            } else {
                $_SESSION['error'] = 'Failed to submit evaluation';
            }
            
            $this->redirect('supervisor/students');
        }
        
        $this->redirect('supervisor/students');
    }

    /**
     * View profile
     */
    public function profile()
    {
        $userId = $this->auth->getUserId();
        
        $db = connectToDatabase();
        $sql = "SELECT * FROM supervisors WHERE id = {$userId}";
        $result = mysqli_query($db, $sql);
        $supervisor = mysqli_fetch_assoc($result);
        
        $this->view('supervisor.profile', ['supervisor' => $supervisor]);
    }

    /**
     * Edit profile
     */
    public function editProfile()
    {
        $userId = $this->auth->getUserId();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'full_name' => $_POST['full_name'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'position' => $_POST['position'] ?? ''
            ];
            
            $db = connectToDatabase();
            $updates = [];
            foreach ($data as $key => $value) {
                $value = mysqli_real_escape_string($db, $value);
                $updates[] = "{$key} = '{$value}'";
            }
            
            $sql = "UPDATE supervisors SET " . implode(', ', $updates) . " WHERE id = {$userId}";
            mysqli_query($db, $sql);
            
            $_SESSION['success'] = 'Profile updated successfully';
            $this->redirect('supervisor/profile');
        }
        
        $db = connectToDatabase();
        $sql = "SELECT * FROM supervisors WHERE id = {$userId}";
        $result = mysqli_query($db, $sql);
        $supervisor = mysqli_fetch_assoc($result);
        
        $this->view('supervisor.edit-profile', ['supervisor' => $supervisor]);
    }

    /**
     * View notifications
     */
    public function notifications()
    {
        $userId = $this->auth->getUserId();
        
        $notificationModel = new Notification();
        $notifications = $notificationModel->getByUser($userId, 'supervisor');
        
        $this->view('supervisor.notifications', ['notifications' => $notifications]);
    }

    /**
     * Mark notification as read
     */
    public function markNotificationRead()
    {
        if (isset($_POST['notification_id'])) {
            $notificationModel = new Notification();
            $notificationModel->markAsRead($_POST['notification_id']);
        }
        
        $this->redirect('supervisor/notifications');
    }
}
