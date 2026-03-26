<?php
// app/Views/auth/login.php
// Admin login form view
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login - Student Course Hub</title>
    <link rel="stylesheet" href="/WebApplication/public/css/style.css" />
    <style>
        .auth-wrap {
            width: min(1040px, 100%);
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.05fr 1fr;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-lg);
            background: #fff;
            animation: fadeUp 0.5s ease both;
        }

        .auth-panel {
            background: linear-gradient(160deg, var(--navy) 0%, var(--navy-mid) 65%, #203a52 100%);
            color: #fff;
            padding: 2.4rem 2rem;
            position: relative;
            isolation: isolate;
        }

        .auth-panel::after {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 14% 18%, rgba(232,139,90,.28), transparent 26%),
                radial-gradient(circle at 82% 86%, rgba(255,255,255,.08), transparent 30%);
            z-index: -1;
        }

        .auth-kicker {
            display: inline-flex;
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.14em;
            color: #f9d2bf;
            border: 1px solid rgba(255,255,255,.25);
            border-radius: 999px;
            padding: 0.25rem 0.55rem;
            margin-bottom: 1.1rem;
        }

        .auth-panel h2 {
            font-family: 'DM Serif Display', serif;
            font-weight: 400;
            font-size: clamp(1.6rem, 2.8vw, 2.15rem);
            line-height: 1.18;
            margin-bottom: 0.85rem;
        }

        .auth-panel p {
            color: rgba(255,255,255,.84);
            font-size: 0.9rem;
            max-width: 34ch;
            margin-bottom: 1.5rem;
        }

        .auth-points {
            list-style: none;
            display: grid;
            gap: 0.65rem;
        }

        .auth-points li {
            display: flex;
            align-items: center;
            gap: 0.55rem;
            font-size: 0.84rem;
            color: rgba(255,255,255,.92);
        }

        .auth-points li::before {
            content: '';
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--accent-lt);
            flex-shrink: 0;
        }

        .login-card {
            padding: 2.2rem 1.8rem;
            background: linear-gradient(180deg, #ffffff 0%, var(--paper) 100%);
        }

        .login-title {
            font-family: 'DM Serif Display', serif;
            font-size: 1.7rem;
            font-weight: 400;
            margin-bottom: 0.2rem;
            color: var(--navy);
        }

        .login-subtitle {
            font-size: 0.9rem;
            color: var(--muted);
            margin-bottom: 1.35rem;
        }

        .error-message {
            background: #fbe5e8;
            border: 1px solid #f2b7c1;
            color: #8b1f35;
            padding: 0.75rem 0.85rem;
            border-radius: var(--radius-sm);
            margin-bottom: 0.9rem;
            font-size: 0.84rem;
        }

        .demo-credentials {
            background: #fff1e8;
            border: 1px solid #f3c8af;
            color: #6c3c23;
            padding: 0.7rem 0.8rem;
            border-radius: var(--radius-sm);
            margin-bottom: 0.95rem;
            font-size: 0.8rem;
            line-height: 1.5;
        }

        .demo-credentials strong {
            display: block;
            margin-bottom: 0.3rem;
            font-size: 0.74rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .login-form {
            display: grid;
            gap: 0.1rem;
            background: transparent;
            border: none;
            box-shadow: none;
            padding: 0;
        }

        .field-group {
            margin-bottom: 0.75rem;
        }

        .field-group:last-of-type {
            margin-bottom: 0.2rem;
        }

        .login-form label {
            margin-bottom: 0.3rem;
        }

        .login-form input {
            margin-bottom: 0;
            min-height: 42px;
        }

        .login-form button {
            margin-top: 0.5rem;
            min-height: 44px;
        }

        .back-link {
            margin-top: 1rem;
            text-align: center;
            font-size: 0.84rem;
        }

        .back-link a {
            color: var(--navy-mid);
            text-decoration: none;
            border-bottom: 1px solid transparent;
            transition: border-color var(--transition), color var(--transition);
        }

        .back-link a:hover {
            color: var(--accent);
            border-color: var(--accent);
        }

        @media (max-width: 860px) {
            .auth-wrap {
                grid-template-columns: 1fr;
            }

            .auth-panel,
            .login-card {
                padding: 1.4rem 1rem;
            }

            .auth-panel p {
                max-width: none;
            }
        }
    </style>
</head>
<body>
    <a class="skip-link" href="#main-content">Skip to main content</a>
    <header>
        <h1>Student <span>Course Hub</span></h1>
        <nav>
            <a href="/WebApplication/public/programmes">Programmes</a>
            <a class="active" href="/WebApplication/public/admin-login">Admin Login</a>
        </nav>
    </header>

    <main id="main-content">
        <section class="auth-wrap" aria-label="Administrator login">
            <aside class="auth-panel">
                <span class="auth-kicker">Administrator Access</span>
                <h2>Manage programmes, modules, and communications.</h2>
                <p>Sign in to access the dashboard, maintain programme data, and keep mailing lists clean and up to date.</p>
                <ul class="auth-points">
                    <li>Programme and module management</li>
                    <li>Mailing list exports and cleanup tools</li>
                    <li>Secure role-based access and CSRF protection</li>
                </ul>
            </aside>

            <div class="login-card">
                <h2 class="login-title">Admin Login</h2>
                <p class="login-subtitle">Use your administrator credentials to continue.</p>

                <?php if (isset($error) && !empty($error)): ?>
                    <div class="error-message">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <div class="demo-credentials">
                    <strong>Demo Credentials</strong>
                    Email: <code>admin@example.com</code><br />
                    Password: <code>Admin@123</code>
                </div>

                <form class="login-form" method="POST" action="/WebApplication/public/admin-login">
                    <input type="hidden" name="_token" value="<?php echo htmlspecialchars(\App\Core\Auth::csrfToken()); ?>" />

                    <div class="field-group">
                        <label for="email">Email Address</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="admin@example.com"
                            required
                            autocomplete="email"
                        />
                    </div>

                    <div class="field-group">
                        <label for="password">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            required
                            autocomplete="current-password"
                        />
                    </div>

                    <button type="submit">Sign In to Dashboard</button>
                </form>

                <div class="back-link">
                    <a href="/WebApplication/public/programmes">Back to Programmes</a>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 Student Course Hub. All rights reserved.</p>
    </footer>
</body>
</html>

