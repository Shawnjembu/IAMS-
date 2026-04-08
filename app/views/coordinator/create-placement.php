<?php
$title = 'Create Placement';
$pageTitle = 'Create New Placement';
require_once 'layouts/header.php';
?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-plus me-2"></i> Create New Placement</span>
                <a href="<?php echo BASE_URL; ?>coordinator/placements" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="<?php echo BASE_URL; ?>coordinator/create-placement">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Student *</label>
                            <select name="student_id" class="form-select" required>
                                <option value="">-- Select Student --</option>
                                <?php foreach ($students ?? [] as $s): ?>
                                    <option value="<?php echo $s['id']; ?>">
                                        <?php echo htmlspecialchars($s['student_number'] . ' — ' . $s['full_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Organization *</label>
                            <select name="organization_id" class="form-select" required>
                                <option value="">-- Select Organization --</option>
                                <?php foreach ($organizations ?? [] as $org): ?>
                                    <?php if ($org['status'] === 'approved'): ?>
                                    <option value="<?php echo $org['id']; ?>">
                                        <?php echo htmlspecialchars($org['organization_name']); ?>
                                    </option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Start Date *</label>
                            <input type="date" name="start_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">End Date *</label>
                            <input type="date" name="end_date" class="form-control" value="<?php echo date('Y-m-d', strtotime('+6 months')); ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Comments / Notes</label>
                            <textarea name="comments" class="form-control" rows="3" placeholder="Any additional notes about this placement..."></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Create Placement
                            </button>
                            <a href="<?php echo BASE_URL; ?>coordinator/placements" class="btn btn-secondary ms-2">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>
