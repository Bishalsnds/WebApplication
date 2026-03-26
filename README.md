# Student Course Hub

Student Course Hub is a PHP MVC web application for showcasing university programmes, capturing student interest, and giving administrators secure tools to manage programme data and mailing lists.

## Overview

The app supports three perspectives:

1. Student perspective: browse programmes, search/filter content, see module breakdowns and staff details, and register or withdraw interest.
2. Administrator perspective: secure login, programme/module management, publish control, and mailing list reporting/export/cleanup.
3. Staff perspective: view modules led and programmes impacted, with rich staff profiles.

## Key Features

### Student-facing features

1. Browse published undergraduate and postgraduate programmes.
2. Search programmes by keyword and filter by level.
3. View programme details, modules by year, module leaders, and shared-module information.
4. View programme/module images with accessible database-backed alt descriptions.
5. Register interest in a programme.
6. Manage and withdraw interest by email.

### Administrator features

1. Admin authentication with role checks.
2. Add, edit, delete programmes.
3. Publish/unpublish programmes.
4. Add, edit, delete modules and assign module leaders.
5. Mailing list matrix and detailed interest log.
6. Filter mailing list by programme.
7. Export mailing list CSV.
8. Remove duplicate and invalid interest records.
9. Remove individual interest records.

### Staff features

1. Staff directory selection page.
2. Rich staff profiles: photo, job title, department, bio.
3. Modules led by selected staff member.
4. Programmes that include those modules.

### Security and data protection

1. Password hashing (bcrypt).
2. Session-based authentication.
3. Role-based authorization for admin routes.
4. CSRF protection on state-changing forms.
5. Prepared statements (PDO) and output escaping.

### Performance and accessibility

1. Added targeted indexes for search, filtering, joins, and reporting queries.
2. Added optional FULLTEXT search path for programme keyword queries with safe fallback.
3. Added image alt-text columns in database and wired alt text to UI.
4. Responsive layout and keyboard-friendly navigation support.

## Technology Stack

1. Backend: PHP (MVC architecture)
2. Database: MySQL / MariaDB
3. Frontend: HTML + CSS
4. Web server: Apache (XAMPP)

## Project Structure

1. [app/Controllers](app/Controllers): route handlers (student/admin/staff/auth).
2. [app/Models](app/Models): data access and query logic.
3. [app/Views](app/Views): PHP templates for admin/auth/student/staff pages.
4. [app/Core](app/Core): bootstrap, router, auth, database connection.
5. [public](public): web root (entry point, CSS, images, static pages).
6. [migrations](migrations): schema evolution scripts.
7. [config/config.php](config/config.php): app and DB configuration.

## Setup and Run

1. Place project in `C:\xampp\htdocs\WebApplication`.
2. Start Apache and MySQL in XAMPP.
3. Create/import database `student_course_hub`.
4. Run migration files in [migrations](migrations) in order:
    1. [migrations/001_create_users_table.sql](migrations/001_create_users_table.sql)
    2. [migrations/002_add_programmes_publish_flag.sql](migrations/002_add_programmes_publish_flag.sql)
    3. [migrations/003_add_staff_profile_fields.sql](migrations/003_add_staff_profile_fields.sql)
    4. [migrations/004_add_image_alt_text_columns.sql](migrations/004_add_image_alt_text_columns.sql)
    5. [migrations/005_add_search_performance_indexes.sql](migrations/005_add_search_performance_indexes.sql)
5. Open:
    1. Student site: `http://localhost/WebApplication/public/programmes`
    2. Staff perspective: `http://localhost/WebApplication/public/staff`
    3. Admin login: `http://localhost/WebApplication/public/admin-login`

Default admin account:

1. Email: `admin@example.com`
2. Password: `Admin@123`

## Main Routes

1. Student:
    1. `/programmes`
    2. `/programme?id={id}`
    3. `/interest?programme_id={id}`
    4. `/my-interests`
2. Staff:
    1. `/staff`
3. Admin:
    1. `/admin-dashboard`
    2. `/admin-modules`
    3. `/admin-mailing-list`

## Related Docs

1. [SETUP_DATABASE.md](SETUP_DATABASE.md): database setup and admin user setup.
2. [database.md](database.md): design and enhancement notes from the assignment.
3. [requirement.md](requirement.md): user stories and assignment scenario.

## Notes

1. The app is configured for local XAMPP usage.
2. For production, disable development tools, enforce HTTPS, and rotate default credentials.

This project is for educational purposes.

---

## 📞 Support

For issues or questions:
1. Check [SETUP_DATABASE.md](SETUP_DATABASE.md) for database troubleshooting
2. Review [ADMIN_GUIDE.md](ADMIN_GUIDE.md) for feature documentation
3. Check application logs in `storage/logs/`
4. Review PHP error logs in Apache error log

---

## 🎓 Learning Resources

- **MVC Architecture**: [Wikipedia - MVC](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller)
- **PDO Security**: [PHP.net - PDO Prepared Statements](https://www.php.net/manual/en/pdo.prepared-statements.php)
- **Password Hashing**: [PHP.net - password_hash](https://www.php.net/manual/en/function.password-hash.php)
- **RESTful Design**: [REST API Best Practices](https://restfulapi.net/)

---

**Last Updated**: March 22, 2026  
**Version**: 1.0.0

