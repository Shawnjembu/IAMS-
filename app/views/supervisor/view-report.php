<?php
$title = 'View Report';
$pageTitle = 'Student Report';
require_once 'layouts/header.php';
?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><?php echo htmlspecialchars($report['title'] ?? 'Final Report'); ?></h5>
    <a href="<?php echo BASE_URL; ?>supervisor/reports" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-file-alt me-2"></i> Report Details</div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="30%"><strong>Student:</strong></td>
                        <td><?php echo htmlspecialchars($report['full_name'] ?? $report['student_name'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Student No.:</strong></td>
                        <td><?php echo htmlspecialchars($report['student_number'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Program:</strong></td>
                        <td><?php echo htmlspecialchars($report['program'] ?? '—'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Report Title:</strong></td>
                        <td><?php echo htmlspecialchars($report['title'] ?? 'Final Report'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>File:</strong></td>
                        <td>
                            <i class="fas fa-file-pdf text-danger me-1"></i>
                            <?php echo htmlspecialchars($report['file_name'] ?? ''); ?>
                            <?php if (!empty($report['file_size'])): ?>
                                <small class="text-muted">(<?php echo round($report['file_size'] / 1024, 1); ?> KB)</small>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Submitted:</strong></td>
                        <td><?php echo !empty($report['created_at']) ? date('M d, Y H:i', strtotime($report['created_at'])) : '—'; ?></td>
                    </tr>
                </table>
                <?php if (!empty($report['description'])): ?>
                    <hr>
                    <h6>Description / Notes</h6>
                    <p class="text-muted"><?php echo nl2br(htmlspecialchars($report['description'])); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><i class="fas fa-clipboard-check me-2"></i> Review Report</div>
            <div class="card-body">
                <?php
                $st = $report['status'] ?? 'submitted';
                $badges = ['submitted' => 'info', 'approved' => 'success', 'rejected' => 'danger', 'reviewed' => 'primary'];
                ?>
                <p><strong>Current Status:</strong>
                    <span class="badge bg-<?php echo $badges[$st] ?? 'secondary'; ?>"><?php echo ucfirst($st); ?></span>
                </p>
                <?php if ($st === 'submitted'): ?>
                    <form method="POST" action="<?php echo BASE_URL; ?>supervisor/review-report">
                        <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                        <div class="mb-3">
                            <label class="form-label">Feedback / Comments</label>
                            <textarea name="comments" class="form-control" rows="4" placeholder="Provide feedback on the report..."></textarea>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" name="action" value="approve" class="btn btn-success"
                                onclick="return confirm('Approve this report?')">
                                <i class="fas fa-check me-2"></i> Approve
                            </button>
                            <button type="submit" name="action" value="reject" class="btn btn-danger"
                                onclick="return confirm('Reject this report?')">
                                <i class="fas fa-times me-2"></i> Reject
                            </button>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="alert alert-<?php echo $badges[$st] ?? 'secondary'; ?>">
                        This report has already been reviewed.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>
