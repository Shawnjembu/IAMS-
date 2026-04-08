<?php 
$title = 'Coordinator Dashboard';
$pageTitle = 'Coordinator Dashboard';
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

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card blue">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">Total Students</h6>
                    <h3 class="mb-0"><?php echo $stats['total_students']; ?></h3>
                </div>
                <i class="fas fa-user-graduate fa-3x opacity-50"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card green">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">Organizations</h6>
                    <h3 class="mb-0"><?php echo $stats['total_organizations']; ?></h3>
                </div>
                <i class="fas fa-building fa-3x opacity-50"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card orange">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">Active Placements</h6>
                    <h3 class="mb-0"><?php echo $stats['active_placements']; ?></h3>
                </div>
                <i class="fas fa-briefcase fa-3x opacity-50"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card stat-card purple">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">Pending Actions</h6>
                    <h3 class="mb-0"><?php echo $stats['pending_placements'] + $stats['pending_logbooks'] + $stats['pending_reports']; ?></h3>
                </div>
                <i class="fas fa-clock fa-3x opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-bolt me-2"></i> Quick Actions
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <a href="<?php echo BASE_URL; ?>coordinator/create-placement" class="btn btn-primary w-100">
                            <i class="fas fa-plus me-2"></i> Create Placement
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="<?php echo BASE_URL; ?>coordinator/organizations" class="btn btn-success w-100">
                            <i class="fas fa-building me-2"></i> Manage Organizations
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="<?php echo BASE_URL; ?>coordinator/placements" class="btn btn-info w-100">
                            <i class="fas fa-clipboard-list me-2"></i> View Placements
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="<?php echo BASE_URL; ?>coordinator/analytics" class="btn btn-warning w-100">
                            <i class="fas fa-chart-bar me-2"></i> Reports & Analytics
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pending Items -->
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-clock me-2"></i> Pending Placements</span>
                <span class="badge bg-warning"><?php echo $stats['pending_placements']; ?></span>
            </div>
            <div class="card-body">
                <a href="<?php echo BASE_URL; ?>coordinator/placements" class="btn btn-sm btn-primary">
                    View All
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-book me-2"></i> Pending Logbooks</span>
                <span class="badge bg-info"><?php echo $stats['pending_logbooks']; ?></span>
            </div>
            <div class="card-body">
                <a href="<?php echo BASE_URL; ?>coordinator/logbooks" class="btn btn-sm btn-primary">
                    View All
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-file-alt me-2"></i> Pending Reports</span>
                <span class="badge bg-success"><?php echo $stats['pending_reports']; ?></span>
            </div>
            <div class="card-body">
                <a href="<?php echo BASE_URL; ?>coordinator/pendingReports" class="btn btn-sm btn-primary">
                    View All
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>
