<?php
// This view is used by both pendingReports() and analytics()
$isAnalytics = isset($orgStats);
$title = $isAnalytics ? 'Reports & Analytics' : 'Pending Reports';
$pageTitle = $isAnalytics ? 'Reports & Analytics' : 'Pending Reports';
require_once 'layouts/header.php';
?>

<?php if ($isAnalytics): ?>
    <!-- Analytics View -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><i class="fas fa-chart-bar me-2"></i> Placements by Organization</div>
                <div class="card-body p-0">
                    <?php if (empty($orgStats)): ?>
                        <p class="text-muted p-3">No placement data available.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead><tr><th>Organization</th><th>Total</th><th>Active</th><th>Completed</th></tr></thead>
                                <tbody>
                                    <?php foreach ($orgStats as $org): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($org['organization_name']); ?></td>
                                            <td><span class="badge bg-primary"><?php echo $org['placement_count']; ?></span></td>
                                            <td><span class="badge bg-success"><?php echo $org['active'] ?? 0; ?></span></td>
                                            <td><span class="badge bg-info"><?php echo $org['completed'] ?? 0; ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header"><i class="fas fa-star me-2"></i> Evaluation Statistics</div>
                <div class="card-body">
                    <?php if (!empty($avgScores)): ?>
                        <?php foreach (['attendance_score'=>'Attendance','performance_score'=>'Performance','professionalism_score'=>'Professionalism','learning_score'=>'Learning'] as $field=>$label): ?>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span><?php echo $label; ?></span>
                                    <strong><?php echo number_format($avgScores[$field] ?? 0, 1); ?>/100</strong>
                                </div>
                                <div class="progress" style="height:8px;">
                                    <div class="progress-bar bg-primary" style="width:<?php echo $avgScores[$field] ?? 0; ?>%"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <hr>
                        <div class="text-center">
                            <strong>Overall Average: <?php echo number_format($avgScores['overall_score'] ?? 0, 1); ?>/100</strong>
                        </div>
                    <?php else: ?>
                        <p class="text-muted">No evaluation data available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

<?php else: ?>
    <!-- Pending Reports View -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-file-alt me-2"></i> All Reports (<?php echo count($reports ?? []); ?>)
        </div>
        <div class="card-body">
            <?php if (empty($reports)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-file-alt fa-4x text-muted mb-3"></i>
                    <h5>No Reports Submitted</h5>
                    <p class="text-muted">No student reports have been submitted yet.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student</th>
                                <th>Title</th>
                                <th>File</th>
                                <th>Submitted</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reports as $i => $rpt): ?>
                                <tr>
                                    <td><?php echo $i + 1; ?></td>
                                    <td><?php echo htmlspecialchars($rpt['full_name'] ?? $rpt['student_name'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($rpt['title'] ?? 'Final Report'); ?></td>
                                    <td>
                                        <i class="fas fa-file-pdf text-danger me-1"></i>
                                        <?php echo htmlspecialchars($rpt['file_name'] ?? ''); ?>
                                    </td>
                                    <td><?php echo !empty($rpt['created_at']) ? date('M d, Y', strtotime($rpt['created_at'])) : '—'; ?></td>
                                    <td>
                                        <?php
                                        $badges = ['submitted' => 'info', 'approved' => 'success', 'rejected' => 'danger', 'reviewed' => 'primary'];
                                        $st = $rpt['status'] ?? 'submitted';
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
<?php endif; ?>

<?php require_once 'layouts/footer.php'; ?>
