-- Fix Partners Table Status Enum
-- This adds 'pending' as a valid status value

ALTER TABLE `partners`
MODIFY COLUMN `status` ENUM('pending','active','inactive','suspended')
NOT NULL DEFAULT 'inactive';

-- Verify the change
SHOW COLUMNS FROM `partners` LIKE 'status';
