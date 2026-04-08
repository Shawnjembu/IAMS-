<?php 
$title = 'My Profile';
$pageTitle = 'My Profile';
require_once 'layouts/header.php'; 
?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <i class="fas fa-user me-2"></i> Profile Information
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th>Student Number:</th>
                        <td><?php echo htmlspecialchars($student['student_number'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <th>Full Name:</th>
                        <td><?php echo htmlspecialchars($student['full_name'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td><?php echo htmlspecialchars($student['email'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <th>Phone:</th>
                        <td><?php echo htmlspecialchars($student['phone'] ?? 'Not set'); ?></td>
                    </tr>
                    <tr>
                        <th>Program:</th>
                        <td><?php echo htmlspecialchars($student['program'] ?? 'Not set'); ?></td>
                    </tr>
                    <tr>
                        <th>Year:</th>
                        <td><?php echo htmlspecialchars($student['year'] ?? ''); ?></td>
                    </tr>
                    <tr>
                        <th>Semester:</th>
                        <td><?php echo htmlspecialchars($student['semester'] ?? 'Not set'); ?></td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td>
                            <span class="badge bg-<?php 
                                echo $student['status'] === 'active' ? 'success' : 
                                    ($student['status'] === 'pending' ? 'warning' : 'secondary'); 
                            ?>">
                                <?php echo htmlspecialchars($student['status'] ?? 'pending'); ?>
                            </span>
                        </td>
                    </tr>
                </table>
                
                <a href="<?php echo BASE_URL; ?>student/edit-profile" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i> Edit Profile
                </a>
            </div>
        </div>
        
        <?php if ($placement): ?>
        <div class="card mt-3">
            <div class="card-header">
                <i class="fas fa-briefcase me-2"></i> Placement Information
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th>Organization:</th>
                        <td><?php echo htmlspecialchars($placement['organization_name'] ?? 'Not assigned'); ?></td>
                    </tr>
                    <tr>
                        <th>Supervisor:</th>
                        <td><?php echo htmlspecialchars($placement['supervisor_name'] ?? 'Not assigned'); ?></td>
                    </tr>
                    <tr>
                        <th>Start Date:</th>
                        <td><?php echo !empty($placement['start_date']) ? date('M d, Y', strtotime($placement['start_date'])) : 'Not set'; ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'layouts/footer.php'; ?>
