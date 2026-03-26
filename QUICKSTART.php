<?php
// QUICK START GUIDE FOR DEVELOPERS
// Follow these steps to get the application running in 5 minutes

/*

## ðŸš€ QUICK START (5 MINUTES)

### Step 1: Copy Project
- Copy this `WebApplication` folder to `C:\xampp\htdocs\WebApplication`

### Step 2: Database Setup
1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Create database: `student_course_hub`
3. Import your SQL file (example-data-Copy(1).sql)
4. Import migration: Run migrations/001_create_users_table.sql

### Step 3: Start Services
- Open XAMPP Control Panel
- Click "Start" for Apache and MySQL

### Step 4: Test Login
1. Go to: http://localhost/WebApplication/public/
2. Click "Admin Login"
3. Use: admin@example.com / Admin@123
4. You should see the admin dashboard

### Step 5: You're Done! âœ…
- Add, Edit, Delete programmes
- See SETUP_DATABASE.md for more details

---

## ðŸ“š COMPLETE GUIDES
- SETUP_DATABASE.md - Full database configuration
- ADMIN_GUIDE.md - Admin feature documentation
- README.md - Project overview & architecture

---

## ðŸ”§ CONFIGURATION

File: config/config.php

$config = [
    'db' => [
        'host' => 'localhost',           // MySQL server
        'name' => 'student_course_hub',  // Database name
        'user' => 'root',                // MySQL user (XAMPP default)
        'pass' => '',                    // MySQL password (XAMPP default: empty)
        'charset' => 'utf8mb4',
    ],
];

---

## ðŸž COMMON ISSUES

Issue: "Database connection failed"
Fix: Check MySQL is running in XAMPP, verify credentials

Issue: "Unable to login"
Fix: Run migrations/001_create_users_table.sql, verify Users table exists

Issue: "Admin routes not working"
Fix: Check .htaccess exists in public/, restart Apache

---

## ðŸ‘¤ DEFAULT ADMIN USER
Email: admin@example.com
Password: Admin@123
Role: admin

(Change password after first login!)

---

## ðŸ“ NEXT STEPS
1. âœ… Database configured
2. âœ… Admin authentication working
3. ðŸ“‹ Next: Add student interest management
4. ðŸ“‹ Then: Add module management
5. ðŸ“‹ Then: Add staff management

*/

// Simply open this file in a browser to see the quick start guide
// Or read the comment block above while developing

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quick Start Guide</title>
    <link rel="stylesheet" href="/WebApplication/public/css/style.css" />
</head>
<body>
    <header>
        <h1>Student Course Hub - Quick Start Guide</h1>
        <nav>
            <a href="/WebApplication/public/">Home</a>
        </nav>
    </header>

    <main>
        <div class="feature" style="max-width: 900px; margin: 2rem auto;">
            <h2>ðŸš€ Get Running in 5 Minutes</h2>

            <h3 style="margin-top: 2rem; color: #16a085;">Step 1: Copy Project</h3>
            <p>Copy this <code>WebApplication</code> folder to <code>C:\xampp\htdocs\WebApplication</code></p>

            <h3 style="margin-top: 2rem; color: #16a085;">Step 2: Database Setup</h3>
            <ol>
                <li>Open <strong>phpMyAdmin</strong>: <a href="http://localhost/phpmyadmin" target="_blank">http://localhost/phpmyadmin</a></li>
                <li>Create database: <code>student_course_hub</code></li>
                <li>Import your SQL file: <code>example-data-Copy(1).sql</code></li>
                <li>Run migration: <code>migrations/001_create_users_table.sql</code></li>
            </ol>

            <h3 style="margin-top: 2rem; color: #16a085;">Step 3: Start Services</h3>
            <ol>
                <li>Open <strong>XAMPP Control Panel</strong></li>
                <li>Click "Start" for <strong>Apache</strong></li>
                <li>Click "Start" for <strong>MySQL</strong></li>
            </ol>

            <h3 style="margin-top: 2rem; color: #16a085;">Step 4: Test Login</h3>
            <ol>
                <li>Go to: <a href="http://localhost/WebApplication/public/" target="_blank">http://localhost/WebApplication/public/</a></li>
                <li>Click "Admin Login"</li>
                <li>Use credentials:
                    <ul>
                        <li><strong>Email:</strong> <code>admin@example.com</code></li>
                        <li><strong>Password:</strong> <code>Admin@123</code></li>
                    </ul>
                </li>
                <li>You should see the Admin Dashboard âœ…</li>
            </ol>

            <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 6px; margin-top: 2rem;">
                <strong>âœ… You're Done!</strong> You can now:
                <ul>
                    <li>Add new programmes</li>
                    <li>Edit programme details</li>
                    <li>Delete outdated programmes</li>
                    <li>View all programmes in admin dashboard</li>
                </ul>
            </div>

            <h3 style="margin-top: 2rem; color: #16a085;">ðŸ“š Read Full Guides</h3>
            <ul>
                <li><a href="/WebApplication/SETUP_DATABASE.md" target="_blank"><strong>SETUP_DATABASE.md</strong></a> - Complete database configuration guide</li>
                <li><a href="/WebApplication/ADMIN_GUIDE.md" target="_blank"><strong>ADMIN_GUIDE.md</strong></a> - Admin features documentation</li>
                <li><a href="/WebApplication/README.md" target="_blank"><strong>README.md</strong></a> - Project overview & architecture</li>
            </ul>

            <h3 style="margin-top: 2rem; color: #16a085;">ðŸž If Something Goes Wrong</h3>
            <ul>
                <li><strong>"Database connection failed"</strong> â†’ Check MySQL is running in XAMPP, verify credentials in config/config.php</li>
                <li><strong>"Can't login even with correct credentials"</strong> â†’ Verify Users table exists (run migration SQL)</li>
                <li><strong>"Admin routes not working"</strong> â†’ Check .htaccess exists in public/ folder, restart Apache</li>
            </ul>

            <h3 style="margin-top: 2rem; color: #16a085;">ðŸ”‘ Default Credentials</h3>
            <p>
                <strong>Email:</strong> <code>admin@example.com</code><br>
                <strong>Password:</strong> <code>Admin@123</code><br>
                <strong>Role:</strong> <code>admin</code>
            </p>
            <div style="background: #fff3cd; color: #856404; padding: 1rem; border-radius: 6px;">
                âš ï¸ Change this password after first login for security!
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2026 Student Course Hub. All rights reserved.</p>
    </footer>
</body>
</html>

