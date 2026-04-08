<?php
$title = 'My Logbooks';
$pageTitle = 'My Logbooks';
require_once 'layouts/header.php';
?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-book me-2"></i> My Logbooks</span>
        <a href="<?php echo BASE_URL; ?>student/create-logbook" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> New Logbook Entry
        </a>
    </div>
    <div class="card-body">
        <?php if (empty($logbooks)): ?>
            <div class="text-center py-5">
                <i class="fas fa-book fa-4x text-muted mb-3"></i>
                <h5>No Logbook Entries</h5>
                <p class="text-muted">You have not submitted any logbook entries yet.</p>
                <a href="<?php echo BASE_URL; ?>student/create-logbook" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Create First Entry
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Week #</th>
                            <th>Week Period</th>
                            <th>Activities Summary</th>
                            <th>Status</th>
                            <th>Reviewed</th>
                            <th>Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logbooks as $lb): ?>
                            <tr>
                                <td><strong>Week <?php echo htmlspecialchars($lb['week_number']); ?></strong></td>
                                <td>
                                    <?php echo !empty($lb['week_start']) ? date('M d', strtotime($lb['week_start'])) : ''; ?>
                                    —
                                    <?php echo !empty($lb['week_end']) ? date('M d, Y', strtotime($lb['week_end'])) : ''; ?>
                                </td>
                                <td><?php echo htmlspecialchars(substr($lb['activities'] ?? '', 0, 80)) . (strlen($lb['activities'] ?? '') > 80 ? '...' : ''); ?></td>
                                <td>
                                    <?php
                                    $badges = ['pending' => 'secondary', 'submitted' => 'info', 'reviewed' => 'primary', 'approved' => 'success', 'rejected' => 'danger'];
                                    $st = $lb['status'] ?? 'submitted';
                                    ?>
                                    <span class="badge bg-<?php echo $badges[$st] ?? 'secondary'; ?>">
                                        <?php echo ucfirst($st); ?>
                                    </span>
                                </td>
                                <td><?php echo !empty($lb['reviewed_at']) ? date('M d, Y', strtotime($lb['reviewed_at'])) : '—'; ?></td>
                                <td><?php echo htmlspecialchars(substr($lb['comments'] ?? '', 0, 50)) ?: '—'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>
