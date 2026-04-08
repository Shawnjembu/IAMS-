<?php
$title = 'My Students';
$pageTitle = 'My Assigned Students';
require_once 'layouts/header.php';
?>

<div class="card">
    <div class="card-header">
        <i class="fas fa-user-graduate me-2"></i> My Assigned Students (<?php echo count($students ?? []); ?>)
    </div>
    <div class="card-body">
        <?php if (empty($students)): ?>
            <div class="text-center py-5">
                <i class="fas fa-user-graduate fa-4x text-muted mb-3"></i>
                <h5>No Students Assigned</h5>
                <p class="text-muted">No students are currently assigned to you.</p>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($students as $s): ?>
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-user-graduate fa-2x text-primary me-3"></i>
                                    <div>
                                        <h6 class="mb-0"><?php echo htmlspecialchars($s['full_name'] ?? ''); ?></h6>
                                        <small class="text-muted"><?php echo htmlspecialchars($s['student_number'] ?? ''); ?></small>
                                    </div>
                                </div>
                                <table class="table table-sm table-borderless mb-2">
                                    <tr><td><strong>Program:</strong></td><td><?php echo htmlspecialchars($s['program'] ?? '—'); ?></td></tr>
                                    <tr><td><strong>Email:</strong></td><td><?php echo htmlspecialchars($s['email'] ?? ''); ?></td></tr>
                                    <tr><td><strong>Status:</strong></td><td>
                                        <?php $badges=['pending'=>'warning','approved'=>'info','active'=>'success','completed'=>'primary']; $st=$s['status']??'active'; ?>
                                        <span class="badge bg-<?php echo $badges[$st]??'secondary'; ?>"><?php echo ucfirst($st); ?></span>
                                    </td></tr>
                                </table>
                                <div class="d-grid gap-1">
                                    <a href="<?php echo BASE_URL; ?>supervisor/view-student?id=<?php echo $s['id']; ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i> View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>
