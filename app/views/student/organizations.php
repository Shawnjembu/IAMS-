<?php 
$title = 'Organizations';
$pageTitle = 'Browse Organizations';
require_once 'layouts/header.php'; 
?>

<!-- Alerts -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-building me-2"></i> Available Organizations
            </div>
            <div class="card-body">
                <?php if (count($organizations) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Company Name</th>
                                    <th>Industry</th>
                                    <th>Address</th>
                                    <th>Contact Person</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($organizations as $org): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($org['organization_name']); ?></td>
                                        <td><?php echo htmlspecialchars($org['industry_type'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($org['address'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($org['contact_person'] ?? 'N/A'); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#applyModal<?php echo $org['id']; ?>">
                                                <i class="fas fa-paper-plane me-1"></i> Apply
                                            </button>
                                            
                                            <!-- Apply Modal -->
                                            <div class="modal fade" id="applyModal<?php echo $org['id']; ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Apply to <?php echo htmlspecialchars($org['organization_name']); ?></h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form action="<?php echo BASE_URL; ?>student/apply" method="POST">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="organization_id" value="<?php echo $org['id']; ?>">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Cover Letter</label>
                                                                    <textarea class="form-control" name="cover_letter" rows="5" placeholder="Explain why you want to intern at this organization..." required></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-primary">Submit Application</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-building fa-3x text-muted mb-3"></i>
                        <p>No approved organizations available at the moment.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>
