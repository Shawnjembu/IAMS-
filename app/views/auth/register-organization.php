<?php
$title = 'Register - Organization';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .register-card { background: white; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); max-width: 600px; width: 100%; }
        .register-header { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; padding: 30px; text-align: center; border-radius: 15px 15px 0 0; }
        .register-body { padding: 30px 40px; }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="register-card mx-auto">
        <div class="register-header">
            <h4><i class="fas fa-building me-2"></i> IAMS</h4>
            <p class="mb-0">Organization Registration</p>
        </div>
        <div class="register-body">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                Your registration will be reviewed by a coordinator before activation.
            </div>

            <form method="POST" action="<?php echo BASE_URL; ?>auth/create-organization">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Organization Name *</label>
                        <input type="text" name="organization_name" class="form-control" placeholder="Full company/organization name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email Address *</label>
                        <input type="email" name="email" class="form-control" placeholder="contact@organization.com" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control" placeholder="Organization phone">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contact Person *</label>
                        <input type="text" name="contact_person" class="form-control" placeholder="Primary contact name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Industry Type</label>
                        <select name="industry_type" class="form-select">
                            <option value="">-- Select Industry --</option>
                            <option>Information Technology</option>
                            <option>Finance & Banking</option>
                            <option>Healthcare</option>
                            <option>Engineering</option>
                            <option>Education</option>
                            <option>Manufacturing</option>
                            <option>Retail & Commerce</option>
                            <option>Government</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="2" placeholder="Physical address"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Brief description of your organization and what students will do"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimum 6 characters" required minlength="6">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm Password *</label>
                        <input type="password" name="confirm_password" class="form-control" placeholder="Repeat password" required minlength="6">
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-building me-2"></i> Register Organization
                        </button>
                    </div>
                </div>
            </form>
            <hr>
            <p class="text-center mb-0">
                Already registered? <a href="<?php echo BASE_URL; ?>auth/login">Login here</a>
            </p>
            <p class="text-center">
                <a href="<?php echo BASE_URL; ?>auth/register-student">Register as Student</a>
            </p>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
