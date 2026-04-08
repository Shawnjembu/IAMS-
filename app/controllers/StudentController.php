<?php

require_once '../core/Controller.php';
require_once '../core/Auth.php';

/**
 * StudentController
 * Handles student-specific functionality
 */
class StudentController extends Controller
{
    private $auth;

    public function __construct($params = [])
    {
        parent::__construct($params);
        $this->auth = new Auth();
        
        // Check if user is logged in and is a student
        if (!$this->auth->isLoggedIn() || !$this->auth->hasRole('student')) {
            $this->redirect('auth/login');
        }
    }

    /**
     * Student dashboard
     */
    public function dashboard()
    {
        $userId = $this->auth->getUserId();
        $student = new Student();
        $studentData = $student->getById($userId);
        
        // Get placement information
        $placementModel = new Placement();
        $placements = $placementModel->getByStudent($userId);
        $placement = !empty($placements) ? $placements[0] : null;
        
        // Get logbook statistics
        $logbookModel = new Logbook();
        $logbookStats = $logbookModel->getStudentStatistics($userId);
        
        // Get latest report
        $reportModel = new Report();
        $latestReport = $reportModel->getLatestByStudent($userId);
        
        // Get notifications
        $notificationModel = new Notification();
        $notifications = $notificationModel->getUnread($userId, 'student');
        
        $this->view('student.dashboard', [
            'student' => $studentData,
            'placement' => $placement,
            'logbookStats' => $logbookStats,
            'latestReport' => $latestReport,
            'notifications' => $notifications
        ]);
    }

    /**
     * View profile
     */
    public function profile()
    {
        $userId = $this->auth->getUserId();
        $student = new Student();
        $studentData = $student->getById($userId);
        
        // Get placement information
        $placementModel = new Placement();
        $placements = $placementModel->getByStudent($userId);
        
        $this->view('student.profile', [
            'student' => $studentData,
            'placement' => !empty($placements) ? $placements[0] : null
        ]);
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
                'program' => $_POST['program'] ?? '',
                'year' => $_POST['year'] ?? '',
                'semester' => $_POST['semester'] ?? ''
            ];
            
            $student = new Student();
            $student->updateProfile($userId, $data);
            
