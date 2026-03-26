<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mailing List - Student Course Hub</title>
    <link rel="stylesheet" href="/WebApplication/public/css/style.css" />
</head>
<body>
    <?php $token = htmlspecialchars(\App\Core\Auth::csrfToken()); ?>
    <a class="skip-link" href="#main-content">Skip to main content</a>
    <header>
        <h1>Student <span>Course Hub</span></h1>
        <nav>
            <a href="/WebApplication/public/admin-dashboard">Dashboard</a>
            <a href="/WebApplication/public/admin-modules">Modules</a>
            <a class="active" href="/WebApplication/public/admin-mailing-list">Mailing List</a>
            <a class="nav-logout" href="/WebApplication/public/logout">Logout</a>
        </nav>
    </header>

    <main id="main-content">
        <section class="content">
            <div class="content-header">
                <h2>Mailing List Report</h2>
                <p>View student contacts and the programmes each student has selected.</p>
            </div>

            <?php if (!empty($message)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>

            <form method="get" action="/WebApplication/public/admin-mailing-list" class="filter-form" aria-label="Filter mailing list by programme">
                <label class="sr-only" for="programme_id">Filter by programme</label>
                <select id="programme_id" name="programme_id">
                    <option value="">All programmes</option>
                    <?php foreach ($programmes as $programme): ?>
                        <option value="<?php echo (int) $programme['ProgrammeID']; ?>" <?php echo ((string) ($selectedProgrammeId ?? '') === (string) $programme['ProgrammeID']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($programme['ProgrammeName']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="btn btn-ghost">Filter</button>
                <a class="btn btn-accent" href="/WebApplication/public/admin-mailing-list-export<?php echo ($selectedProgrammeId !== null && $selectedProgrammeId > 0) ? '?programme_id=' . (int) $selectedProgrammeId : ''; ?>">Export CSV</a>
            </form>

            <div class="action-row" style="margin: 0.8rem 0 1.5rem;">
                <form method="post" action="/WebApplication/public/admin-remove-duplicate-interests" class="inline-form" onsubmit="return confirm('Remove duplicate interest registrations?');">
                    <input type="hidden" name="_token" value="<?php echo $token; ?>" />
                    <button type="submit" class="btn cleanup-btn cleanup-btn-duplicate">Remove Duplicates</button>
                </form>
                <form method="post" action="/WebApplication/public/admin-remove-invalid-interests" class="inline-form" onsubmit="return confirm('Remove invalid email registrations?');">
                    <input type="hidden" name="_token" value="<?php echo $token; ?>" />
                    <button type="submit" class="btn cleanup-btn cleanup-btn-invalid">Remove Invalid Emails</button>
                </form>
            </div>

            <h3>Student to Programme Matrix</h3>
            <div class="table-wrap" style="margin-top: 1rem;">
                <table class="data-table">
                    <caption>Students with all selected programmes</caption>
                    <thead>
                        <tr>
                            <th scope="col">Student Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Interested Programmes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($matrix)): ?>
                            <tr><td colspan="3">No interest submissions found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($matrix as $row): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['StudentName']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['InterestedProgrammes']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <h3 style="margin-top: 2rem;">Detailed Interest Log</h3>
            <div class="table-wrap" style="margin-top: 1rem;">
                <table class="data-table">
                    <caption>All submitted interests</caption>
                    <thead>
                        <tr>
                            <th scope="col">Interest ID</th>
                            <th scope="col">Student Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Programme</th>
                            <th scope="col">Level</th>
                            <th scope="col">Registered At</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($rows)): ?>
                            <tr><td colspan="7">No records available.</td></tr>
                        <?php else: ?>
                            <?php foreach ($rows as $row): ?>
                                <tr>
                                    <td><?php echo (int) $row['InterestID']; ?></td>
                                    <td><?php echo htmlspecialchars($row['StudentName']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['ProgrammeName']); ?></td>
                                    <td><?php echo htmlspecialchars($row['LevelName'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($row['RegisteredAt']); ?></td>
                                    <td>
                                        <form method="post" action="/WebApplication/public/admin-delete-interest" class="inline-form" onsubmit="return confirm('Remove this interest entry?');">
                                            <input type="hidden" name="_token" value="<?php echo $token; ?>" />
                                            <input type="hidden" name="interest_id" value="<?php echo (int) $row['InterestID']; ?>" />
                                            <input type="hidden" name="selected_programme_id" value="<?php echo ($selectedProgrammeId !== null && $selectedProgrammeId > 0) ? (int) $selectedProgrammeId : ''; ?>" />
                                            <button type="submit" class="btn btn-ghost">Remove</button>
                                        </form>
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
