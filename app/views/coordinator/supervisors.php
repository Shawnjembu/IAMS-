<?php
$title = 'Supervisors';
$pageTitle = 'Supervisors';
require_once 'layouts/header.php';
?>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<div class="row">
    <!-- Supervisors List -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-user-tie me-2"></i> Supervisors (<?php echo count($supervisors ?? []); ?>)
            </div>
            <div class="card-body p-0">
                <?php if (empty($supervisors)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-user-tie fa-4x text-muted mb-3"></i>
                        <h5>No Supervisors Yet</h5>
                        <p class="text-muted">Create a supervisor account using the form.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Position</th>
                                    <th>Organization</th>
                                    <th>Students</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($supervisors as $i => $sup): ?>
                                    <tr>
                                        <td><?php echo $i + 1; ?></td>
                                        <td>
                                            <i class="fas fa-user-tie text-primary me-1"></i>
                                            <?php echo htmlspecialchars($sup['full_name']); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($sup['email']); ?></td>
                                        <td><?php echo htmlspecialchars($sup['position'] ?? '—'); ?></td>
                                        <td><?php echo htmlspecialchars($sup['organization_name'] ?? '—'); ?></td>
                                        <td>
                                            <span class="badge bg-info"><?php echo $sup['student_count'] ?? 0; ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- System Flow Info -->
        <div class="card mt-3">
            <div class="card-header"><i class="fas fa-info-circle me-2"></i> System Flow</div>
            <div class="card-body p-3">
                <ol class="mb-0 small text-muted">
                    <li class="mb-1">Organization registers → <strong>Coordinator approves</strong> → visible to students</li>
                    <li class="mb-1">Student registers → browses approved orgs → <strong>applies</strong></li>
                    <li class="mb-1">Organization <strong>accepts/rejects</strong> applications</li>
                    <li class="mb-1">Coordinator creates <strong>Placement</strong> (assigns supervisor)</li>
                    <li class="mb-1">Student submits weekly <strong>Logbooks</strong> → Supervisor reviews</li>
                    <li class="mb-1">Student uploads <strong>Final Report</strong> → Supervisor reviews</li>
                    <li class="mb-0">Supervisor submits <strong>Evaluation</strong> with scores</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Create Supervisor Form -->
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-plus me-2"></i> Create Supervisor Account
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info small">
                    <i class="fas fa-lock me-1"></i>
                    Supervisor accounts are created by the coordinator only — they do <strong>not</strong> self-register.
                    The supervisor uses their email + the password you set here to log in.
                </div>
                <form method="POST" action="<?php echo BASE_URL; ?>coordinator/create-supervisor">
                    <div class="mb-3">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="full_name" class="form-control" placeholder="e.g. John Doe" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address *</label>
                        <input type="email" name="email" class="form-control" placeholder="supervisor@company.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" placeholder="e.g. 0712345678">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Position / Title</label>
                        <input type="text" name="position" class="form-control" placeholder="e.g. Senior Engineer">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Linked Organization</label>
                        <select name="organization_id" class="form-select">
                            <option value="">-- None / Independent --</option>
                            <?php foreach ($organizations ?? [] as $org): ?>
                                <?php if ($org['status'] === 'approved'): ?>
                                    <option value="<?php echo $org['id']; ?>">
                                        <?php echo htmlspecialchars($org['organization_name']); ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">Associate supervisor with an approved organization.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" class="form-control" placeholder="Set a login password" required minlength="6">
                        <div class="form-text">Share this password with the supervisor securely.</div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-user-plus me-2"></i> Create Supervisor Account
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>