            $_SESSION['success'] = 'Profile updated successfully';
            $this->redirect('student/profile');
        }
        
        $student = new Student();
        $studentData = $student->getById($userId);
        
        $this->view('student.edit-profile', ['student' => $studentData]);
    }

    /**
     * View available organizations
     */
    public function organizations()
    {
        $organization = new Organization();
        $organizations = $organization->getAllWithStudentCounts();
        
        // Filter only approved organizations
        $approvedOrgs = array_filter($organizations, function($org) {
            return $org['status'] === 'approved';
        });
        
        $this->view('student.organizations', ['organizations' => $approvedOrgs]);
    }

    /**
     * Apply to organization
     */
    public function apply()
    {
        $userId = $this->auth->getUserId();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $organizationId = $_POST['organization_id'] ?? 0;
            $coverLetter = $_POST['cover_letter'] ?? '';
            
            $applicationModel = new Application();
            
            // Check if already applied
            if ($applicationModel->hasApplied($userId, $organizationId)) {
                $_SESSION['error'] = 'You have already applied to this organization';
                $this->redirect('student/organizations');
            }
            
            $data = [
                'student_id' => $userId,
                'organization_id' => $organizationId,
                'cover_letter' => $coverLetter,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            if ($applicationModel->insert($data)) {
                $_SESSION['success'] = 'Application submitted successfully';
                
                // Send notification to organization
                $notificationModel = new Notification();
                $organization = new Organization();
                $org = $organization->getById($organizationId);
                if ($org) {
                    $notificationModel->send($organizationId, 'organization', 
                        'New Application', 
                        'You have a new student application. Please review.',
                        'info');
                }
            } else {
                $_SESSION['error'] = 'Failed to submit application';
            }
            
            $this->redirect('student/applications');
        }
        
        $this->redirect('student/organizations');
    }

    /**
     * View my applications
     */
    public function applications()
    {
        $userId = $this->auth->getUserId();
        
        $applicationModel = new Application();
        $applications = $applicationModel->getByStudent($userId);
        
        $this->view('student.applications', ['applications' => $applications]);
    }

    /**
     * View logbooks
     */
    public function logbooks()
    {
        $userId = $this->auth->getUserId();
        
        $logbookModel = new Logbook();
        $logbooks = $logbookModel->getByStudent($userId);
        
        $this->view('student.logbooks', ['logbooks' => $logbooks]);
    }

    /**
     * Create new logbook entry
     */
    public function createLogbook()
    {
        $userId = $this->auth->getUserId();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $logbookModel = new Logbook();
            $currentWeek = $logbookModel->getCurrentWeek($userId);
            
            $data = [
                'student_id' => $userId,
                'week_number' => $currentWeek,
                'week_start' => $_POST['week_start'] ?? date('Y-m-d'),
                'week_end' => $_POST['week_end'] ?? date('Y-m-d'),
                'activities' => $_POST['activities'] ?? '',
                'learning_outcomes' => $_POST['learning_outcomes'] ?? '',
                'challenges' => $_POST['challenges'] ?? '',
                'status' => 'submitted',
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            if ($logbookModel->insert($data)) {
                $_SESSION['success'] = 'Logbook submitted successfully';
                
                // Send notification to supervisor
                $placementModel = new Placement();
                $placements = $placementModel->getByStudent($userId);
                if (!empty($placements) && $placements[0]['supervisor_id']) {
                    $notificationModel = new Notification();
                    $notificationModel->send($placements[0]['supervisor_id'], 'supervisor',
                        'New Logbook Entry',
                        'A student has submitted a new logbook entry. Please review.',
                        'info');
                }
            } else {
                $_SESSION['error'] = 'Failed to submit logbook';
            }
            
            $this->redirect('student/logbooks');
        }
        
        $logbookModel = new Logbook();
        $currentWeek = $logbookModel->getCurrentWeek($userId);
        
        $this->view('student.create-logbook', ['currentWeek' => $currentWeek]);
    }

    /**
     * View reports
     */
    public function reports()
    {
        $userId = $this->auth->getUserId();
        
        $reportModel = new Report();
        $reports = $reportModel->getByStudent($userId);
        
        $this->view('student.reports', ['reports' => $reports]);
    }

    /**
     * Upload report
     */
    public function uploadReport()
    {
        $userId = $this->auth->getUserId();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_FILES['report_file']) && $_FILES['report_file']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['report_file'];
                
                // Validate file type
                $allowedTypes = ['application/pdf', 'application/msword', 
                                 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                 'application/zip', 'application/x-zip-compressed'];
                
                if (!in_array($file['type'], $allowedTypes)) {
                    $_SESSION['error'] = 'Invalid file type. Please upload PDF, DOC, DOCX, or ZIP.';
                    $this->redirect('student/reports');
                }
                
                // Validate file size (max 10MB)
                if ($file['size'] > 10 * 1024 * 1024) {
                    $_SESSION['error'] = 'File too large. Maximum size is 10MB';
                    $this->redirect('student/reports');
                }
                
                // Upload file
                $uploadDir = dirname(dirname(__DIR__)) . '/assets/uploads/reports/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileName = time() . '_' . basename($file['name']);
                $filePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($file['tmp_name'], $filePath)) {
                    $reportModel = new Report();
                    $data = [
                        'student_id' => $userId,
                        'title' => $_POST['title'] ?? 'Final Report',
                        'file_path' => 'assets/uploads/reports/' . $fileName,
                        'file_name' => $file['name'],
                        'file_size' => $file['size'],
                        'description' => $_POST['description'] ?? '',
                        'status' => 'submitted',
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    
                    if ($reportModel->insert($data)) {
                        $_SESSION['success'] = 'Report uploaded successfully';
                        
                        // Send notification to supervisor
                        $placementModel = new Placement();
                        $placements = $placementModel->getByStudent($userId);
                        if (!empty($placements) && $placements[0]['supervisor_id']) {
                            $notificationModel = new Notification();
                            $notificationModel->send($placements[0]['supervisor_id'], 'supervisor',
                                'New Report Uploaded',
                                'A student has uploaded their final report. Please review.',
                                'info');
                        }
                    } else {
                        $_SESSION['error'] = 'Failed to save report information';
                    }
                } else {
                    $_SESSION['error'] = 'Failed to upload file';
                }
            } else {
                $_SESSION['error'] = 'Please select a file to upload';
            }
            
            $this->redirect('student/reports');
        }
        
        $this->view('student.upload-report');
    }

    /**
     * View notifications
     */
    public function notifications()
    {
        $userId = $this->auth->getUserId();
        
        $notificationModel = new Notification();
        $notifications = $notificationModel->getByUser($userId, 'student');
        
        $this->view('student.notifications', ['notifications' => $notifications]);
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
        
        $this->redirect('student/notifications');
    }
}
