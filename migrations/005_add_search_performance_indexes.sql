-- migrations/005_add_search_performance_indexes.sql
-- Improve search and filtering performance with targeted indexes.

SET @db_name = DATABASE();

-- programmes
SET @sql = IF (
    EXISTS (
        SELECT 1
        FROM information_schema.statistics
        WHERE table_schema = @db_name
          AND table_name = 'programmes'
          AND index_name = 'idx_programmes_level_publish'
    ),
    'SELECT 1',
    'ALTER TABLE programmes ADD INDEX idx_programmes_level_publish (LevelID, IsPublished)'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = IF (
    EXISTS (
        SELECT 1
        FROM information_schema.statistics
        WHERE table_schema = @db_name
          AND table_name = 'programmes'
          AND index_name = 'idx_programmes_leader'
    ),
    'SELECT 1',
    'ALTER TABLE programmes ADD INDEX idx_programmes_leader (ProgrammeLeaderID)'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = IF (
    EXISTS (
        SELECT 1
        FROM information_schema.statistics
        WHERE table_schema = @db_name
          AND table_name = 'programmes'
          AND index_name = 'idx_programmes_name'
    ),
    'SELECT 1',
    'ALTER TABLE programmes ADD INDEX idx_programmes_name (ProgrammeName)'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = IF (
    EXISTS (
        SELECT 1
        FROM information_schema.statistics
        WHERE table_schema = @db_name
          AND table_name = 'programmes'
          AND index_name = 'ft_programmes_name_desc'
    ),
    'SELECT 1',
    'ALTER TABLE programmes ADD FULLTEXT INDEX ft_programmes_name_desc (ProgrammeName, Description)'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- modules
SET @sql = IF (
    EXISTS (
        SELECT 1
        FROM information_schema.statistics
        WHERE table_schema = @db_name
          AND table_name = 'modules'
          AND index_name = 'idx_modules_leader'
    ),
    'SELECT 1',
    'ALTER TABLE modules ADD INDEX idx_modules_leader (ModuleLeaderID)'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = IF (
    EXISTS (
        SELECT 1
        FROM information_schema.statistics
        WHERE table_schema = @db_name
          AND table_name = 'modules'
          AND index_name = 'idx_modules_name'
    ),
    'SELECT 1',
    'ALTER TABLE modules ADD INDEX idx_modules_name (ModuleName)'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = IF (
    EXISTS (
        SELECT 1
        FROM information_schema.statistics
        WHERE table_schema = @db_name
          AND table_name = 'modules'
          AND index_name = 'ft_modules_name_desc'
    ),
    'SELECT 1',
    'ALTER TABLE modules ADD FULLTEXT INDEX ft_modules_name_desc (ModuleName, Description)'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- programmemodules
SET @sql = IF (
    EXISTS (
        SELECT 1
        FROM information_schema.statistics
        WHERE table_schema = @db_name
          AND table_name = 'programmemodules'
          AND index_name = 'idx_pm_programme_year'
    ),
    'SELECT 1',
    'ALTER TABLE programmemodules ADD INDEX idx_pm_programme_year (ProgrammeID, Year)'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = IF (
    EXISTS (
        SELECT 1
        FROM information_schema.statistics
        WHERE table_schema = @db_name
          AND table_name = 'programmemodules'
          AND index_name = 'idx_pm_module'
    ),
    'SELECT 1',
    'ALTER TABLE programmemodules ADD INDEX idx_pm_module (ModuleID)'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- interestedstudents
SET @sql = IF (
    EXISTS (
        SELECT 1
        FROM information_schema.statistics
        WHERE table_schema = @db_name
          AND table_name = 'interestedstudents'
          AND index_name = 'idx_interest_programme_email'
    ),
    'SELECT 1',
    'ALTER TABLE interestedstudents ADD INDEX idx_interest_programme_email (ProgrammeID, Email)'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = IF (
    EXISTS (
        SELECT 1
        FROM information_schema.statistics
        WHERE table_schema = @db_name
          AND table_name = 'interestedstudents'
          AND index_name = 'idx_interest_email_registered'
    ),
    'SELECT 1',
    'ALTER TABLE interestedstudents ADD INDEX idx_interest_email_registered (Email, RegisteredAt)'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql = IF (
    EXISTS (
        SELECT 1
        FROM information_schema.statistics
        WHERE table_schema = @db_name
          AND table_name = 'interestedstudents'
          AND index_name = 'idx_interest_programme_registered'
    ),
    'SELECT 1',
    'ALTER TABLE interestedstudents ADD INDEX idx_interest_programme_registered (ProgrammeID, RegisteredAt)'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
