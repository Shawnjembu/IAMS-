<?php

require_once '../core/Controller.php';
require_once '../core/Auth.php';

/**
 * CoordinatorController
 * Handles coordinator-specific functionality
 */
class CoordinatorController extends Controller
{
    private $auth;

    public function __construct($params = [])
    {
        parent::__construct($params);
        $this->auth = new Auth();
        
        // Check if user is logged in and is a coordinator
        if (!$this->auth->isLoggedIn() || !$this->auth->hasRole('coordinator')) {
            $this->redirect('auth/login');
        }
    }

    /**
     * Coordinator dashboard
     */
    public function dashboard()
    {
        $userId = $this->auth->getUserId();
        
        // Get statistics
        $db = connectToDatabase();
        
        $stats = [
            'total_students' => 0,
            'total_organizations' => 0,
            'pending_placements' => 0,
            'active_placements' => 0,
            'completed_placements' => 0,
            'pending_logbooks' => 0,
            'pending_reports' => 0
        ];
        
        // Count students
        $result = mysqli_query($db, "SELECT COUNT(*) as count FROM students");
        if ($result) $stats['total_students'] = mysqli_fetch_assoc($result)['count'];
        
        // Count approved organizations
        $result = mysqli_query($db, "SELECT COUNT(*) as count FROM organizations WHERE status = 'approved'");
        if ($result) $stats['total_organizations'] = mysqli_fetch_assoc($result)['count'];
        
        // Count pending placements
        $result = mysqli_query($db, "SELECT COUNT(*) as count FROM placements WHERE status = 'pending'");
        if ($result) $stats['pending_placements'] = mysqli_fetch_assoc($result)['count'];
        
        // Count active placements
        $result = mysqli_query($db, "SELECT COUNT(*) as count FROM placements WHERE status = 'active'");
        if ($result) $stats['active_placements'] = mysqli_fetch_assoc($result)['count'];
        
        // Count completed placements
        $result = mysqli_query($db, "SELECT COUNT(*) as count FROM placements WHERE status = 'completed'");
        if ($result) $stats['completed_placements'] = mysqli_fetch_assoc($result)['count'];
        
        // Count pending logbooks
        $result = mysqli_query($db, "SELECT COUNT(*) as count FROM logbooks WHERE status = 'submitted'");
        if ($result) $stats['pending_logbooks'] = mysqli_fetch_assoc($result)['count'];
        
        // Count pending reports
        $result = mysqli_query($db, "SELECT COUNT(*) as count FROM reports WHERE status = 'submitted'");
        if ($result) $stats['pending_reports'] = mysqli_fetch_assoc($result)['count'];
        
        // Get notifications
        $notificationModel = new Notification();
        $notifications = $notificationModel->getUnread($userId, 'coordinator');
        
        $this->view('coordinator.dashboard', [
            'stats' => $stats,
            'notifications' => $notifications
        ]);
    }

    /**
     * View all students
     */
    public function students()
    {
        $student = new Student();
        $students = $student->getAllWithOrganizations();
        
        $this->view('coordinator.students', ['students' => $students]);
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
        
        $this->view('coordinator.view-student', [
            'student' => $studentData,
            'placement' => !empty($placements) ? $placements[0] : null,
            'logbooks' => $logbooks,
            'reports' => $reports,
            'evaluations' => $evaluations
        ]);
    }

    /**
     * View all organizations
     */
    public function organizations()
    {
        $organization = new Organization();
        $organizations = $organization->getAllWithStudentCounts();
        
        $this->view('coordinator.organizations', ['organizations' => $organizations]);
    }

    /**
     * Approve/reject organization
     */
    public function processOrganization()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orgId = $_POST['organization_id'] ?? 0;
            $action = $_POST['action'] ?? '';
            
            $organization = new Organization();
            
