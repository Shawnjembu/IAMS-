<?php 
$title = 'Student Dashboard';
$pageTitle = 'Student Dashboard';
require_once 'layouts/header.php'; 
?>

<!-- Alerts -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['warning'])): ?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['warning']; unset($_SESSION['warning']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card blue">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">Welcome</h6>
                    <h3 class="mb-0"><?php echo htmlspecialchars($student['full_name'] ?? 'Student'); ?></h3>
                </div>
                <i class="fas fa-user-graduate fa-3x opacity-50"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card green">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">Logbooks</h6>
                    <h3 class="mb-0"><?php echo $logbookStats['total'] ?? 0; ?></h3>
                </div>
                <i class="fas fa-book fa-3x opacity-50"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card orange">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">Reports</h6>
                    <h3 class="mb-0"><?php echo !empty($latestReport) ? '1' : '0'; ?></h3>
                </div>
                <i class="fas fa-file-alt fa-3x opacity-50"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card purple">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">Notifications</h6>
                    <h3 class="mb-0"><?php echo count($notifications); ?></h3>
                </div>
                <i class="fas fa-bell fa-3x opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="row">
    <!-- Profile Summary -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-user me-2"></i> Profile Summary
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <div class="avatar-circle mx-auto mb-3">
                        <i class="fas fa-user-graduate fa-4x text-primary"></i>
                    </div>
                    <h5><?php echo htmlspecialchars($student['full_name'] ?? ''); ?></h5>
                    <p class="text-muted"><?php echo htmlspecialchars($student['student_number'] ?? ''); ?></p>
                </div>
                
                <table class="table table-sm">
                    <tr>
                        <td><strong>Email:</strong></td>
                        <td><?php echo htmlspecialchars($student['email'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Phone:</strong></td>
                        <td><?php echo htmlspecialchars($student['phone'] ?? 'Not set'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Program:</strong></td>
                        <td><?php echo htmlspecialchars($student['program'] ?? 'Not set'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Year:</strong></td>
                        <td><?php echo htmlspecialchars($student['year'] ?? ''); ?></td>
                    </tr>
                </table>
                
                <a href="<?php echo BASE_URL; ?>student/profile" class="btn btn-primary btn-sm w-100">
                    <i class="fas fa-edit me-2"></i> Edit Profile
                </a>
            </div>
        </div>
    </div>
    
    <!-- Placement Status -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-briefcase me-2"></i> Placement Status
            </div>
            <div class="card-body">
                <?php if ($placement): ?>
                    <div class="text-center mb-3">
                        <?php 
                        $statusClass = [
                            'pending' => 'warning',
                            'approved' => 'info',
                            'active' => 'success',
                            'completed' => 'primary'
                        ];
                        $status = $placement['status'] ?? 'pending';
                        ?>
                        <span class="badge bg-<?php echo $statusClass[$status] ?? 'secondary'; ?> p-2">
                            <?php echo strtoupper($status); ?>
                        </span>
                    </div>
                    
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Organization:</strong></td>
                            <td><?php echo htmlspecialchars($placement['organization_name'] ?? 'Not assigned'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Address:</strong></td>
                            <td><?php echo htmlspecialchars($placement['org_address'] ?? ''); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Supervisor:</strong></td>
                            <td><?php echo htmlspecialchars($placement['supervisor_name'] ?? 'Not assigned'); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Start Date:</strong></td>
                            <td><?php echo !empty($placement['start_date']) ? date('M d, Y', strtotime($placement['start_date'])) : 'Not set'; ?></td>
                        </tr>
                    </table>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                        <p>You have not been placed yet.</p>
                        <a href="<?php echo BASE_URL; ?>student/organizations" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i> Browse Organizations
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-bolt me-2"></i> Quick Actions
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <?php if ($placement && $placement['status'] === 'active'): ?>
                        <a href="<?php echo BASE_URL; ?>student/createLogbook" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-2"></i> Submit Weekly Logbook
                        </a>
                        <a href="<?php echo BASE_URL; ?>student/uploadReport" class="btn btn-success">
                            <i class="fas fa-upload me-2"></i> Upload Final Report
                        </a>
                    <?php endif; ?>
                    
                    <a href="<?php echo BASE_URL; ?>student/logbooks" class="btn btn-info text-white">
                        <i class="fas fa-book me-2"></i> View My Logbooks
                    </a>
                    
                    <a href="<?php echo BASE_URL; ?>student/reports" class="btn btn-warning text-white">
                        <i class="fas fa-file-alt me-2"></i> View My Reports
                    </a>
                    
                    <a href="<?php echo BASE_URL; ?>student/notifications" class="btn btn-secondary">
                        <i class="fas fa-bell me-2"></i> Notifications
                        <?php if (count($notifications) > 0): ?>
                            <span class="badge bg-danger ms-2"><?php echo count($notifications); ?></span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>
