<?php
$title = 'Organizations';
$pageTitle = 'Organizations';
require_once 'layouts/header.php';
?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <i class="fas fa-building me-2"></i> Organizations (<?php echo count($organizations ?? []); ?>)
    </div>
    <div class="card-body">
        <?php if (empty($organizations)): ?>
            <div class="text-center py-5">
                <i class="fas fa-building fa-4x text-muted mb-3"></i>
                <h5>No Organizations</h5>
                <p class="text-muted">No organizations have registered yet.</p>
            </div>
        <?php else: ?>
            <div class="mb-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Search organizations...">
            </div>
            <div class="table-responsive">
                <table class="table table-hover" id="orgsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Organization</th>
                            <th>Industry</th>
                            <th>Contact</th>
                            <th>Students</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($organizations as $i => $org): ?>
                            <tr>
                                <td><?php echo $i + 1; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($org['organization_name'] ?? ''); ?></strong>
                                    <br><small class="text-muted"><?php echo htmlspecialchars($org['email'] ?? ''); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($org['industry_type'] ?? '—'); ?></td>
                                <td><?php echo htmlspecialchars($org['contact_person'] ?? '—'); ?></td>
                                <td><?php echo $org['student_count'] ?? 0; ?></td>
                                <td>
                                    <?php
                                    $badges = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'];
                                    $st = $org['status'] ?? 'pending';
                                    ?>
                                    <span class="badge bg-<?php echo $badges[$st] ?? 'secondary'; ?>">
                                        <?php echo ucfirst($st); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($org['status'] === 'pending'): ?>
                                        <form method="POST" action="<?php echo BASE_URL; ?>coordinator/process-organization" class="d-inline">
                                            <input type="hidden" name="organization_id" value="<?php echo $org['id']; ?>">
                                            <input type="hidden" name="action" value="approve">
                                            <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Approve this organization?')">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="<?php echo BASE_URL; ?>coordinator/process-organization" class="d-inline ms-1">
                                            <input type="hidden" name="organization_id" value="<?php echo $org['id']; ?>">
                                            <input type="hidden" name="action" value="reject">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Reject this organization?')">
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

<script>
document.getElementById('searchInput')?.addEventListener('input', function() {
    const val = this.value.toLowerCase();
    document.querySelectorAll('#orgsTable tbody tr').forEach(function(row) {
        row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
    });
});
</script>

<?php require_once 'layouts/footer.php'; ?>
