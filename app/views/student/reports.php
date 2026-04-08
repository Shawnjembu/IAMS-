<?php
$title = 'My Reports';
$pageTitle = 'My Reports';
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
        <span><i class="fas fa-file-alt me-2"></i> My Reports</span>
        <a href="<?php echo BASE_URL; ?>student/upload-report" class="btn btn-primary btn-sm">
            <i class="fas fa-upload me-1"></i> Upload Report
        </a>
    </div>
    <div class="card-body">
        <?php if (empty($reports)): ?>
            <div class="text-center py-5">
                <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                <h5>No Reports Uploaded</h5>
                <p class="text-muted">You have not uploaded any reports yet.</p>
                <a href="<?php echo BASE_URL; ?>student/upload-report" class="btn btn-primary">
                    <i class="fas fa-upload me-2"></i> Upload Your Report
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>File Name</th>
                            <th>File Size</th>
                            <th>Submitted</th>
                            <th>Status</th>
                            <th>Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reports as $i => $rpt): ?>
                            <tr>
                                <td><?php echo $i + 1; ?></td>
                                <td><?php echo htmlspecialchars($rpt['title'] ?? 'Final Report'); ?></td>
                                <td>
                                    <i class="fas fa-file-pdf text-danger me-1"></i>
                                    <?php echo htmlspecialchars($rpt['file_name'] ?? ''); ?>
                                </td>
                                <td><?php echo !empty($rpt['file_size']) ? round($rpt['file_size'] / 1024, 1) . ' KB' : '—'; ?></td>
                                <td><?php echo !empty($rpt['created_at']) ? date('M d, Y', strtotime($rpt['created_at'])) : '—'; ?></td>
                                <td>
                                    <?php
                                    $badges = ['submitted' => 'info', 'reviewed' => 'primary', 'approved' => 'success', 'rejected' => 'danger', 'pending' => 'secondary'];
                                    $st = $rpt['status'] ?? 'submitted';
                                    ?>
                                    <span class="badge bg-<?php echo $badges[$st] ?? 'secondary'; ?>">
                                        <?php echo ucfirst($st); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars(substr($rpt['description'] ?? '', 0, 60)) ?: '—'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>
