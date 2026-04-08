<?php
$title = 'My Applications';
$pageTitle = 'My Applications';
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
        <span><i class="fas fa-paper-plane me-2"></i> My Applications</span>
        <a href="<?php echo BASE_URL; ?>student/organizations" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Apply to Organization
        </a>
    </div>
    <div class="card-body">
        <?php if (empty($applications)): ?>
            <div class="text-center py-5">
                <i class="fas fa-paper-plane fa-4x text-muted mb-3"></i>
                <h5>No Applications Yet</h5>
                <p class="text-muted">You have not applied to any organization.</p>
                <a href="<?php echo BASE_URL; ?>student/organizations" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i> Browse Organizations
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Organization</th>
                            <th>Industry</th>
                            <th>Applied On</th>
                            <th>Status</th>
                            <th>Response Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $i => $app): ?>
                            <tr>
                                <td><?php echo $i + 1; ?></td>
                                <td><?php echo htmlspecialchars($app['organization_name'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($app['industry_type'] ?? '—'); ?></td>
                                <td><?php echo !empty($app['created_at']) ? date('M d, Y', strtotime($app['created_at'])) : '—'; ?></td>
                                <td>
                                    <?php
                                    $badges = ['pending' => 'warning', 'accepted' => 'success', 'rejected' => 'danger', 'withdrawn' => 'secondary'];
                                    $st = $app['status'] ?? 'pending';
                                    ?>
                                    <span class="badge bg-<?php echo $badges[$st] ?? 'secondary'; ?>">
                                        <?php echo ucfirst($st); ?>
                                    </span>
                                </td>
                                <td><?php echo !empty($app['response_date']) ? date('M d, Y', strtotime($app['response_date'])) : '—'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>
