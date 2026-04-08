# IAMS — Industrial Attachment Management System

A web-based system for managing student industrial attachment placements, built with PHP (custom MVC), MySQL, and Bootstrap 5.

## Features

| Role | Capabilities |
|------|-------------|
| **Coordinator** | Manage students, approve organizations, create placements, view logbooks & reports, analytics dashboard |
| **Student** | Browse organizations, apply for placements, submit weekly logbooks, upload reports, track notifications |
| **Organization** | Review applications, manage attached students, receive notifications |
| **Supervisor** | Review student logbooks and reports, submit evaluations |

## Tech Stack

- **Backend:** PHP 8+ — custom MVC framework (no Laravel/Symfony)
- **Database:** MySQL via `mysqli`
- **Frontend:** Bootstrap 5, Font Awesome 6, Vanilla JS
- **Server:** PHP built-in dev server or Apache (XAMPP)

## Project Structure

```
IAMS/
├── app/
│   ├── controllers/      # AuthController, StudentController, OrganizationController, etc.
│   ├── models/           # Student, Organization, Placement, Logbook, Report, Evaluation, etc.
│   └── views/            # Role-based views + shared layouts (header/footer)
├── config/
│   └── database.php      # DB credentials and connection helpers
├── core/
│   ├── Auth.php          # Session-based authentication & role management
│   ├── Controller.php    # Base controller (view rendering, redirects)
│   ├── Model.php         # Base model
│   └── Router.php        # URL router with regex pattern matching
├── database/
│   └── iams.sql          # Full schema + seed data
└── public/
    ├── index.php          # Front controller & route definitions
    └── router.php         # PHP built-in server router script
```

## Requirements

- **PHP 8.0+** with the `mysqli` extension enabled
- **MySQL 5.7+** or MariaDB 10+
- **XAMPP** (recommended) — provides both PHP with mysqli and MySQL

## Setup

### 1. Clone the repository

```bash
git clone <repo-url>
cd IAMS
```

### 2. Import the database

```bash
C:\xampp\mysql\bin\mysql -u root < database/iams.sql
```

Or via phpMyAdmin: navigate to `http://localhost/phpmyadmin`, create a database named `iams`, then import `database/iams.sql`.

### 3. Configure the database (if needed)

Edit [config/database.php](config/database.php) to match your MySQL credentials:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');   // set your password if applicable
define('DB_NAME', 'iams');
```

### 4. Start the server

**Start MySQL first:**

```bash
# Option A: via XAMPP batch script
C:\xampp\mysql_start.bat

# Option B: directly
C:\xampp\mysql\bin\mysqld --defaults-file="C:\xampp\mysql\bin\my.ini" --standalone
```

**Then start the PHP development server** using XAMPP's PHP (which has `mysqli` enabled):

```bash
cd public
C:\xampp\php\php.exe -S localhost:8000 router.php
```

Open **http://localhost:8000** in your browser.

> **Note:** The system must be served through `router.php` for URL routing to work. Do not open PHP files directly in the browser.

### Alternative: XAMPP Apache

Place the project in `C:\xampp\htdocs\IAMS`, start Apache and MySQL from the XAMPP Control Panel, then open `http://localhost/IAMS/public/`.

## Default Test Accounts

All accounts use the password: **`password`**

| Role | Email |
|------|-------|
| Coordinator | admin@example.com |
| Student | student@example.com |
| Organization | company@example.com |
| Supervisor | supervisor@example.com |

## Application Routes

| Prefix | Description |
|--------|-------------|
| `/auth/login` | Login page |
| `/auth/register-student` | Student registration |
| `/auth/register-organization` | Organization registration |
| `/auth/forgot-password` | Password reset |
| `/student/*` | Student dashboard, applications, logbooks, reports |
| `/organization/*` | Organization dashboard, applications, students |
| `/coordinator/*` | Coordinator dashboard, placements, analytics |
| `/supervisor/*` | Supervisor dashboard, logbook/report reviews, evaluations |

## Security

- Passwords hashed with `password_hash()` / verified with `password_verify()`
- Session-based authentication with role enforcement on every route
- SQL queries use `mysqli_real_escape_string` for input sanitization
- Role-based access control — each dashboard checks session role before rendering

## File Uploads

- Logbooks: stored in `assets/uploads/logbooks/`
- Reports: stored in `assets/uploads/reports/`
- Allowed types: PDF, DOC, DOCX, ZIP (max 10 MB)

## Troubleshooting

| Problem | Fix |
|---------|-----|
| `mysqli_connect()` undefined | Use XAMPP's PHP (`C:\xampp\php\php.exe`), not a standalone install |
| 404 on all routes | Ensure you're running the server with `router.php` as the router script |
| Database connection failed | Confirm MySQL is running and credentials in `config/database.php` are correct |
| Blank page / layout missing | Always access via the PHP server — do not open view files directly |

## License

This project is developed for educational purposes as part of a software engineering course.
