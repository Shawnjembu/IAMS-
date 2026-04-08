<?php
$title = 'Edit Company Profile';
$pageTitle = 'Edit Company Profile';
require_once 'layouts/header.php';
?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-edit me-2"></i> Edit Company Profile</span>
                <a href="<?php echo BASE_URL; ?>organization/profile" class="btn btn-sm btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="<?php echo BASE_URL; ?>organization/edit-profile">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Organization Name *</label>
                            <input type="text" name="organization_name" class="form-control" value="<?php echo htmlspecialchars($organization['organization_name'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contact Person *</label>
                            <input type="text" name="contact_person" class="form-control" value="<?php echo htmlspecialchars($organization['contact_person'] ?? ''); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($organization['phone'] ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Industry Type</label>
                            <select name="industry_type" class="form-select">
                                <?php
                                $industries = ['Information Technology', 'Finance & Banking', 'Healthcare', 'Engineering', 'Education', 'Manufacturing', 'Retail & Commerce', 'Government', 'Other'];
                                foreach ($industries as $ind):
                                ?>
                                    <option value="<?php echo $ind; ?>" <?php echo ($organization['industry_type'] ?? '') === $ind ? 'selected' : ''; ?>><?php echo $ind; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="<?php echo htmlspecialchars($organization['email'] ?? ''); ?>" disabled>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="2"><?php echo htmlspecialchars($organization['address'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($organization['description'] ?? ''); ?></textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Save Changes
                            </button>
                            <a href="<?php echo BASE_URL; ?>organization/profile" class="btn btn-secondary ms-2">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>
