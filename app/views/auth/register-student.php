<?php
$title = 'Register - Student';
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
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .register-card { background: white; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); overflow: hidden; max-width: 600px; width: 100%; }
        .register-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; }
        .register-body { padding: 30px 40px; }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="register-card mx-auto">
        <div class="register-header">
            <h4><i class="fas fa-graduation-cap me-2"></i> IAMS</h4>
            <p class="mb-0">Student Registration</p>
        </div>
        <div class="register-body">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <form method="POST" action="<?php echo BASE_URL; ?>auth/create-student">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Student Number *</label>
                        <input type="text" name="student_number" class="form-control" placeholder="e.g. STU2024001" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="full_name" class="form-control" placeholder="Your full name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email Address *</label>
                        <input type="email" name="email" class="form-control" placeholder="your@email.com" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control" placeholder="e.g. 0712345678">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Program / Course *</label>
                        <input type="text" name="program" class="form-control" placeholder="e.g. Computer Science" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Year of Study</label>
                        <select name="year" class="form-select">
                            <option value="1">Year 1</option>
                            <option value="2">Year 2</option>
                            <option value="3" selected>Year 3</option>
                            <option value="4">Year 4</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Semester</label>
                        <select name="semester" class="form-select">
                            <option value="Semester 1">Semester 1</option>
                            <option value="Semester 2">Semester 2</option>
                        </select>
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
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-user-plus me-2"></i> Register
                        </button>
                    </div>
                </div>
            </form>
            <hr>
            <p class="text-center mb-0">
                Already have an account? <a href="<?php echo BASE_URL; ?>auth/login">Login here</a>
            </p>
            <p class="text-center">
                <a href="<?php echo BASE_URL; ?>auth/register-organization">Register as Organization</a>
            </p>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
