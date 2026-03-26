<?php
$token = htmlspecialchars(\App\Core\Auth::csrfToken());
$isEditing = is_array($editModule);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Module Management - Student Course Hub</title>
    <link rel="stylesheet" href="/WebApplication/public/css/style.css" />
</head>
<body>
    <a class="skip-link" href="#main-content">Skip to main content</a>
    <header>
        <h1>Student <span>Course Hub</span></h1>
        <nav>
            <a href="/WebApplication/public/admin-dashboard">Dashboard</a>
            <a class="active" href="/WebApplication/public/admin-modules">Modules</a>
            <a href="/WebApplication/public/admin-mailing-list">Mailing List</a>
            <a class="nav-logout" href="/WebApplication/public/logout">Logout</a>
        </nav>
    </header>

    <main id="main-content">
        <section class="content">
            <div class="content-header">
                <h2>Module Management</h2>
                <p>Create, update, delete modules and assign them to programmes and years.</p>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error">Please provide the required module fields.</div>
            <?php endif; ?>

            <h3><?php echo $isEditing ? 'Edit Module' : 'Add Module'; ?></h3>
            <form method="post" action="<?php echo $isEditing ? '/WebApplication/public/admin-edit-module' : '/WebApplication/public/admin-add-module'; ?>">
                <input type="hidden" name="_token" value="<?php echo $token; ?>" />
                <?php if ($isEditing): ?>
                    <input type="hidden" name="module_id" value="<?php echo (int) $editModule['ModuleID']; ?>" />
                <?php endif; ?>

                <label for="module_name">Module Name</label>
                <input id="module_name" name="module_name" type="text" required value="<?php echo htmlspecialchars($editModule['ModuleName'] ?? ''); ?>" />

                <label for="module_leader_id">Module Leader</label>
                <select id="module_leader_id" name="module_leader_id" required>
                    <option value="">Select staff member</option>
                    <?php foreach ($staffMembers as $staff): ?>
                        <option value="<?php echo (int) $staff['StaffID']; ?>" <?php echo ((string) ($editModule['ModuleLeaderID'] ?? '') === (string) $staff['StaffID']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($staff['Name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4"><?php echo htmlspecialchars($editModule['Description'] ?? ''); ?></textarea>

                <label for="image">Image URL</label>
                <input id="image" name="image" type="text" value="<?php echo htmlspecialchars($editModule['Image'] ?? ''); ?>" />

                <label for="image_alt">Image Description (Alt Text)</label>
                <input id="image_alt" name="image_alt" type="text" value="<?php echo htmlspecialchars($editModule['ImageAlt'] ?? ''); ?>" />

                <?php if (!$isEditing): ?>
                    <label for="programme_id">Assign to Programme</label>
                    <select id="programme_id" name="programme_id">
                        <option value="">Do not assign now</option>
                        <?php foreach ($programmes as $programme): ?>
                            <option value="<?php echo (int) $programme['ProgrammeID']; ?>"><?php echo htmlspecialchars($programme['ProgrammeName']); ?></option>
                        <?php endforeach; ?>
                    </select>

                    <label for="year">Year of Study</label>
                    <select id="year" name="year">
                        <option value="1">Year 1</option>
                        <option value="2">Year 2</option>
                        <option value="3">Year 3</option>
                        <option value="4">Year 4</option>
                    </select>
                <?php endif; ?>

                <button type="submit" class="btn-submit"><?php echo $isEditing ? 'Update Module' : 'Create Module'; ?></button>
            </form>

            <p style="margin-top: 1rem;">
                <a class="btn btn-ghost" href="/WebApplication/public/admin-modules">Reset Form</a>
            </p>

            <div class="table-wrap" style="margin-top: 2rem;">
                <table class="data-table">
                    <caption>All modules</caption>
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Module</th>
                            <th scope="col">Leader</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($modules)): ?>
                            <tr><td colspan="4">No modules found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($modules as $module): ?>
                                <tr>
                                    <td><?php echo (int) $module['ModuleID']; ?></td>
                                    <td><?php echo htmlspecialchars($module['ModuleName']); ?></td>
                                    <td><?php echo htmlspecialchars($module['ModuleLeaderName'] ?? 'Not set'); ?></td>
                                    <td>
                                        <div class="action-row">
                                            <a
                                                class="icon-btn"
                                                href="/WebApplication/public/admin-modules?id=<?php echo (int) $module['ModuleID']; ?>"
                                                aria-label="Edit module <?php echo htmlspecialchars($module['ModuleName']); ?>"
                                                title="Edit"
                                            >
                                                &#9998;
                                            </a>
                                            <form method="post" action="/WebApplication/public/admin-delete-module" class="inline-form" onsubmit="return confirm('Delete this module?');">
                                                <input type="hidden" name="_token" value="<?php echo $token; ?>" />
                                                <input type="hidden" name="module_id" value="<?php echo (int) $module['ModuleID']; ?>" />
                                                <button
                                                    type="submit"
                                                    class="icon-btn"
                                                    aria-label="Delete module <?php echo htmlspecialchars($module['ModuleName']); ?>"
                                                    title="Delete"
                                                >
                                                    &#128465;
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
