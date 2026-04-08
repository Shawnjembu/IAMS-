<?php
$title = 'Upload Report';
$pageTitle = 'Upload Final Report';
require_once 'layouts/header.php';
?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-upload me-2"></i> Upload Final Report</span>
                <a href="<?php echo BASE_URL; ?>student/reports" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Allowed file types: <strong>PDF, DOC, DOCX, ZIP</strong> — Maximum size: <strong>10 MB</strong>
                </div>
                <form method="POST" action="<?php echo BASE_URL; ?>student/upload-report" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Report Title *</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Industrial Attachment Final Report" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Report File *</label>
                        <input type="file" name="report_file" class="form-control" accept=".pdf,.doc,.docx,.zip" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description / Notes</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Optional notes about this report..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-2"></i> Upload Report
                    </button>
                    <a href="<?php echo BASE_URL; ?>student/reports" class="btn btn-secondary ms-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>
