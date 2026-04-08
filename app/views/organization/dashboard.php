<?php 
$title = 'Organization Dashboard';
$pageTitle = 'Organization Dashboard';
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
    <div class="col-md-4">
        <div class="card stat-card blue">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">Welcome</h6>
                    <h3 class="mb-0"><?php echo htmlspecialchars($organization['organization_name'] ?? 'Organization'); ?></h3>
                </div>
                <i class="fas fa-building fa-3x opacity-50"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card stat-card orange">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">Pending Applications</h6>
                    <h3 class="mb-0"><?php echo count($pendingApplications); ?></h3>
                </div>
                <i class="fas fa-users fa-3x opacity-50"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card stat-card green">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">Assigned Students</h6>
                    <h3 class="mb-0"><?php echo count($placements); ?></h3>
                </div>
                <i class="fas fa-user-graduate fa-3x opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<!-- Status Check -->
<?php if ($organization['status'] !== 'approved'): ?>
    <div class="alert alert-warning mb-4">
        <i class="fas fa-exclamation-triangle me-2"></i>
        Your organization account is <strong><?php echo $organization['status']; ?></strong>. 
        You cannot manage students until your account is approved by the coordinator.
    </div>
<?php endif; ?>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-bolt me-2"></i> Quick Actions
            </div>
            <div class="card-body">
                <?php if ($organization['status'] === 'approved'): ?>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <a href="<?php echo BASE_URL; ?>organization/applications" class="btn btn-primary w-100">
                                <i class="fas fa-users me-2"></i> View Applications
                                <?php if (count($pendingApplications) > 0): ?>
                                    <span class="badge bg-danger ms-2"><?php echo count($pendingApplications); ?></span>
                                <?php endif; ?>
                            </a>
                        </div>
                        <div class="col-md-4 mb-2">
                            <a href="<?php echo BASE_URL; ?>organization/students" class="btn btn-info w-100">
                                <i class="fas fa-user-graduate me-2"></i> My Students
                            </a>
                        </div>
                        <div class="col-md-4 mb-2">
                            <a href="<?php echo BASE_URL; ?>organization/profile" class="btn btn-secondary w-100">
                                <i class="fas fa-building me-2"></i> Edit Profile
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Please wait for your account to be approved.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Pending Applications -->
<?php if ($organization['status'] === 'approved' && count($pendingApplications) > 0): ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-clock me-2"></i> Pending Applications
                <span class="badge bg-warning float-end"><?php echo count($pendingApplications); ?></span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Student Number</th>
                                <th>Name</th>
                                <th>Program</th>
                                <th>Applied Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendingApplications as $app): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($app['student_number']); ?></td>
                                    <td><?php echo htmlspecialchars($app['student_name']); ?></td>
                                    <td><?php echo htmlspecialchars($app['program']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($app['created_at'])); ?></td>
                                    <td>
                                        <form method="POST" action="<?php echo BASE_URL; ?>organization/process-application" class="d-inline">
                                            <input type="hidden" name="application_id" value="<?php echo $app['id']; ?>">
                                            <button type="submit" name="action" value="accept" class="btn btn-sm btn-success">
                                                <i class="fas fa-check"></i> Accept
                                            </button>
                                            <button type="submit" name="action" value="reject" class="btn btn-sm btn-danger">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php require_once 'layouts/footer.php'; ?>
