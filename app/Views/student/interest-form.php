<?php
$token = htmlspecialchars(\App\Core\Auth::csrfToken());
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register Interest - Student Course Hub</title>
    <link rel="stylesheet" href="/WebApplication/public/css/style.css" />
</head>
<body>
    <a class="skip-link" href="#main-content">Skip to main content</a>
    <header>
        <h1>Student <span>Course Hub</span></h1>
        <nav>
            <a href="/WebApplication/public/programmes">Programmes</a>
            <a href="/WebApplication/public/staff">Staff</a>
            <a href="/WebApplication/public/admin-login">Admin Login</a>
        </nav>
    </header>

    <main id="main-content">
        <section class="content">
            <div class="content-header">
                <h2>Register Interest</h2>
                <p><?php echo htmlspecialchars($programme['ProgrammeName']); ?></p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success">Thank you. Your interest has been recorded successfully.</div>
            <?php endif; ?>

            <form method="post" action="/WebApplication/public/interest">
                <input type="hidden" name="_token" value="<?php echo $token; ?>" />
                <input type="hidden" name="programme_id" value="<?php echo (int) $programme['ProgrammeID']; ?>" />

                <label for="student_name">Full Name</label>
                <input id="student_name" name="student_name" type="text" required value="<?php echo htmlspecialchars($_POST['student_name'] ?? ''); ?>" />

                <label for="email">Email</label>
                <input id="email" name="email" type="email" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" />

                <button type="submit" class="btn-submit">Submit Interest</button>
            </form>

            <p style="margin-top: 1rem;">
                <a class="btn btn-ghost" href="/WebApplication/public/programme?id=<?php echo (int) $programme['ProgrammeID']; ?>">Back to Programme</a>
            </p>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 Student Course Hub. All rights reserved.</p>
    </footer>
</body>
</html>
