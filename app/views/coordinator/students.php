<?php
$title = 'All Students';
$pageTitle = 'Students';
require_once 'layouts/header.php';
?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-user-graduate me-2"></i> All Students (<?php echo count($students ?? []); ?>)</span>
    </div>
    <div class="card-body">
        <?php if (empty($students)): ?>
            <div class="text-center py-5">
                <i class="fas fa-user-graduate fa-4x text-muted mb-3"></i>
                <h5>No Students Registered</h5>
                <p class="text-muted">No students have registered in the system yet.</p>
            </div>
        <?php else: ?>
            <div class="mb-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Search by name, student number, or program...">
            </div>
            <div class="table-responsive">
                <table class="table table-hover" id="studentsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student No.</th>
                            <th>Name</th>
                            <th>Program</th>
                            <th>Organization</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $i => $s): ?>
                            <tr>
                                <td><?php echo $i + 1; ?></td>
                                <td><?php echo htmlspecialchars($s['student_number'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($s['full_name'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($s['program'] ?? '—'); ?></td>
                                <td><?php echo htmlspecialchars($s['organization_name'] ?? 'Not placed'); ?></td>
                                <td>
                                    <?php
                                    $badges = ['pending' => 'warning', 'approved' => 'info', 'active' => 'success', 'completed' => 'primary', 'rejected' => 'danger'];
                                    $st = $s['status'] ?? 'pending';
                                    ?>
                                    <span class="badge bg-<?php echo $badges[$st] ?? 'secondary'; ?>">
                                        <?php echo ucfirst($st); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo BASE_URL; ?>coordinator/view-student?id=<?php echo $s['id']; ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
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
    document.querySelectorAll('#studentsTable tbody tr').forEach(function(row) {
        row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
    });
});
</script>

<?php require_once 'layouts/footer.php'; ?>
