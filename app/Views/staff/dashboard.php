<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Staff Perspective - Student Course Hub</title>
    <link rel="stylesheet" href="/WebApplication/public/css/style.css" />
</head>
<body>
    <a class="skip-link" href="#main-content">Skip to main content</a>
    <header>
        <h1>Student <span>Course Hub</span></h1>
        <nav>
            <a href="/WebApplication/public/programmes">Programmes</a>
            <a class="active" href="/WebApplication/public/staff">Staff</a>
            <a href="/WebApplication/public/my-interests">My Interests</a>
            <a href="/WebApplication/public/admin-login">Admin Login</a>
        </nav>
    </header>

    <main id="main-content">
        <section class="content">
            <div class="content-header">
                <h2>Staff Perspective</h2>
                <p>Select a staff member to view modules they lead and which programmes include those modules.</p>
            </div>

            <form method="get" action="/WebApplication/public/staff" class="filter-form" aria-label="Choose staff member">
                <label class="sr-only" for="staff_id">Staff member</label>
                <select id="staff_id" name="staff_id">
                    <option value="">Select a staff member</option>
                    <?php foreach ($staffMembers as $staff): ?>
                        <option value="<?php echo (int) $staff['StaffID']; ?>" <?php echo ((string) ($selectedStaffId ?? '') === (string) $staff['StaffID']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($staff['Name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-accent">Show Responsibilities</button>
                <?php if (!empty($selectedStaffId)): ?>
                    <a class="btn btn-ghost" href="/WebApplication/public/staff">Reset</a>
                <?php endif; ?>
            </form>

            <?php if (!empty($selectedStaffId) && $selectedStaff === null): ?>
                <div class="alert alert-error" style="margin-top: 1rem;">Selected staff member was not found.</div>
            <?php endif; ?>

            <?php if ($selectedStaff !== null): ?>
                <?php $staffPhoto = !empty($selectedStaff['PhotoUrl']) ? (string) $selectedStaff['PhotoUrl'] : '/WebApplication/public/img/staff-placeholder.svg'; ?>
                <section class="staff-profile-card" aria-label="Selected staff profile">
                    <img
                        class="staff-profile-photo"
                        src="<?php echo htmlspecialchars($staffPhoto); ?>"
                        alt="Photo of <?php echo htmlspecialchars($selectedStaff['Name']); ?>"
                        onerror="this.src='/WebApplication/public/img/staff-placeholder.svg';"
                    />
                    <div class="staff-profile-meta">
                        <h3><?php echo htmlspecialchars($selectedStaff['Name']); ?></h3>
                        <p class="staff-profile-role">
                            <?php echo htmlspecialchars((string) ($selectedStaff['JobTitle'] ?? 'Academic Staff Member')); ?>
                            <?php if (!empty($selectedStaff['Department'])): ?>
                                <span> | <?php echo htmlspecialchars((string) $selectedStaff['Department']); ?></span>
                            <?php endif; ?>
                        </p>
                        <?php if (!empty($selectedStaff['Bio'])): ?>
                            <p class="staff-profile-bio"><?php echo nl2br(htmlspecialchars((string) $selectedStaff['Bio'])); ?></p>
                        <?php else: ?>
                            <p class="staff-profile-bio">No profile bio available yet.</p>
                        <?php endif; ?>
                    </div>
                </section>

                <h3 style="margin-top: 1.5rem; margin-bottom: 0.8rem;">Modules Led by <?php echo htmlspecialchars($selectedStaff['Name']); ?></h3>
                <div class="table-wrap">
                    <table class="data-table">
                        <caption>Teaching responsibilities</caption>
                        <thead>
                            <tr>
                                <th scope="col">Module</th>
                                <th scope="col">Description</th>
                                <th scope="col">Included In Programmes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($modules)): ?>
                                <tr>
                                    <td colspan="3">No modules assigned to this staff member.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($modules as $module): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($module['ModuleName']); ?></td>
                                        <td><?php echo htmlspecialchars((string) ($module['Description'] ?? '')); ?></td>
                                        <td>
                                            <?php if ((int) ($module['ProgrammeCount'] ?? 0) > 0): ?>
                                                <?php echo htmlspecialchars((string) $module['ProgrammeNames']); ?>
                                            <?php else: ?>
                                                Not currently assigned to any programme
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <h3 style="margin-top: 1.5rem; margin-bottom: 0.8rem;">Programmes Impacted</h3>
                <div class="table-wrap">
                    <table class="data-table">
                        <caption>Programmes that include this staff member's modules</caption>
                        <thead>
                            <tr>
                                <th scope="col">Programme</th>
                                <th scope="col">Level</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($programmes)): ?>
                                <tr>
                                    <td colspan="2">No programmes currently include these modules.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($programmes as $programme): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($programme['ProgrammeName']); ?></td>
                                        <td><?php echo htmlspecialchars($programme['LevelName'] ?? 'N/A'); ?></td>
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
