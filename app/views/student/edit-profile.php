<?php
$title = 'Edit Profile';
$pageTitle = 'Edit Profile';
require_once 'layouts/header.php';
?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-edit me-2"></i> Edit Profile</span>
                <a href="<?php echo BASE_URL; ?>student/profile" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="<?php echo BASE_URL; ?>student/edit-profile">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($student['full_name'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Student Number</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($student['student_number'] ?? ''); ?>" disabled>
                            <div class="form-text">Student number cannot be changed.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="<?php echo htmlspecialchars($student['email'] ?? ''); ?>" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($student['phone'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Program / Course</label>
                            <input type="text" name="program" class="form-control" value="<?php echo htmlspecialchars($student['program'] ?? ''); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Year of Study</label>
                            <select name="year" class="form-select">
                                <?php for ($y = 1; $y <= 4; $y++): ?>
                                    <option value="<?php echo $y; ?>" <?php echo ($student['year'] ?? '') == $y ? 'selected' : ''; ?>>Year <?php echo $y; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Semester</label>
                            <select name="semester" class="form-select">
                                <option value="Semester 1" <?php echo ($student['semester'] ?? '') === 'Semester 1' ? 'selected' : ''; ?>>Semester 1</option>
                                <option value="Semester 2" <?php echo ($student['semester'] ?? '') === 'Semester 2' ? 'selected' : ''; ?>>Semester 2</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Save Changes
                            </button>
                            <a href="<?php echo BASE_URL; ?>student/profile" class="btn btn-secondary ms-2">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>
