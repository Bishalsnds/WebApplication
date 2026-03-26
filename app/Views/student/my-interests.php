<?php
$token = htmlspecialchars(\App\Core\Auth::csrfToken());
$emailValue = htmlspecialchars($email ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Interests - Student Course Hub</title>
    <link rel="stylesheet" href="/WebApplication/public/css/style.css" />
</head>
<body>
    <a class="skip-link" href="#main-content">Skip to main content</a>
    <header>
        <h1>Student <span>Course Hub</span></h1>
        <nav>
            <a href="/WebApplication/public/programmes">Programmes</a>
            <a href="/WebApplication/public/staff">Staff</a>
            <a class="active" href="/WebApplication/public/my-interests">My Interests</a>
            <a href="/WebApplication/public/admin-login">Admin Login</a>
        </nav>
    </header>

    <main id="main-content">
        <section class="content">
            <div class="content-header">
                <h2>Manage My Interests</h2>
                <p>Enter your email to view and withdraw programme interests.</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <?php if (!empty($removed)): ?>
                <div class="alert alert-success">Interest withdrawn successfully.</div>
            <?php endif; ?>

            <form method="get" action="/WebApplication/public/my-interests" class="filter-form">
                <label class="sr-only" for="email">Email</label>
                <input id="email" name="email" type="email" required placeholder="you@example.com" value="<?php echo $emailValue; ?>" />
                <button type="submit" class="btn btn-ghost">View My Interests</button>
            </form>

            <?php if (!empty($email)): ?>
                <div class="table-wrap" style="margin-top: 1.2rem;">
                    <table class="data-table">
                        <caption>Your current interest registrations</caption>
                        <thead>
                            <tr>
                                <th scope="col">Programme</th>
                                <th scope="col">Level</th>
                                <th scope="col">Registered At</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($rows)): ?>
                                <tr>
                                    <td colspan="4">No interests found for this email.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($rows as $row): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['ProgrammeName']); ?></td>
                                        <td><?php echo htmlspecialchars($row['LevelName'] ?? 'N/A'); ?></td>
                                        <td><?php echo htmlspecialchars($row['RegisteredAt']); ?></td>
                                        <td>
                                            <form method="post" action="/WebApplication/public/withdraw-interest" class="inline-form" onsubmit="return confirm('Withdraw this interest?');">
                                                <input type="hidden" name="_token" value="<?php echo $token; ?>" />
                                                <input type="hidden" name="interest_id" value="<?php echo (int) $row['InterestID']; ?>" />
                                                <input type="hidden" name="email" value="<?php echo $emailValue; ?>" />
                                                <button type="submit" class="btn btn-ghost">Withdraw</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 Student Course Hub. All rights reserved.</p>
    </footer>
</body>
</html>
