<?php
$title = 'Placements';
$pageTitle = 'Placements';
require_once 'layouts/header.php';
?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-clipboard-list me-2"></i> Placements (<?php echo count($placements ?? []); ?>)</span>
        <a href="<?php echo BASE_URL; ?>coordinator/create-placement" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Create Placement
        </a>
    </div>
    <div class="card-body">
        <?php if (empty($placements)): ?>
            <div class="text-center py-5">
                <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                <h5>No Placements Yet</h5>
                <p class="text-muted">No placements have been created.</p>
                <a href="<?php echo BASE_URL; ?>coordinator/create-placement" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Create First Placement
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Organization</th>
                            <th>Supervisor</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($placements as $i => $pl): ?>
                            <tr>
                                <td><?php echo $i + 1; ?></td>
                                <td><?php echo htmlspecialchars($pl['student_name'] ?? $pl['full_name'] ?? '—'); ?></td>
                                <td><?php echo htmlspecialchars($pl['organization_name'] ?? '—'); ?></td>
                                <td><?php echo htmlspecialchars($pl['supervisor_name'] ?? '—'); ?></td>
                                <td><?php echo !empty($pl['start_date']) ? date('M d, Y', strtotime($pl['start_date'])) : '—'; ?></td>
                                <td><?php echo !empty($pl['end_date']) ? date('M d, Y', strtotime($pl['end_date'])) : '—'; ?></td>
                                <td>
                                    <?php
                                    $badges = ['pending' => 'warning', 'approved' => 'info', 'active' => 'success', 'completed' => 'primary', 'rejected' => 'danger', 'cancelled' => 'secondary'];
                                    $st = $pl['status'] ?? 'pending';
                                    ?>
                                    <span class="badge bg-<?php echo $badges[$st] ?? 'secondary'; ?>">
                                        <?php echo ucfirst($st); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>
