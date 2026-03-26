<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Programmes - Student Course Hub</title>
    <link rel="stylesheet" href="/WebApplication/public/css/style.css" />
</head>
<body>
    <a class="skip-link" href="#main-content">Skip to main content</a>
    <header>
        <h1>Student <span>Course Hub</span></h1>
        <nav>
            <a class="active" href="/WebApplication/public/programmes">Programmes</a>
            <a href="/WebApplication/public/staff">Staff</a>
            <a href="/WebApplication/public/my-interests">My Interests</a>
            <a href="/WebApplication/public/admin-login">Admin Login</a>
        </nav>
    </header>

    <main id="main-content">
        <section class="hero">
            <div class="hero-label">Explore Degrees</div>
            <h2>Find your ideal programme</h2>
            <p>Browse undergraduate and postgraduate programmes, module breakdowns by year, and academic staff information.</p>
        </section>

        <form method="get" action="/WebApplication/public/programmes" class="filter-form" aria-label="Filter and search programmes">
            <label class="sr-only" for="q">Search programmes</label>
            <div class="filter-input-wrap">
                <span class="filter-icon" aria-hidden="true">&#128269;</span>
                <input id="q" name="q" type="text" placeholder="Search programmes..." value="<?php echo htmlspecialchars($search ?? ''); ?>" />
            </div>

            <label class="sr-only" for="level">Filter by level</label>
            <select id="level" name="level">
                <option value="">All levels</option>
                <?php foreach ($levels as $level): ?>
                    <option value="<?php echo (int) $level['LevelID']; ?>" <?php echo ((string) ($selectedLevel ?? '') === (string) $level['LevelID']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($level['LevelName']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn btn-accent filter-apply">Apply</button>

            <?php if (!empty($search) || !empty($selectedLevel)): ?>
                <a class="btn btn-ghost filter-reset" href="/WebApplication/public/programmes">Reset</a>
            <?php endif; ?>
        </form>

        <?php if (!empty($search) || !empty($selectedLevel)): ?>
            <p class="filter-active-note">Showing filtered results. Use Reset to view all programmes.</p>
        <?php endif; ?>

        <section class="features" aria-label="Programme list">
            <?php if (empty($programmes)): ?>
                <article class="feature">
                    <h3>No published programmes yet</h3>
                    <p>Please check back later.</p>
                </article>
            <?php else: ?>
                <?php foreach ($programmes as $programme): ?>
                    <article class="feature programme-card">
                        <?php $programmeImage = !empty($programme['Image']) ? (string) $programme['Image'] : '/WebApplication/public/img/programme-placeholder.svg'; ?>
                        <?php $programmeImageAlt = trim((string) ($programme['ImageAlt'] ?? '')) !== '' ? (string) $programme['ImageAlt'] : 'Image for ' . (string) ($programme['ProgrammeName'] ?? 'programme'); ?>
                        <img
                            class="programme-thumb"
                            src="<?php echo htmlspecialchars($programmeImage); ?>"
                            alt="<?php echo htmlspecialchars($programmeImageAlt); ?>"
                            onerror="this.src='/WebApplication/public/img/programme-placeholder.svg';"
                        />
                        <h3><?php echo htmlspecialchars($programme['ProgrammeName']); ?></h3>
                        <p>
                            <strong>Level:</strong> <?php echo htmlspecialchars($programme['LevelName'] ?? 'N/A'); ?><br />
                            <strong>Leader:</strong> <?php echo htmlspecialchars($programme['ProgrammeLeaderName'] ?? 'N/A'); ?>
                        </p>
                        <?php $desc = (string) ($programme['Description'] ?? ''); ?>
                        <p class="programme-summary"><?php echo htmlspecialchars(strlen($desc) > 180 ? substr($desc, 0, 177) . '...' : $desc); ?></p>
                        <p class="programme-actions">
                            <a class="btn btn-accent" href="/WebApplication/public/programme?id=<?php echo (int) $programme['ProgrammeID']; ?>">View Details</a>
                            <a class="btn btn-ghost" href="/WebApplication/public/interest?programme_id=<?php echo (int) $programme['ProgrammeID']; ?>">Register Interest</a>
                        </p>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; 2026 Student Course Hub. All rights reserved.</p>
    </footer>
</body>
</html>
