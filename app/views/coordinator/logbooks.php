<?php
$title = 'Logbooks';
$pageTitle = 'All Logbooks';
require_once 'layouts/header.php';
?>

<div class="card">
    <div class="card-header">
        <i class="fas fa-book me-2"></i> All Logbook Entries (<?php echo count($logbooks ?? []); ?>)
    </div>
    <div class="card-body">
        <?php if (empty($logbooks)): ?>
            <div class="text-center py-5">
                <i class="fas fa-book fa-4x text-muted mb-3"></i>
                <h5>No Logbook Entries</h5>
                <p class="text-muted">No logbook entries have been submitted yet.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Student No.</th>
                            <th>Week</th>
                            <th>Period</th>
                            <th>Status</th>
                            <th>Submitted</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logbooks as $i => $lb): ?>
                            <tr>
                                <td><?php echo $i + 1; ?></td>
                                <td><?php echo htmlspecialchars($lb['full_name'] ?? $lb['student_name'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($lb['student_number'] ?? ''); ?></td>
                                <td>Week <?php echo htmlspecialchars($lb['week_number']); ?></td>
                                <td>
                                    <?php echo !empty($lb['week_start']) ? date('M d', strtotime($lb['week_start'])) : ''; ?>
                                    — <?php echo !empty($lb['week_end']) ? date('M d, Y', strtotime($lb['week_end'])) : ''; ?>
                                </td>
                                <td>
                                    <?php
                                    $badges = ['submitted' => 'info', 'approved' => 'success', 'rejected' => 'danger', 'reviewed' => 'primary', 'pending' => 'secondary'];
                                    $st = $lb['status'] ?? 'submitted';
                                    ?>
                                    <span class="badge bg-<?php echo $badges[$st] ?? 'secondary'; ?>">
                                        <?php echo ucfirst($st); ?>
                                    </span>
                                </td>
                                <td><?php echo !empty($lb['created_at']) ? date('M d, Y', strtotime($lb['created_at'])) : '—'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>
