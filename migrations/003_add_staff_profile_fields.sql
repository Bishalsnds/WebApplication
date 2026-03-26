-- migrations/003_add_staff_profile_fields.sql
-- Add richer profile fields to staff for public and staff-facing views.

ALTER TABLE staff
ADD COLUMN JobTitle VARCHAR(150) NULL,
ADD COLUMN Department VARCHAR(150) NULL,
ADD COLUMN Bio TEXT NULL,
ADD COLUMN PhotoUrl VARCHAR(500) NULL;
