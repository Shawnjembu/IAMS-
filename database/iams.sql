-- Industrial Attachment Management System (IAMS) Database Schema
-- XAMPP MySQL Database

-- Drop database if exists and create new one
DROP DATABASE IF EXISTS iams;
CREATE DATABASE iams CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE iams;

-- ========================================
-- USERS TABLES (Separate tables for each role)
-- ========================================

-- Students table
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_number VARCHAR(50) UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    program VARCHAR(100),
    year INT,
    semester VARCHAR(20),
    organization_id INT,
    supervisor_id INT,
    status ENUM('pending', 'approved', 'rejected', 'active', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_student_number (student_number),
    INDEX idx_organization (organization_id),
    INDEX idx_supervisor (supervisor_id)
) ENGINE=InnoDB;

-- Organizations table
CREATE TABLE organizations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    organization_name VARCHAR(200) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    address TEXT,
    phone VARCHAR(20),
    contact_person VARCHAR(100),
    industry_type VARCHAR(100),
    description TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- Coordinators table (Academic staff who manage the program)
CREATE TABLE coordinators (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    department VARCHAR(100),
    position VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB;

-- Supervisors table (Organization supervisors for students)
CREATE TABLE supervisors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    position VARCHAR(100),
    organization_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_organization (organization_id)
) ENGINE=InnoDB;

-- ========================================
-- PLACEMENT & ATTACHMENT TABLES
-- ========================================

-- Placements table
CREATE TABLE placements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    organization_id INT NOT NULL,
    supervisor_id INT,
    start_date DATE,
    end_date DATE,
    status ENUM('pending', 'approved', 'rejected', 'active', 'completed', 'cancelled') DEFAULT 'pending',
    comments TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (organization_id) REFERENCES organizations(id) ON DELETE CASCADE,
    FOREIGN KEY (supervisor_id) REFERENCES supervisors(id) ON DELETE SET NULL,
    INDEX idx_student (student_id),
    INDEX idx_organization (organization_id),
    INDEX idx_supervisor (supervisor_id),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- ========================================
-- LOGBOOK & REPORT TABLES
-- ========================================

-- Logbooks table (Weekly student reports)
CREATE TABLE logbooks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    week_number INT NOT NULL,
    week_start DATE NOT NULL,
    week_end DATE NOT NULL,
    activities TEXT NOT NULL,
    learning_outcomes TEXT,
    challenges TEXT,
    comments TEXT,
    status ENUM('pending', 'submitted', 'reviewed', 'approved', 'rejected') DEFAULT 'submitted',
    reviewed_by INT,
    reviewed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES supervisors(id) ON DELETE SET NULL,
    INDEX idx_student (student_id),
    INDEX idx_week (week_number),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- Reports table (Final attachment reports)
CREATE TABLE reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    title VARCHAR(200),
    file_path VARCHAR(255) NOT NULL,
    file_name VARCHAR(200),
    file_size INT,
    description TEXT,
    status ENUM('pending', 'submitted', 'reviewed', 'approved', 'rejected') DEFAULT 'submitted',
    reviewed_by INT,
    reviewed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES supervisors(id) ON DELETE SET NULL,
    INDEX idx_student (student_id),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- ========================================
-- EVALUATION TABLES
-- ========================================

-- Evaluations table (Supervisor evaluations of students)
CREATE TABLE evaluations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    supervisor_id INT NOT NULL,
    placement_id INT,
    attendance_score DECIMAL(5,2),
    performance_score DECIMAL(5,2),
    professionalism_score DECIMAL(5,2),
    learning_score DECIMAL(5,2),
    overall_score DECIMAL(5,2),
    strengths TEXT,
    weaknesses TEXT,
    comments TEXT,
    status ENUM('draft', 'submitted') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (supervisor_id) REFERENCES supervisors(id) ON DELETE CASCADE,
    FOREIGN KEY (placement_id) REFERENCES placements(id) ON DELETE SET NULL,
    INDEX idx_student (student_id),
    INDEX idx_supervisor (supervisor_id),
    INDEX idx_placement (placement_id)
) ENGINE=InnoDB;

