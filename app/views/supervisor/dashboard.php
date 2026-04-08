<?php 
$title = 'Supervisor Dashboard';
$pageTitle = 'Supervisor Dashboard';
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
    <div class="col-md-4">
        <div class="card stat-card blue">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">My Students</h6>
                    <h3 class="mb-0"><?php echo count($assignedStudents); ?></h3>
                </div>
                <i class="fas fa-user-graduate fa-3x opacity-50"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card stat-card orange">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">Pending Logbooks</h6>
                    <h3 class="mb-0"><?php echo $pendingLogbooks; ?></h3>
                </div>
                <i class="fas fa-book fa-3x opacity-50"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card stat-card green">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">Pending Reports</h6>
                    <h3 class="mb-0"><?php echo $pendingReports; ?></h3>
                </div>
                <i class="fas fa-file-alt fa-3x opacity-50"></i>
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
                    <div class="col-md-4 mb-2">
                        <a href="<?php echo BASE_URL; ?>supervisor/students" class="btn btn-primary w-100">
                            <i class="fas fa-users me-2"></i> View My Students
                        </a>
                    </div>
                    <div class="col-md-4 mb-2">
                        <a href="<?php echo BASE_URL; ?>supervisor/logbooks" class="btn btn-info w-100">
                            <i class="fas fa-book me-2"></i> Review Logbooks
                        </a>
                    </div>
                    <div class="col-md-4 mb-2">
                        <a href="<?php echo BASE_URL; ?>supervisor/reports" class="btn btn-success w-100">
                            <i class="fas fa-file-alt me-2"></i> Review Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assigned Students -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-user-graduate me-2"></i> Assigned Students
            </div>
            <div class="card-body">
                <?php if (count($assignedStudents) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Student Number</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Program</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($assignedStudents as $student): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($student['student_number']); ?></td>
                                        <td><?php echo htmlspecialchars($student['full_name']); ?></td>
                                        <td><?php echo htmlspecialchars($student['email']); ?></td>
                                        <td><?php echo htmlspecialchars($student['program']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $student['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                                <?php echo htmlspecialchars($student['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?php echo BASE_URL; ?>supervisor/view-student?id=<?php echo $student['id']; ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                        <p>No students assigned to you yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>
