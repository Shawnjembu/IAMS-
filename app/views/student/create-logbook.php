<?php
$title = 'Submit Logbook Entry';
$pageTitle = 'Submit Logbook Entry';
require_once 'layouts/header.php';
?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-book me-2"></i> Week <?php echo htmlspecialchars($currentWeek ?? 1); ?> Logbook Entry</span>
                <a href="<?php echo BASE_URL; ?>student/logbooks" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="<?php echo BASE_URL; ?>student/create-logbook">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Week Start Date *</label>
                            <input type="date" name="week_start" class="form-control" value="<?php echo date('Y-m-d', strtotime('monday this week')); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Week End Date *</label>
                            <input type="date" name="week_end" class="form-control" value="<?php echo date('Y-m-d', strtotime('friday this week')); ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Activities Performed *</label>
                            <textarea name="activities" class="form-control" rows="5" placeholder="Describe the tasks and activities you performed this week..." required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Learning Outcomes</label>
                            <textarea name="learning_outcomes" class="form-control" rows="4" placeholder="What did you learn? New skills, knowledge, or insights gained..."></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Challenges Faced</label>
                            <textarea name="challenges" class="form-control" rows="3" placeholder="Any difficulties encountered and how you dealt with them..."></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i> Submit Logbook Entry
                            </button>
                            <a href="<?php echo BASE_URL; ?>student/logbooks" class="btn btn-secondary ms-2">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>
