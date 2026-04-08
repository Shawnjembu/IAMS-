<?php
$title = 'Attached Students';
$pageTitle = 'Attached Students';
require_once 'layouts/header.php';
?>

<div class="card">
    <div class="card-header">
        <i class="fas fa-user-graduate me-2"></i> Attached Students
    </div>
    <div class="card-body">
        <?php if (empty($placements)): ?>
            <div class="text-center py-5">
                <i class="fas fa-user-graduate fa-4x text-muted mb-3"></i>
                <h5>No Students Assigned</h5>
                <p class="text-muted">No students are currently placed at your organization.</p>
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
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($placements as $i => $pl): ?>
                            <tr>
                                <td><?php echo $i + 1; ?></td>
                                <td><?php echo htmlspecialchars($pl['full_name'] ?? $pl['student_name'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($pl['student_number'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($pl['program'] ?? '—'); ?></td>
                                <td><?php echo !empty($pl['start_date']) ? date('M d, Y', strtotime($pl['start_date'])) : '—'; ?></td>
                                <td><?php echo !empty($pl['end_date']) ? date('M d, Y', strtotime($pl['end_date'])) : '—'; ?></td>
                                <td>
                                    <?php
                                    $badges = ['pending' => 'warning', 'approved' => 'info', 'active' => 'success', 'completed' => 'primary', 'cancelled' => 'danger'];
                                    $st = $pl['status'] ?? 'active';
                                    ?>
                                    <span class="badge bg-<?php echo $badges[$st] ?? 'secondary'; ?>">
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
