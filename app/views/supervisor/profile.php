<?php
$title = 'My Profile';
$pageTitle = 'My Profile';
require_once 'layouts/header.php';
?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center p-4">
                <i class="fas fa-user-tie fa-5x text-primary mb-3"></i>
                <h5><?php echo htmlspecialchars($supervisor['full_name'] ?? ''); ?></h5>
                <p class="text-muted"><?php echo htmlspecialchars($supervisor['position'] ?? 'Supervisor'); ?></p>
                <a href="<?php echo BASE_URL; ?>supervisor/edit-profile" class="btn btn-primary w-100">
                    <i class="fas fa-edit me-2"></i> Edit Profile
                </a>
                <a href="<?php echo BASE_URL; ?>auth/change-password" class="btn btn-outline-secondary w-100 mt-2">
                    <i class="fas fa-key me-2"></i> Change Password
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-info-circle me-2"></i> Profile Details</div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr><td width="35%"><strong>Full Name</strong></td><td><?php echo htmlspecialchars($supervisor['full_name'] ?? ''); ?></td></tr>
                    <tr><td><strong>Email</strong></td><td><?php echo htmlspecialchars($supervisor['email'] ?? ''); ?></td></tr>
                    <tr><td><strong>Phone</strong></td><td><?php echo htmlspecialchars($supervisor['phone'] ?? 'Not set'); ?></td></tr>
                    <tr><td><strong>Position</strong></td><td><?php echo htmlspecialchars($supervisor['position'] ?? 'Not set'); ?></td></tr>
                    <tr><td><strong>Member Since</strong></td><td><?php echo !empty($supervisor['created_at']) ? date('M d, Y', strtotime($supervisor['created_at'])) : '—'; ?></td></tr>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>