-- ========================================
-- NOTIFICATION TABLES
-- ========================================

-- Notifications table
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    user_type ENUM('student', 'organization', 'coordinator', 'supervisor') NOT NULL,
    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info', 'success', 'warning', 'error') DEFAULT 'info',
    is_read TINYINT(1) DEFAULT 0,
    link VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user (user_id, user_type),
    INDEX idx_is_read (is_read),
    INDEX idx_created (created_at)
) ENGINE=InnoDB;

-- ========================================
-- APPLICATIONS TABLE
-- ========================================

-- Applications table (Student applications to organizations)
CREATE TABLE applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    organization_id INT NOT NULL,
    cover_letter TEXT,
    status ENUM('pending', 'accepted', 'rejected', 'withdrawn') DEFAULT 'pending',
    response_date TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (organization_id) REFERENCES organizations(id) ON DELETE CASCADE,
    INDEX idx_student (student_id),
    INDEX idx_organization (organization_id),
    INDEX idx_status (status)
) ENGINE=InnoDB;

-- ========================================
-- DEFAULT ADMIN ACCOUNT
-- ========================================

-- Insert default admin (password: password)
INSERT INTO coordinators (full_name, email, password, phone, department, position) 
VALUES (
    'System Administrator', 
    'admin@example.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    '1234567890', 
    'IT Department', 
    'System Admin'
);

-- Insert default student (password: password)
INSERT INTO students (student_number, full_name, email, password, phone, program, year, semester, status) 
VALUES (
    'STU001', 
    'John Student', 
    'student@example.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    '9876543210', 
    'Computer Science', 
    3, 
    'Fall 2024',
    'pending'
);

-- Insert default supervisor (password: password)
INSERT INTO supervisors (full_name, email, password, phone, position, organization_id) 
VALUES (
    'Jane Supervisor', 
    'supervisor@example.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    '5551234567', 
    'Senior Manager', 
    NULL
);

-- Insert sample organization
INSERT INTO organizations (organization_name, email, password, address, phone, contact_person, industry_type, description, status) 
VALUES (
    'Tech Solutions Inc.', 
    'company@example.com', 
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
    '123 Tech Street, Innovation City', 
    '5559876543', 
    'John Smith', 
    'Information Technology', 
    'A leading technology solutions provider offering software development and IT consulting services.',
    'approved'
);

-- ========================================
-- VIEW: Student Dashboard Data
-- ========================================

CREATE VIEW v_student_dashboard AS
SELECT 
    s.id,
    s.student_number,
    s.full_name,
    s.email,
    s.program,
    s.year,
    s.status as student_status,
    o.id as organization_id,
    o.organization_name,
    o.address as org_address,
    sup.id as supervisor_id,
    sup.full_name as supervisor_name,
    p.start_date,
    p.end_date,
    p.status as placement_status,
    (SELECT COUNT(*) FROM logbooks WHERE student_id = s.id) as logbook_count,
    (SELECT COUNT(*) FROM reports WHERE student_id = s.id) as report_count
FROM students s
LEFT JOIN placements p ON s.id = p.student_id AND p.status IN ('active', 'approved')
LEFT JOIN organizations o ON p.organization_id = o.id
LEFT JOIN supervisors sup ON p.supervisor_id = sup.id;

-- ========================================
-- VIEW: Coordinator Dashboard Data
-- ========================================

CREATE VIEW v_coordinator_dashboard AS
SELECT 
    (SELECT COUNT(*) FROM students) as total_students,
    (SELECT COUNT(*) FROM organizations WHERE status = 'approved') as total_organizations,
    (SELECT COUNT(*) FROM placements WHERE status = 'pending') as pending_placements,
    (SELECT COUNT(*) FROM placements WHERE status = 'active') as active_placements,
    (SELECT COUNT(*) FROM placements WHERE status = 'completed') as completed_placements,
    (SELECT COUNT(*) FROM logbooks WHERE status = 'submitted') as pending_logbooks,
    (SELECT COUNT(*) FROM reports WHERE status = 'submitted') as pending_reports;
