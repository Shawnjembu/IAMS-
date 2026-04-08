<?php
$title = 'Company Profile';
$pageTitle = 'Company Profile';
require_once 'layouts/header.php';
?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center p-4">
                <i class="fas fa-building fa-5x text-primary mb-3"></i>
                <h5><?php echo htmlspecialchars($organization['organization_name'] ?? ''); ?></h5>
                <p class="text-muted"><?php echo htmlspecialchars($organization['industry_type'] ?? ''); ?></p>
                <?php
                $st = $organization['status'] ?? 'pending';
                $badges = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'];
                ?>
                <span class="badge bg-<?php echo $badges[$st] ?? 'secondary'; ?> p-2">
                    <?php echo strtoupper($st); ?>
                </span>
                <div class="mt-3">
                    <a href="<?php echo BASE_URL; ?>organization/edit-profile" class="btn btn-primary w-100">
                        <i class="fas fa-edit me-2"></i> Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><i class="fas fa-info-circle me-2"></i> Organization Details</div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td width="40%"><strong>Organization Name</strong></td>
                        <td><?php echo htmlspecialchars($organization['organization_name'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Email</strong></td>
                        <td><?php echo htmlspecialchars($organization['email'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Phone</strong></td>
                        <td><?php echo htmlspecialchars($organization['phone'] ?? 'Not set'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Contact Person</strong></td>
                        <td><?php echo htmlspecialchars($organization['contact_person'] ?? 'Not set'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Industry Type</strong></td>
                        <td><?php echo htmlspecialchars($organization['industry_type'] ?? 'Not set'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Address</strong></td>
                        <td><?php echo htmlspecialchars($organization['address'] ?? 'Not set'); ?></td>
                    </tr>
                    <tr>
                        <td><strong>Member Since</strong></td>
                        <td><?php echo !empty($organization['created_at']) ? date('M d, Y', strtotime($organization['created_at'])) : '—'; ?></td>
                    </tr>
                </table>
                <?php if (!empty($organization['description'])): ?>
                    <hr>
                    <h6>About</h6>
                    <p class="text-muted"><?php echo nl2br(htmlspecialchars($organization['description'])); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>
