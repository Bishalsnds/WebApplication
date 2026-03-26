<?php
$userName = htmlspecialchars($user['name'] ?? 'Admin');
$token = htmlspecialchars(\App\Core\Auth::csrfToken());
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - Student Course Hub</title>
    <link rel="stylesheet" href="/WebApplication/public/css/style.css" />
</head>
<body>
    <a class="skip-link" href="#main-content">Skip to main content</a>
    <header>
        <h1>Student <span>Course Hub</span></h1>
        <nav>
            <a class="active" href="/WebApplication/public/admin-dashboard">Dashboard</a>
            <a href="/WebApplication/public/admin-modules">Modules</a>
            <a href="/WebApplication/public/admin-mailing-list">Mailing List</a>
            <a class="nav-logout" href="/WebApplication/public/logout">Logout</a>
        </nav>
    </header>

    <main id="main-content">
        <section class="content">
            <div class="content-header">
                <h2>Programme Management</h2>
                <p>Welcome, <?php echo $userName; ?>. Create, update, publish, and remove programmes.</p>
            </div>

            <p>
                <a class="btn btn-accent" href="/WebApplication/public/admin-add-programme">Add New Programme</a>
            </p>

            <div class="table-wrap" style="margin-top: 1.5rem;">
                <table class="data-table">
                    <caption>All degree programmes</caption>
                    <thead>
                        <tr>
                            <th scope="col">Programme</th>
                            <th scope="col">Level</th>
                            <th scope="col">Leader</th>
                            <th scope="col">Modules</th>
                            <th scope="col">Status</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($programmes)): ?>
                            <tr>
                                <td colspan="6">No programmes found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($programmes as $programme): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($programme['ProgrammeName']); ?></td>
                                    <td><?php echo htmlspecialchars($programme['LevelName'] ?? 'Not set'); ?></td>
                                    <td><?php echo htmlspecialchars($programme['ProgrammeLeaderName'] ?? 'Not set'); ?></td>
                                    <td><?php echo (int) ($programme['ModuleCount'] ?? 0); ?></td>
                                    <td>
                                        <?php if ((int) ($programme['IsPublished'] ?? 1) === 1): ?>
                                            <span class="badge badge-accent">Published</span>
                                        <?php else: ?>
                                            <span class="badge badge-muted">Unpublished</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="action-row">
                                            <a
                                                class="icon-btn icon-btn-edit"
                                                href="/WebApplication/public/admin-edit-programme?id=<?php echo (int) $programme['ProgrammeID']; ?>"
                                                aria-label="Edit programme <?php echo htmlspecialchars($programme['ProgrammeName']); ?>"
                                                title="Edit"
                                            >
                                                <svg class="icon-glyph" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                                    <path d="M12 20h9" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                            </a>

                                            <?php if ($supportsPublishing): ?>
                                                <form method="post" action="/WebApplication/public/admin-toggle-programme-publish" class="inline-form">
                                                    <input type="hidden" name="_token" value="<?php echo $token; ?>" />
                                                    <input type="hidden" name="programme_id" value="<?php echo (int) $programme['ProgrammeID']; ?>" />
                                                    <input type="hidden" name="is_published" value="<?php echo (int) ($programme['IsPublished'] ?? 1) === 1 ? '0' : '1'; ?>" />
                                                    <button
                                                        type="submit"
                                                        class="icon-btn icon-btn-toggle"
                                                        aria-label="<?php echo (int) ($programme['IsPublished'] ?? 1) === 1 ? 'Unpublish' : 'Publish'; ?> programme <?php echo htmlspecialchars($programme['ProgrammeName']); ?>"
                                                        title="<?php echo (int) ($programme['IsPublished'] ?? 1) === 1 ? 'Unpublish' : 'Publish'; ?>"
                                                    >
                                                        <?php if ((int) ($programme['IsPublished'] ?? 1) === 1): ?>
                                                            <svg class="icon-glyph" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                                                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                                                                <circle cx="12" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="1.8"></circle>
                                                            </svg>
                                                        <?php else: ?>
                                                            <svg class="icon-glyph" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                                                <path d="M17.94 17.94A10.94 10.94 0 0 1 12 19C5 19 1 12 1 12a21.77 21.77 0 0 1 5.06-6.94" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                                                                <path d="M9.9 4.24A10.94 10.94 0 0 1 12 5c7 0 11 7 11 7a22.2 22.2 0 0 1-3.1 4.24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                                                                <line x1="1" y1="1" x2="23" y2="23" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></line>
                                                            </svg>
                                                        <?php endif; ?>
                                                    </button>
                                                </form>
                                            <?php endif; ?>

                                            <form method="post" action="/WebApplication/public/admin-delete-programme" class="inline-form" onsubmit="return confirm('Delete this programme?');">
                                                <input type="hidden" name="_token" value="<?php echo $token; ?>" />
                                                <input type="hidden" name="programme_id" value="<?php echo (int) $programme['ProgrammeID']; ?>" />
                                                <button
                                                    type="submit"
                                                    class="icon-btn icon-btn-delete"
                                                    aria-label="Delete programme <?php echo htmlspecialchars($programme['ProgrammeName']); ?>"
                                                    title="Delete"
                                                >
                                                    <svg class="icon-glyph" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                                        <polyline points="3 6 5 6 21 6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></polyline>
                                                        <path d="M19 6l-1 14H6L5 6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path d="M10 11v6M14 11v6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
                                                        <path d="M9 6V4h6v2" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 Student Course Hub. All rights reserved.</p>
    </footer>
</body>
</html>
