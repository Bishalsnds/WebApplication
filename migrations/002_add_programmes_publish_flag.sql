-- migrations/002_add_programmes_publish_flag.sql
-- Add publishing status flag to programmes table.

ALTER TABLE programmes
ADD COLUMN IsPublished TINYINT(1) NOT NULL DEFAULT 1;

UPDATE programmes
SET IsPublished = 1
WHERE IsPublished IS NULL;
