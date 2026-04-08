<?php
$title = 'Evaluations';
$pageTitle = 'Student Evaluations';
require_once 'layouts/header.php';
?>

<div class="card">
    <div class="card-header">
        <i class="fas fa-star me-2"></i> All Evaluations (<?php echo count($evaluations ?? []); ?>)
    </div>
    <div class="card-body">
        <?php if (empty($evaluations)): ?>
            <div class="text-center py-5">
                <i class="fas fa-star fa-4x text-muted mb-3"></i>
                <h5>No Evaluations</h5>
                <p class="text-muted">No evaluations have been submitted yet.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Supervisor</th>
                            <th>Attendance</th>
                            <th>Performance</th>
                            <th>Professionalism</th>
                            <th>Learning</th>
                            <th>Overall</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($evaluations as $i => $ev): ?>
                            <tr>
                                <td><?php echo $i + 1; ?></td>
                                <td><?php echo htmlspecialchars($ev['student_name'] ?? $ev['full_name'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($ev['supervisor_name'] ?? ''); ?></td>
                                <td><?php echo number_format($ev['attendance_score'] ?? 0, 1); ?></td>
                                <td><?php echo number_format($ev['performance_score'] ?? 0, 1); ?></td>
                                <td><?php echo number_format($ev['professionalism_score'] ?? 0, 1); ?></td>
                                <td><?php echo number_format($ev['learning_score'] ?? 0, 1); ?></td>
                                <td>
                                    <strong class="text-primary"><?php echo number_format($ev['overall_score'] ?? 0, 1); ?></strong>
                                </td>
                                <td>
                                    <?php $st = $ev['status'] ?? 'draft'; ?>
                                    <span class="badge bg-<?php echo $st === 'submitted' ? 'success' : 'secondary'; ?>">
                                        <?php echo ucfirst($st); ?>
                                    </span>
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
