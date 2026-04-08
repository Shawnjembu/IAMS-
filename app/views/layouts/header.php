<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'IAMS - Industrial Attachment Management System'; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .sidebar {
            min-height: 100vh;
            background: #2c3e50;
            color: white;
        }
        
        .sidebar a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            padding: 12px 20px;
            display: block;
            transition: all 0.3s;
        }
        
        .sidebar a:hover, .sidebar a.active {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        
        .sidebar .nav-item {
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        
        .main-content {
            padding: 20px;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #eee;
            font-weight: 600;
        }
        
        .stat-card {
            border-radius: 10px;
            padding: 20px;
            color: white;
        }
        
        .stat-card.blue { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stat-card.green { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
        .stat-card.orange { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .stat-card.purple { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        
        .table th {
            font-weight: 600;
            color: #555;
            border-bottom: 2px solid #eee;
        }
        
        .btn-primary {
            background: #667eea;
            border: none;
        }
        
        .btn-primary:hover {
            background: #5568d3;
        }
        
        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            padding: 3px 7px;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar d-none d-md-block">
                <div class="text-center py-4">
                    <h4><i class="fas fa-graduation-cap"></i> IAMS</h4>
                    <p class="small text-muted">Industrial Attachment</p>
                </div>
                
                <nav class="nav flex-column">
                    <?php 
                    $role = $_SESSION['role'] ?? '';
                    $currentPage = $_GET['url'] ?? '';
                    ?>
                    
                    <?php if ($role === 'student'): ?>
                        <a href="<?php echo BASE_URL; ?>student/dashboard" class="nav-item <?php echo strpos($currentPage, 'student/dashboard') !== false ? 'active' : ''; ?>">
                            <i class="fas fa-home me-2"></i> Dashboard
                        </a>
                        <a href="<?php echo BASE_URL; ?>student/profile" class="nav-item <?php echo strpos($currentPage, 'student/profile') !== false ? 'active' : ''; ?>">
                            <i class="fas fa-user me-2"></i> My Profile
                        </a>
                        <a href="<?php echo BASE_URL; ?>student/organizations" class="nav-item <?php echo strpos($currentPage, 'student/organizations') !== false ? 'active' : ''; ?>">
                            <i class="fas fa-building me-2"></i> Organizations
                        </a>
                        <a href="<?php echo BASE_URL; ?>student/applications" class="nav-item <?php echo strpos($currentPage, 'student/applications') !== false ? 'active' : ''; ?>">
                            <i class="fas fa-paper-plane me-2"></i> My Applications
                        </a>
                        <a href="<?php echo BASE_URL; ?>student/logbooks" class="nav-item <?php echo strpos($currentPage, 'student/logbooks') !== false ? 'active' : ''; ?>">
                            <i class="fas fa-book me-2"></i> Logbooks
                        </a>
                        <a href="<?php echo BASE_URL; ?>student/reports" class="nav-item <?php echo strpos($currentPage, 'student/reports') !== false ? 'active' : ''; ?>">
                            <i class="fas fa-file-alt me-2"></i> Reports
                        </a>
                        <a href="<?php echo BASE_URL; ?>student/notifications" class="nav-item <?php echo strpos($currentPage, 'student/notifications') !== false ? 'active' : ''; ?>">
                            <i class="fas fa-bell me-2"></i> Notifications
                        </a>
                    
                    <?php elseif ($role === 'organization'): ?>
                        <a href="<?php echo BASE_URL; ?>organization/dashboard" class="nav-item <?php echo strpos($currentPage, 'organization/dashboard') !== false ? 'active' : ''; ?>">
                            <i class="fas fa-home me-2"></i> Dashboard
                        </a>
                        <a href="<?php echo BASE_URL; ?>organization/profile" class="nav-item <?php echo strpos($currentPage, 'organization/profile') !== false ? 'active' : ''; ?>">
                            <i class="fas fa-building me-2"></i> Company Profile
                        </a>
                        <a href="<?php echo BASE_URL; ?>organization/applications" class="nav-item <?php echo strpos($currentPage, 'organization/applications') !== false ? 'active' : ''; ?>">
                            <i class="fas fa-users me-2"></i> Applications
                        </a>
                        <a href="<?php echo BASE_URL; ?>organization/students" class="nav-item <?php echo strpos($currentPage, 'organization/students') !== false ? 'active' : ''; ?>">
                            <i class="fas fa-user-graduate me-2"></i> Students
                        </a>
                        <a href="<?php echo BASE_URL; ?>organization/notifications" class="nav-item <?php echo strpos($currentPage, 'organization/notifications') !== false ? 'active' : ''; ?>">
                            <i class="fas fa-bell me-2"></i> Notifications
                        </a>
                    
                    <?php elseif ($role === 'coordinator'): ?>
                        <a href="<?php echo BASE_URL; ?>coordinator/dashboard" class="nav-item <?php echo strpos($currentPage, 'coordinator/dashboard') !== false ? 'active' : ''; ?>">
                            <i class="fas fa-home me-2"></i> Dashboard
                        </a>
                        <a href="<?php echo BASE_URL; ?>coordinator/students" class="nav-item <?php echo strpos($currentPage, 'coordinator/students') !== false ? 'active' : ''; ?>">
                            <i class="fas fa-user-graduate me-2"></i> Students
                        </a>
                        <a href="<?php echo BASE_URL; ?>coordinator/organizations" class="nav-item <?php echo strpos($currentPage, 'coordinator/organizations') !== false ? 'active' : ''; ?>">
                            <i class="fas fa-building me-2"></i> Organizations
                        </a>
                        <a href="<?php echo BASE_URL; ?>coordinator/placements" class="nav-item <?php echo strpos($currentPage, 'coordinator/placements') !== false ? 'active' : ''; ?>">
                            <i class="fas fa-clipboard-list me-2"></i> Placements
                        </a>
                        <a href="<?php echo BASE_URL; ?>coordinator/supervisors" class="nav-item <?php echo strpos($currentPage, 'coordinator/supervisors') !== false ? 'active' : ''; ?>">
                            <i class="fas fa-user-tie me-2"></i> Supervisors
                        </a>
                        <a href="<?php echo BASE_URL; ?>coordinator/logbooks" class="nav-item <?php echo strpos($currentPage, 'coordinator/logbooks') !== false ? 'active' : ''; ?>">
                            <i class="fas fa-book me-2"></i> Logbooks
                        </a>
                        <a href="<?php echo BASE_URL; ?>coordinator/pendingReports" class="nav-item <?php echo strpos($currentPage, 'coordinator/pendingReports') !== false ? 'active' : ''; ?>">
                            <i class="fas fa-file-alt me-2"></i> Reports
                        </a>
                        <a href="<?php echo BASE_URL; ?>coordinator/evaluations" class="nav-item <?php echo strpos($currentPage, 'coordinator/evaluations') !== false ? 'active' : ''; ?>">
                            <i class="fas fa-star me-2"></i> Evaluations
                        </a>
                        <a href="<?php echo BASE_URL; ?>coordinator/analytics" class="nav-item <?php echo strpos($currentPage, 'coordinator/analytics') !== false ? 'active' : ''; ?>">
                            <i class="fas fa-chart-bar me-2"></i> Reports & Analytics
                        </a>
                    
                    <?php elseif ($role === 'supervisor'): ?>
                        <a href="<?php echo BASE_URL; ?>supervisor/dashboard" class="nav-item <?php echo strpos($currentPage, 'supervisor/dashboard') !== false ? 'active' : ''; ?>">
                            <i class="fas fa-home me-2"></i> Dashboard
                        </a>
                        <a href="<?php echo BASE_URL; ?>supervisor/students" class="nav-item <?php echo strpos($currentPage, 'supervisor/students') !== false ? 'active' : ''; ?>">
                            <i class="fas fa-user-graduate me-2"></i> My Students
                        </a>
                        <a href="<?php echo BASE_URL; ?>supervisor/logbooks" class="nav-item <?php echo strpos($currentPage, 'supervisor/logbooks') !== false ? 'active' : ''; ?>">
                            <i class="fas fa-book me-2"></i> Logbooks
                        </a>
                        <a href="<?php echo BASE_URL; ?>supervisor/reports" class="nav-item <?php echo strpos($currentPage, 'supervisor/reports') !== false ? 'active' : ''; ?>">
                            <i class="fas fa-file-alt me-2"></i> Reports
                        </a>
                        <a href="<?php echo BASE_URL; ?>supervisor/profile" class="nav-item <?php echo strpos($currentPage, 'supervisor/profile') !== false ? 'active' : ''; ?>">
                            <i class="fas fa-user me-2"></i> Profile
                        </a>
                    <?php endif; ?>
                    
                    <a href="<?php echo BASE_URL; ?>auth/logout" class="nav-item text-danger">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                </nav>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <!-- Top Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light sticky-top">
                    <div class="container-fluid">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        
                        <span class="navbar-brand ms-3">
                            <?php echo $pageTitle ?? 'Dashboard'; ?>
                        </span>
                        
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav ms-auto">
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-user-circle me-1"></i> 
                                        <?php echo $_SESSION['name'] ?? 'User'; ?>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="<?php echo BASE_URL . $role; ?>/profile">
                                            <i class="fas fa-user me-2"></i> Profile
                                        </a></li>
                                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>auth/change-password">
                                            <i class="fas fa-key me-2"></i> Change Password
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="<?php echo BASE_URL; ?>auth/logout">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                
                <!-- Page Content -->
                <main class="main-content">
