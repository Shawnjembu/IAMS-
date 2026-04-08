<?php
$title = 'Logbooks';
$pageTitle = 'Student Logbooks';
require_once 'layouts/header.php';
?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <i class="fas fa-book me-2"></i> Logbooks (<?php echo count($logbooks ?? []); ?>)
    </div>
    <div class="card-body">
        <?php if (empty($logbooks)): ?>
            <div class="text-center py-5">
                <i class="fas fa-book fa-4x text-muted mb-3"></i>
                <h5>No Logbooks</h5>
                <p class="text-muted">No logbook entries from your students yet.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Week</th>
                            <th>Period</th>
                            <th>Activities</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logbooks as $i => $lb): ?>
                            <tr>
                                <td><?php echo $i + 1; ?></td>
                                <td><?php echo htmlspecialchars($lb['full_name'] ?? $lb['student_name'] ?? ''); ?></td>
                                <td>Week <?php echo htmlspecialchars($lb['week_number']); ?></td>
                                <td><?php echo !empty($lb['week_start']) ? date('M d', strtotime($lb['week_start'])) : ''; ?> — <?php echo !empty($lb['week_end']) ? date('M d, Y', strtotime($lb['week_end'])) : ''; ?></td>
                                <td><?php echo htmlspecialchars(substr($lb['activities'] ?? '', 0, 60)) . (strlen($lb['activities'] ?? '') > 60 ? '...' : ''); ?></td>
                                <td>
                                    <?php
                                    $badges = ['submitted' => 'info', 'approved' => 'success', 'rejected' => 'danger', 'reviewed' => 'primary', 'pending' => 'secondary'];
                                    $st = $lb['status'] ?? 'submitted';
                                    ?>
                                    <span class="badge bg-<?php echo $badges[$st] ?? 'secondary'; ?>">
                                        <?php echo ucfirst($st); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo BASE_URL; ?>supervisor/view-logbook?id=<?php echo $lb['id']; ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> Review
                                    </a>
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
