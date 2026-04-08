<?php
$title = 'View Student';
$pageTitle = 'Student Details';
require_once 'layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><?php echo htmlspecialchars($student['full_name'] ?? 'Student'); ?></h5>
    <a href="<?php echo BASE_URL; ?>supervisor/students" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back to Students
    </a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-user me-2"></i> Student Info</div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <i class="fas fa-user-graduate fa-4x text-primary mb-2"></i>
                    <h6><?php echo htmlspecialchars($student['full_name'] ?? ''); ?></h6>
                    <small class="text-muted"><?php echo htmlspecialchars($student['student_number'] ?? ''); ?></small>
                </div>
                <table class="table table-sm table-borderless">
                    <tr><td><strong>Email:</strong></td><td><?php echo htmlspecialchars($student['email'] ?? ''); ?></td></tr>
                    <tr><td><strong>Phone:</strong></td><td><?php echo htmlspecialchars($student['phone'] ?? '—'); ?></td></tr>
                    <tr><td><strong>Program:</strong></td><td><?php echo htmlspecialchars($student['program'] ?? '—'); ?></td></tr>
                    <tr><td><strong>Year:</strong></td><td><?php echo htmlspecialchars($student['year'] ?? '—'); ?></td></tr>
                </table>
            </div>
        </div>

        <!-- Evaluate -->
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-star me-2"></i> Submit Evaluation</div>
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>supervisor/evaluate">
                    <input type="hidden" name="student_id" value="<?php echo $student['id'] ?? 0; ?>">
                    <input type="hidden" name="placement_id" value="<?php echo $placement['id'] ?? ''; ?>">
                    <?php foreach (['attendance_score'=>'Attendance','performance_score'=>'Performance','professionalism_score'=>'Professionalism','learning_score'=>'Learning'] as $field=>$label): ?>
                        <div class="mb-2">
                            <label class="form-label small"><?php echo $label; ?> (0-100)</label>
                            <input type="number" name="<?php echo $field; ?>" class="form-control form-control-sm" min="0" max="100" value="70" required>
                        </div>
                    <?php endforeach; ?>
                    <div class="mb-2">
                        <label class="form-label small">Strengths</label>
                        <textarea name="strengths" class="form-control form-control-sm" rows="2"></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Weaknesses</label>
                        <textarea name="weaknesses" class="form-control form-control-sm" rows="2"></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Comments</label>
                        <textarea name="comments" class="form-control form-control-sm" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100" onclick="return confirm('Submit evaluation?')">
                        <i class="fas fa-paper-plane me-1"></i> Submit Evaluation
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Logbooks -->
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-book me-2"></i> Logbooks (<?php echo count($logbooks ?? []); ?>)</div>
            <div class="card-body p-0">
                <?php if (empty($logbooks)): ?>
                    <p class="text-muted p-3 mb-0">No logbook entries submitted yet.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Week</th><th>Period</th><th>Status</th><th>Action</th></tr></thead>
                            <tbody>
                                <?php foreach ($logbooks as $lb): ?>
                                    <tr>
                                        <td>Week <?php echo $lb['week_number']; ?></td>
                                        <td><?php echo !empty($lb['week_start']) ? date('M d', strtotime($lb['week_start'])) : ''; ?> — <?php echo !empty($lb['week_end']) ? date('M d', strtotime($lb['week_end'])) : ''; ?></td>
                                        <td>
                                            <?php $badges=['submitted'=>'info','approved'=>'success','rejected'=>'danger','reviewed'=>'primary']; $st=$lb['status']??'submitted'; ?>
                                            <span class="badge bg-<?php echo $badges[$st]??'secondary'; ?>"><?php echo ucfirst($st); ?></span>
                                        </td>
                                        <td>
                                            <a href="<?php echo BASE_URL; ?>supervisor/view-logbook?id=<?php echo $lb['id']; ?>" class="btn btn-xs btn-outline-primary btn-sm">View</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Reports -->
        <div class="card">
            <div class="card-header"><i class="fas fa-file-alt me-2"></i> Reports (<?php echo count($reports ?? []); ?>)</div>
            <div class="card-body p-0">
                <?php if (empty($reports)): ?>
                    <p class="text-muted p-3 mb-0">No reports submitted yet.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Title</th><th>Submitted</th><th>Status</th><th>Action</th></tr></thead>
                            <tbody>
                                <?php foreach ($reports as $rpt): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($rpt['title'] ?? 'Final Report'); ?></td>
                                        <td><?php echo !empty($rpt['created_at']) ? date('M d, Y', strtotime($rpt['created_at'])) : '—'; ?></td>
                                        <td>
                                            <?php $badges=['submitted'=>'info','approved'=>'success','rejected'=>'danger']; $st=$rpt['status']??'submitted'; ?>
                                            <span class="badge bg-<?php echo $badges[$st]??'secondary'; ?>"><?php echo ucfirst($st); ?></span>
                                        </td>
                                        <td>
                                            <a href="<?php echo BASE_URL; ?>supervisor/view-report?id=<?php echo $rpt['id']; ?>" class="btn btn-sm btn-outline-primary">View</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>