            if ($action === 'approve') {
                $organization->updateStatus($orgId, 'approved');
                
                // Send notification
                $notificationModel = new Notification();
                $notificationModel->send($orgId, 'organization',
                    'Organization Approved',
                    'Your organization has been approved! You can now accept students.',
                    'success');
                
                $_SESSION['success'] = 'Organization approved';
            } elseif ($action === 'reject') {
                $organization->updateStatus($orgId, 'rejected');
                
                $_SESSION['success'] = 'Organization rejected';
            }
        }
        
        $this->redirect('coordinator/organizations');
    }

    /**
     * View all placements
     */
    public function placements()
    {
        $placementModel = new Placement();
        $placements = $placementModel->getAllWithDetails();
        
        $this->view('coordinator.placements', ['placements' => $placements]);
    }

    /**
     * Create placement
     */
    public function createPlacement()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'student_id' => $_POST['student_id'] ?? 0,
                'organization_id' => $_POST['organization_id'] ?? 0,
                'supervisor_id' => $_POST['supervisor_id'] ?? null,
                'start_date' => $_POST['start_date'] ?? date('Y-m-d'),
                'end_date' => $_POST['end_date'] ?? '',
                'status' => 'active',
                'comments' => $_POST['comments'] ?? '',
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $placementModel = new Placement();
            $placementId = $placementModel->insert($data);
            
            if ($placementId) {
                // Update student status
                $student = new Student();
                $student->updateProfile($data['student_id'], [
                    'organization_id' => $data['organization_id'],
                    'supervisor_id' => $data['supervisor_id'],
                    'status' => 'active'
                ]);
                
                // Send notifications
                $notificationModel = new Notification();
                
                // Notify student
                $notificationModel->send($data['student_id'], 'student',
                    'Placement Assigned',
                    'You have been assigned to a new placement. Check your dashboard for details.',
                    'success');
                
                // Notify organization
                $notificationModel->send($data['organization_id'], 'organization',
                    'New Student Assigned',
                    'A new student has been assigned to your organization.',
                    'info');
                
                $_SESSION['success'] = 'Placement created successfully';
            } else {
                $_SESSION['error'] = 'Failed to create placement';
            }
            
            $this->redirect('coordinator/placements');
        }
        
        // Get data for forms
        $student = new Student();
        $students = $student->getAll();
        
        $organization = new Organization();
        $organizations = $organization->getPending(); // Get pending organizations
        
        $this->view('coordinator.create-placement', [
            'students' => $students,
            'organizations' => $organizations
        ]);
    }

    /**
     * View pending logbooks
     */
    public function logbooks()
    {
        $logbookModel = new Logbook();
        $logbooks = $logbookModel->getAllWithStudent();
        
        $this->view('coordinator.logbooks', ['logbooks' => $logbooks]);
    }

    /**
     * View pending reports
     */
    public function pendingReports()
    {
        $reportModel = new Report();
        $reports = $reportModel->getAllWithStudent();
        
        $this->view('coordinator.reports', ['reports' => $reports]);
    }

    /**
     * View evaluations
     */
    public function evaluations()
    {
        $evaluationModel = new Evaluation();
        $evaluations = $evaluationModel->getAllWithDetails();
        
        $this->view('coordinator.evaluations', ['evaluations' => $evaluations]);
    }

    /**
     * View notifications
     */
    public function notifications()
    {
        $userId = $this->auth->getUserId();
        
        $notificationModel = new Notification();
        $notifications = $notificationModel->getByUser($userId, 'coordinator');
        
        $this->view('coordinator.notifications', ['notifications' => $notifications]);
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
        
        $this->redirect('coordinator/notifications');
    }

    /**
     * Reports and analytics
     */
    public function analytics()
    {
        $db = connectToDatabase();
        
        // Get placement statistics by organization
        $sql = "SELECT o.organization_name, 
                COUNT(p.id) as placement_count,
                SUM(CASE WHEN p.status = 'active' THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN p.status = 'completed' THEN 1 ELSE 0 END) as completed
                FROM organizations o
                LEFT JOIN placements p ON o.id = p.organization_id
                WHERE o.status = 'approved'
                GROUP BY o.id
                ORDER BY placement_count DESC";
        
        $result = mysqli_query($db, $sql);
        $orgStats = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $orgStats[] = $row;
        }
        
        // Get evaluation statistics
        $evaluationModel = new Evaluation();
        $evalStats = $evaluationModel->getStatistics();
        $avgScores = $evaluationModel->getAverageScores();
        
        $this->view('coordinator.reports', [
            'orgStats' => $orgStats,
            'evalStats' => $evalStats,
            'avgScores' => $avgScores
        ]);
    }
}
