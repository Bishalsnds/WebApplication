-- migrations/004_add_image_alt_text_columns.sql
-- Add database-backed image descriptions for accessibility.

ALTER TABLE programmes
ADD COLUMN ImageAlt VARCHAR(255) NULL;

ALTER TABLE modules
ADD COLUMN ImageAlt VARCHAR(255) NULL;
