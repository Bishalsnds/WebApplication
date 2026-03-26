<?php
$title = $isEdit ? 'Edit Programme' : 'Add Programme';
$action = $isEdit ? '/WebApplication/public/admin-edit-programme' : '/WebApplication/public/admin-add-programme';
$token = htmlspecialchars(\App\Core\Auth::csrfToken());
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $title; ?> - Student Course Hub</title>
    <link rel="stylesheet" href="/WebApplication/public/css/style.css" />
</head>
<body>
    <a class="skip-link" href="#main-content">Skip to main content</a>
    <header>
        <h1>Student <span>Course Hub</span></h1>
        <nav>
            <a href="/WebApplication/public/admin-dashboard">Dashboard</a>
            <a href="/WebApplication/public/admin-modules">Modules</a>
            <a href="/WebApplication/public/admin-mailing-list">Mailing List</a>
            <a class="nav-logout" href="/WebApplication/public/logout">Logout</a>
        </nav>
    </header>

    <main id="main-content">
        <section class="content">
            <div class="content-header">
                <h2><?php echo htmlspecialchars($title); ?></h2>
                <p>Manage programme information shown to prospective students.</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="post" action="<?php echo htmlspecialchars($action); ?>">
                <input type="hidden" name="_token" value="<?php echo $token; ?>" />
                <?php if ($isEdit): ?>
                    <input type="hidden" name="programme_id" value="<?php echo (int) $programme['ProgrammeID']; ?>" />
                <?php endif; ?>

                <label for="programme_name">Programme Name</label>
                <input id="programme_name" name="programme_name" type="text" required value="<?php echo htmlspecialchars($programme['ProgrammeName'] ?? ''); ?>" />

                <label for="level_id">Academic Level</label>
                <select id="level_id" name="level_id">
                    <option value="">Select level</option>
                    <?php foreach ($levels as $level): ?>
                        <option value="<?php echo (int) $level['LevelID']; ?>" <?php echo ((string) ($programme['LevelID'] ?? '') === (string) $level['LevelID']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($level['LevelName']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="programme_leader_id">Programme Leader</label>
                <select id="programme_leader_id" name="programme_leader_id">
                    <option value="">Select staff member</option>
                    <?php foreach ($staffMembers as $staff): ?>
                        <option value="<?php echo (int) $staff['StaffID']; ?>" <?php echo ((string) ($programme['ProgrammeLeaderID'] ?? '') === (string) $staff['StaffID']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($staff['Name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="description">Description</label>
                <textarea id="description" name="description" rows="5"><?php echo htmlspecialchars($programme['Description'] ?? ''); ?></textarea>

                <label for="image">Image URL</label>
                <input id="image" name="image" type="text" value="<?php echo htmlspecialchars($programme['Image'] ?? ''); ?>" />

                <label for="image_alt">Image Description (Alt Text)</label>
                <input id="image_alt" name="image_alt" type="text" value="<?php echo htmlspecialchars($programme['ImageAlt'] ?? ''); ?>" />

                <div class="checkbox-row">
                    <input id="is_published" name="is_published" type="checkbox" value="1" <?php echo ((int) ($programme['IsPublished'] ?? 1) === 1) ? 'checked' : ''; ?> />
                    <label for="is_published">Publish this programme on the student site</label>
                </div>

                <button type="submit" class="btn-submit"><?php echo $isEdit ? 'Update Programme' : 'Create Programme'; ?></button>
            </form>

            <p style="margin-top: 1rem;">
                <a class="btn btn-ghost" href="/WebApplication/public/admin-dashboard">Back to Dashboard</a>
            </p>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 Student Course Hub. All rights reserved.</p>
    </footer>
</body>
</html>
