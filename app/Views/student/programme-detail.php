<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($programme['ProgrammeName']); ?> - Student Course Hub</title>
    <link rel="stylesheet" href="/WebApplication/public/css/style.css" />
</head>
<body>
    <a class="skip-link" href="#main-content">Skip to main content</a>
    <header>
        <h1>Student <span>Course Hub</span></h1>
        <nav>
            <a href="/WebApplication/public/programmes">Programmes</a>
            <a href="/WebApplication/public/staff">Staff</a>
            <a href="/WebApplication/public/my-interests">My Interests</a>
            <a href="/WebApplication/public/admin-login">Admin Login</a>
        </nav>
    </header>

    <main id="main-content">
        <section class="content">
            <div class="content-header">
                <h2><?php echo htmlspecialchars($programme['ProgrammeName']); ?></h2>
                <p>
                    <strong>Level:</strong> <?php echo htmlspecialchars($programme['LevelName'] ?? 'N/A'); ?>
                </p>
            </div>

            <?php if (!empty($programme['ProgrammeLeaderName'])): ?>
                <?php $leaderPhoto = !empty($programme['ProgrammeLeaderPhotoUrl']) ? (string) $programme['ProgrammeLeaderPhotoUrl'] : '/WebApplication/public/img/staff-placeholder.svg'; ?>
                <section class="staff-profile-card" aria-label="Programme leader profile" style="margin-bottom: 1.2rem;">
                    <img
                        class="staff-profile-photo"
                        src="<?php echo htmlspecialchars($leaderPhoto); ?>"
                        alt="Photo of <?php echo htmlspecialchars((string) $programme['ProgrammeLeaderName']); ?>"
                        onerror="this.src='/WebApplication/public/img/staff-placeholder.svg';"
                    />
                    <div class="staff-profile-meta">
                        <h3><?php echo htmlspecialchars((string) $programme['ProgrammeLeaderName']); ?></h3>
                        <p class="staff-profile-role">
                            <?php echo htmlspecialchars((string) ($programme['ProgrammeLeaderJobTitle'] ?? 'Programme Leader')); ?>
                            <?php if (!empty($programme['ProgrammeLeaderDepartment'])): ?>
                                <span> | <?php echo htmlspecialchars((string) $programme['ProgrammeLeaderDepartment']); ?></span>
                            <?php endif; ?>
                        </p>
                        <?php if (!empty($programme['ProgrammeLeaderBio'])): ?>
                            <p class="staff-profile-bio"><?php echo nl2br(htmlspecialchars((string) $programme['ProgrammeLeaderBio'])); ?></p>
                        <?php endif; ?>
                    </div>
                </section>
            <?php endif; ?>

            <?php $programmeImage = !empty($programme['Image']) ? (string) $programme['Image'] : '/WebApplication/public/img/programme-placeholder.svg'; ?>
            <?php $programmeImageAlt = trim((string) ($programme['ImageAlt'] ?? '')) !== '' ? (string) $programme['ImageAlt'] : 'Image for ' . (string) ($programme['ProgrammeName'] ?? 'programme'); ?>
            <img
                class="programme-hero-image"
                src="<?php echo htmlspecialchars($programmeImage); ?>"
                alt="<?php echo htmlspecialchars($programmeImageAlt); ?>"
                onerror="this.src='/WebApplication/public/img/programme-placeholder.svg';"
            />

            <p><?php echo nl2br(htmlspecialchars((string) ($programme['Description'] ?? 'No description available.'))); ?></p>

            <h3 style="margin-top: 2rem;">Modules by Year</h3>
            <?php if (empty($programme['modulesByYear'])): ?>
                <p>No modules have been assigned yet.</p>
            <?php else: ?>
                <?php foreach ($programme['modulesByYear'] as $year => $modules): ?>
                    <section style="margin-top: 1.2rem;">
                        <h4>Year <?php echo (int) $year; ?></h4>
                        <div class="table-wrap">
                            <table class="data-table">
                                <caption>Modules in year <?php echo (int) $year; ?></caption>
                                <thead>
                                    <tr>
                                        <th scope="col">Image</th>
                                        <th scope="col">Module Name</th>
                                        <th scope="col">Module Leader</th>
                                        <th scope="col">Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($modules as $module): ?>
                                        <tr>
                                            <td>
                                                <?php $moduleImage = !empty($module['Image']) ? (string) $module['Image'] : '/WebApplication/public/img/module-placeholder.svg'; ?>
                                                <?php $moduleImageAlt = trim((string) ($module['ImageAlt'] ?? '')) !== '' ? (string) $module['ImageAlt'] : 'Image for ' . (string) ($module['ModuleName'] ?? 'module'); ?>
                                                <img
                                                    class="module-thumb"
                                                    src="<?php echo htmlspecialchars($moduleImage); ?>"
                                                    alt="<?php echo htmlspecialchars($moduleImageAlt); ?>"
                                                    onerror="this.src='/WebApplication/public/img/module-placeholder.svg';"
                                                />
                                            </td>
                                            <td><?php echo htmlspecialchars($module['ModuleName']); ?></td>
                                            <td>
                                                <?php echo htmlspecialchars($module['ModuleLeaderName'] ?? 'N/A'); ?>
                                                <?php if (!empty($module['ModuleLeaderJobTitle'])): ?>
                                                    <div class="staff-inline-meta"><?php echo htmlspecialchars((string) $module['ModuleLeaderJobTitle']); ?></div>
                                                <?php endif; ?>
                                                <?php if (!empty($module['ModuleLeaderDepartment'])): ?>
                                                    <div class="staff-inline-meta"><?php echo htmlspecialchars((string) $module['ModuleLeaderDepartment']); ?></div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars((string) ($module['Description'] ?? '')); ?>
                                                <?php if ((int) ($module['SharedProgrammeCount'] ?? 0) > 0): ?>
                                                    <div class="shared-note">
                                                        Shared with <?php echo (int) $module['SharedProgrammeCount']; ?> other programme(s):
                                                        <?php echo htmlspecialchars((string) ($module['SharedProgrammeNames'] ?? '')); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                <?php endforeach; ?>
            <?php endif; ?>

            <p style="margin-top: 2rem;">
                <a class="btn btn-accent" href="/WebApplication/public/interest?programme_id=<?php echo (int) $programme['ProgrammeID']; ?>">Register Interest</a>
                <a class="btn btn-ghost" href="/WebApplication/public/programmes">Back to Programmes</a>
            </p>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 Student Course Hub. All rights reserved.</p>
    </footer>
</body>
</html>
