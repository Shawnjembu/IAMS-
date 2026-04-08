<?php

require_once '../core/Controller.php';
require_once '../core/Auth.php';

/**
 * OrganizationController
 * Handles organization-specific functionality
 */
class OrganizationController extends Controller
{
    private $auth;

    public function __construct($params = [])
    {
        parent::__construct($params);
        $this->auth = new Auth();
        
        // Check if user is logged in and is an organization
        if (!$this->auth->isLoggedIn() || !$this->auth->hasRole('organization')) {
            $this->redirect('auth/login');
        }
    }

    /**
     * Organization dashboard
     */
    public function dashboard()
    {
        $userId = $this->auth->getUserId();
        $organization = new Organization();
        $orgData = $organization->getById($userId);
        
        // Check if organization is approved
        if ($orgData['status'] !== 'approved') {
            $_SESSION['warning'] = 'Your account is pending approval. You cannot manage students until approved.';
        }
        
        // Get applications
        $applicationModel = new Application();
        $applications = $applicationModel->getByOrganization($userId);
        $pendingApplications = array_filter($applications, function($app) {
            return $app['status'] === 'pending';
        });
        
        // Get placements
        $placementModel = new Placement();
        $placements = $placementModel->getByOrganization($userId);
        
        // Get notifications
        $notificationModel = new Notification();
        $notifications = $notificationModel->getUnread($userId, 'organization');
        
        $this->view('organization.dashboard', [
            'organization' => $orgData,
            'applications' => $applications,
            'pendingApplications' => $pendingApplications,
            'placements' => $placements,
            'notifications' => $notifications
        ]);
    }

    /**
     * View profile
     */
    public function profile()
    {
        $userId = $this->auth->getUserId();
        $organization = new Organization();
        $orgData = $organization->getById($userId);
        
        $this->view('organization.profile', ['organization' => $orgData]);
    }

    /**
     * Edit profile
     */
    public function editProfile()
    {
        $userId = $this->auth->getUserId();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'organization_name' => $_POST['organization_name'] ?? '',
                'address' => $_POST['address'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'contact_person' => $_POST['contact_person'] ?? '',
                'industry_type' => $_POST['industry_type'] ?? '',
                'description' => $_POST['description'] ?? ''
            ];
            
            $organization = new Organization();
            $organization->updateProfile($userId, $data);
            
            $_SESSION['success'] = 'Profile updated successfully';
            $this->redirect('organization/profile');
        }
        
        $organization = new Organization();
        $orgData = $organization->getById($userId);
        
        $this->view('organization.edit-profile', ['organization' => $orgData]);
    }

    /**
     * View applications
     */
    public function applications()
    {
        $userId = $this->auth->getUserId();
        
        $applicationModel = new Application();
        $applications = $applicationModel->getByOrganization($userId);
        
        $this->view('organization.applications', ['applications' => $applications]);
    }

    /**
     * Process application (accept/reject)
     */
    public function processApplication()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $applicationId = $_POST['application_id'] ?? 0;
            $action = $_POST['action'] ?? '';
            
            $applicationModel = new Application();
            $application = $applicationModel->getWithDetails($applicationId);
            
            if ($application && $application['organization_id'] == $this->auth->getUserId()) {
                if ($action === 'accept') {
                    $applicationModel->updateStatus($applicationId, 'accepted');
                    
                    // Update student organization assignment
                    $student = new Student();
                    $student->updateProfile($application['student_id'], [
                        'organization_id' => $application['organization_id'],
                        'status' => 'approved'
                    ]);
                    
                    // Create placement
                    $placementModel = new Placement();
                    $placementModel->insert([
                        'student_id' => $application['student_id'],
                        'organization_id' => $application['organization_id'],
                        'status' => 'approved',
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                    
                    // Send notification to student
                    $notificationModel = new Notification();
                    $notificationModel->send($application['student_id'], 'student',
                        'Application Accepted',
                        'Your application has been accepted! You can now start your industrial attachment.',
                        'success');
                    
                    $_SESSION['success'] = 'Application accepted';
                } elseif ($action === 'reject') {
                    $applicationModel->updateStatus($applicationId, 'rejected');
                    
                    // Send notification to student
                    $notificationModel = new Notification();
                    $notificationModel->send($application['student_id'], 'student',
                        'Application Rejected',
                        'Your application has been rejected. Please apply to other organizations.',
                        'error');
                    
                    $_SESSION['success'] = 'Application rejected';
                }
            }
        }
        
        $this->redirect('organization/applications');
    }

    /**
     * View assigned students
     */
    public function students()
    {
        $userId = $this->auth->getUserId();
        
        $placementModel = new Placement();
        $placements = $placementModel->getByOrganization($userId);
        
        $this->view('organization.students', ['placements' => $placements]);
    }

    /**
     * View notifications
     */
    public function notifications()
    {
        $userId = $this->auth->getUserId();
        
        $notificationModel = new Notification();
        $notifications = $notificationModel->getByUser($userId, 'organization');
        
        $this->view('organization.notifications', ['notifications' => $notifications]);
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
        
        $this->redirect('organization/notifications');
    }
}
