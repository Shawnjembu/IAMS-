<?php
$title = 'Student Reports';
$pageTitle = 'Student Reports';
require_once 'layouts/header.php';
?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <i class="fas fa-file-alt me-2"></i> Student Reports (<?php echo count($reports ?? []); ?>)
    </div>
    <div class="card-body">
        <?php if (empty($reports)): ?>
            <div class="text-center py-5">
                <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                <h5>No Reports</h5>
                <p class="text-muted">Your students have not submitted any reports yet.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Student No.</th>
                            <th>Title</th>
                            <th>File</th>
                            <th>Submitted</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reports as $i => $rpt): ?>
                            <tr>
                                <td><?php echo $i + 1; ?></td>
                                <td><?php echo htmlspecialchars($rpt['student_name'] ?? $rpt['full_name'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($rpt['student_number'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($rpt['title'] ?? 'Final Report'); ?></td>
                                <td>
                                    <i class="fas fa-file-pdf text-danger me-1"></i>
                                    <?php echo htmlspecialchars($rpt['file_name'] ?? ''); ?>
                                </td>
                                <td><?php echo !empty($rpt['created_at']) ? date('M d, Y', strtotime($rpt['created_at'])) : '—'; ?></td>
                                <td>
                                    <?php
                                    $badges = ['submitted' => 'info', 'approved' => 'success', 'rejected' => 'danger', 'reviewed' => 'primary'];
                                    $st = $rpt['status'] ?? 'submitted';
                                    ?>
                                    <span class="badge bg-<?php echo $badges[$st] ?? 'secondary'; ?>">
                                        <?php echo ucfirst($st); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo BASE_URL; ?>supervisor/view-report?id=<?php echo $rpt['id']; ?>" class="btn btn-sm btn-outline-primary">
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
