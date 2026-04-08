<?php
$title = 'View Logbook';
$pageTitle = 'Logbook Entry';
require_once 'layouts/header.php';
?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Week <?php echo htmlspecialchars($logbook['week_number'] ?? ''); ?> Logbook</h5>
    <a href="<?php echo BASE_URL; ?>supervisor/logbooks" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-book me-2"></i> Logbook Details</div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Student:</strong> <?php echo htmlspecialchars($logbook['full_name'] ?? $logbook['student_name'] ?? ''); ?>
                    </div>
                    <div class="col-md-6">
                        <strong>Week Period:</strong>
                        <?php echo !empty($logbook['week_start']) ? date('M d, Y', strtotime($logbook['week_start'])) : ''; ?>
                        — <?php echo !empty($logbook['week_end']) ? date('M d, Y', strtotime($logbook['week_end'])) : ''; ?>
                    </div>
                </div>
                <hr>
                <h6><i class="fas fa-tasks me-2"></i> Activities Performed</h6>
                <p class="text-muted"><?php echo nl2br(htmlspecialchars($logbook['activities'] ?? '')); ?></p>
                <hr>
                <h6><i class="fas fa-lightbulb me-2"></i> Learning Outcomes</h6>
                <p class="text-muted"><?php echo nl2br(htmlspecialchars($logbook['learning_outcomes'] ?? 'Not provided.')); ?></p>
                <hr>
                <h6><i class="fas fa-exclamation-triangle me-2"></i> Challenges Faced</h6>
                <p class="text-muted"><?php echo nl2br(htmlspecialchars($logbook['challenges'] ?? 'None reported.')); ?></p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><i class="fas fa-clipboard-check me-2"></i> Review Logbook</div>
            <div class="card-body">
                <?php
                $st = $logbook['status'] ?? 'submitted';
                $badges = ['submitted' => 'info', 'approved' => 'success', 'rejected' => 'danger', 'reviewed' => 'primary'];
                ?>
                <p><strong>Current Status:</strong>
                    <span class="badge bg-<?php echo $badges[$st] ?? 'secondary'; ?>"><?php echo ucfirst($st); ?></span>
                </p>
                <?php if ($st === 'submitted'): ?>
                    <form method="POST" action="<?php echo BASE_URL; ?>supervisor/review-logbook">
                        <input type="hidden" name="logbook_id" value="<?php echo $logbook['id']; ?>">
                        <div class="mb-3">
                            <label class="form-label">Comments / Feedback</label>
                            <textarea name="comments" class="form-control" rows="4" placeholder="Provide feedback to the student..."></textarea>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" name="action" value="approve" class="btn btn-success"
                                onclick="return confirm('Approve this logbook?')">
                                <i class="fas fa-check me-2"></i> Approve
                            </button>
                            <button type="submit" name="action" value="reject" class="btn btn-danger"
                                onclick="return confirm('Reject this logbook?')">
                                <i class="fas fa-times me-2"></i> Reject
                            </button>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="alert alert-<?php echo $badges[$st] ?? 'secondary'; ?>">
                        This logbook has already been reviewed.
                    </div>
                    <?php if (!empty($logbook['comments'])): ?>
                        <p><strong>Your feedback:</strong><br><?php echo nl2br(htmlspecialchars($logbook['comments'])); ?></p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>
