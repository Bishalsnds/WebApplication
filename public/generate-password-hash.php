<?php
// public/generate-password-hash.php
// ONLY FOR DEVELOPMENT - DO NOT USE IN PRODUCTION
// Use this tool to generate bcrypt password hashes for user creation

// Security Check - Remove this in production
$allowed_ips = ['127.0.0.1', 'localhost', '::1'];
if (!in_array($_SERVER['REMOTE_ADDR'], $allowed_ips)) {
    // Also allow local network for XAMPP
    if ($_SERVER['HTTP_HOST'] !== 'localhost' && strpos($_SERVER['HTTP_HOST'], '127.0.0.1') === false) {
        die('This tool is only accessible from localhost.');
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Password Hash Generator - Development Only</title>
    <link rel="stylesheet" href="/WebApplication/public/css/style.css" />
</head>
<body>
    <header>
        <h1>Password Hash Generator (Development Only)</h1>
        <nav>
            <a href="/">Home</a>
        </nav>
    </header>

    <main>
        <div class="hero">
            <h2>Generate Bcrypt Password Hash</h2>
            <p>Use this tool to create password hashes for user database entries.</p>
            <p style="color: #c0392b; font-weight: bold;">WARNING: Development Only - Remove before production!</p>
        </div>

        <form method="POST" style="max-width: 500px; margin: 2rem auto;">
            <label for="password">Enter Password to Hash</label>
            <input type="password" id="password" name="password" placeholder="e.g., Admin@123" required>
            <button type="submit">Generate Hash</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';
            
            if (empty($password)) {
                echo '<div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 6px; max-width: 500px; margin: 1rem auto;">Password is required.</div>';
            } else {
                $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
                ?>
                <div style="background: #d4edda; color: #155724; padding: 1.5rem; border-radius: 6px; max-width: 600px; margin: 1rem auto; font-family: monospace;">
                    <h3 style="color: #155724;">Generated Hash</h3>
                    <p style="word-break: break-all; background: #fff; padding: 1rem; border-radius: 4px; border: 1px solid #155724;">
                        <?= htmlspecialchars($hash) ?>
                    </p>
                    <p style="margin-top: 1rem; color: #155724;">
                        <strong>Instructions:</strong><br>
                        1. Copy the hash above<br>
                        2. Go to phpMyAdmin -> Users table -> Insert<br>
                        3. Paste this hash in the <code>password_hash</code> field<br>
                        4. Enter user email and role<br>
                        5. Click "Go" to insert the user
                    </p>
                </div>
                <div style="background: #fff3cd; color: #856404; padding: 1rem; border-radius: 6px; max-width: 600px; margin: 1rem auto;">
                    <strong>To verify this hash works:</strong><br>
                    Login with: Email = your_email | Password = <?= htmlspecialchars($password) ?>
                </div>
                <?php
            }
        }
        ?>
    </main>

    <footer>
        <p>&copy; 2026 Student Course Hub. All rights reserved.</p>
    </footer>
</body>
</html>

