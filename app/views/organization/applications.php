<?php
$title = 'Student Applications';
$pageTitle = 'Student Applications';
require_once 'layouts/header.php';
?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <i class="fas fa-users me-2"></i> Student Applications
    </div>
    <div class="card-body">
        <?php if (empty($applications)): ?>
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <h5>No Applications</h5>
                <p class="text-muted">No students have applied to your organization yet.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Student No.</th>
                            <th>Program</th>
                            <th>Applied On</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applications as $i => $app): ?>
                            <tr>
                                <td><?php echo $i + 1; ?></td>
                                <td><?php echo htmlspecialchars($app['student_name'] ?? $app['full_name'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($app['student_number'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($app['program'] ?? '—'); ?></td>
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
                                <td>
                                    <?php if ($app['status'] === 'pending'): ?>
                                        <form method="POST" action="<?php echo BASE_URL; ?>organization/process-application" class="d-inline">
                                            <input type="hidden" name="application_id" value="<?php echo $app['id']; ?>">
                                            <input type="hidden" name="action" value="accept">
                                            <button type="submit" class="btn btn-success btn-sm"
                                                onclick="return confirm('Accept this student?')">
                                                <i class="fas fa-check"></i> Accept
                                            </button>
                                        </form>
                                        <form method="POST" action="<?php echo BASE_URL; ?>organization/process-application" class="d-inline ms-1">
                                            <input type="hidden" name="application_id" value="<?php echo $app['id']; ?>">
                                            <input type="hidden" name="action" value="reject">
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Reject this application?')">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted small">Processed</span>
                                    <?php endif; ?>
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
