<?php
$title = 'View Student';
$pageTitle = 'Student Details';
require_once 'layouts/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><?php echo htmlspecialchars($student['full_name'] ?? 'Student'); ?></h5>
    <a href="<?php echo BASE_URL; ?>coordinator/students" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back to Students
    </a>
</div>

<div class="row">
    <!-- Student Info -->
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
                    <tr><td><strong>Status:</strong></td><td>
                        <?php $st = $student['status'] ?? 'pending'; $badges = ['pending'=>'warning','approved'=>'info','active'=>'success','completed'=>'primary','rejected'=>'danger']; ?>
                        <span class="badge bg-<?php echo $badges[$st]??'secondary'; ?>"><?php echo ucfirst($st); ?></span>
                    </td></tr>
                </table>
            </div>
        </div>

        <?php if ($placement): ?>
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-briefcase me-2"></i> Placement</div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr><td><strong>Organization:</strong></td><td><?php echo htmlspecialchars($placement['organization_name'] ?? ''); ?></td></tr>
                    <tr><td><strong>Supervisor:</strong></td><td><?php echo htmlspecialchars($placement['supervisor_name'] ?? '—'); ?></td></tr>
                    <tr><td><strong>Start:</strong></td><td><?php echo !empty($placement['start_date']) ? date('M d, Y', strtotime($placement['start_date'])) : '—'; ?></td></tr>
                    <tr><td><strong>End:</strong></td><td><?php echo !empty($placement['end_date']) ? date('M d, Y', strtotime($placement['end_date'])) : '—'; ?></td></tr>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Logbooks & Reports -->
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-book me-2"></i> Logbooks (<?php echo count($logbooks ?? []); ?>)</div>
            <div class="card-body p-0">
                <?php if (empty($logbooks)): ?>
                    <p class="text-muted p-3 mb-0">No logbook entries submitted.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Week</th><th>Period</th><th>Status</th></tr></thead>
                            <tbody>
                                <?php foreach ($logbooks as $lb): ?>
                                    <tr>
                                        <td>Week <?php echo $lb['week_number']; ?></td>
                                        <td><?php echo !empty($lb['week_start']) ? date('M d', strtotime($lb['week_start'])) : ''; ?> — <?php echo !empty($lb['week_end']) ? date('M d, Y', strtotime($lb['week_end'])) : ''; ?></td>
                                        <td>
                                            <?php $badges=['submitted'=>'info','approved'=>'success','rejected'=>'danger','reviewed'=>'primary']; $st=$lb['status']??'submitted'; ?>
                                            <span class="badge bg-<?php echo $badges[$st]??'secondary'; ?>"><?php echo ucfirst($st); ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header"><i class="fas fa-file-alt me-2"></i> Reports (<?php echo count($reports ?? []); ?>)</div>
            <div class="card-body p-0">
                <?php if (empty($reports)): ?>
                    <p class="text-muted p-3 mb-0">No reports submitted.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead><tr><th>Title</th><th>Submitted</th><th>Status</th></tr></thead>
                            <tbody>
                                <?php foreach ($reports as $rpt): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($rpt['title'] ?? 'Final Report'); ?></td>
                                        <td><?php echo !empty($rpt['created_at']) ? date('M d, Y', strtotime($rpt['created_at'])) : '—'; ?></td>
                                        <td>
                                            <?php $badges=['submitted'=>'info','approved'=>'success','rejected'=>'danger']; $st=$rpt['status']??'submitted'; ?>
                                            <span class="badge bg-<?php echo $badges[$st]??'secondary'; ?>"><?php echo ucfirst($st); ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($evaluations)): ?>
        <div class="card">
            <div class="card-header"><i class="fas fa-star me-2"></i> Evaluations</div>
            <div class="card-body">
                <?php foreach ($evaluations as $ev): ?>
                    <div class="mb-3 p-3 border rounded">
                        <div class="row text-center mb-2">
                            <?php foreach (['attendance_score'=>'Attendance','performance_score'=>'Performance','professionalism_score'=>'Professionalism','learning_score'=>'Learning'] as $field=>$label): ?>
                                <div class="col-3">
                                    <div class="fw-bold fs-4 text-primary"><?php echo number_format($ev[$field]??0, 1); ?></div>
                                    <small class="text-muted"><?php echo $label; ?></small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="text-center">
                            <strong>Overall: <?php echo number_format($ev['overall_score']??0, 1); ?>/100</strong>
                        </div>
                        <?php if (!empty($ev['comments'])): ?>
                            <p class="mt-2 mb-0 text-muted small"><?php echo htmlspecialchars($ev['comments']); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>
