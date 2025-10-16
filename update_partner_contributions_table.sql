-- =====================================================
-- UPDATE PARTNER CONTRIBUTIONS TABLE
-- Add missing columns for reference_no, attachment, and recorded_by
-- =====================================================

-- Add reference_no column
ALTER TABLE `partner_contributions` 
ADD COLUMN `reference_no` VARCHAR(100) NULL DEFAULT NULL 
COMMENT 'Reference number for the contribution' 
AFTER `transaction_id`;

-- Add attachment column
ALTER TABLE `partner_contributions` 
ADD COLUMN `attachment` VARCHAR(500) NULL DEFAULT NULL 
COMMENT 'File path for contribution receipt/proof' 
AFTER `notes`;

-- Add recorded_by column (if not exists)
-- First check if it exists
SET @col_exists = 0;
SELECT COUNT(*) INTO @col_exists 
FROM information_schema.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'partner_contributions' 
AND COLUMN_NAME = 'recorded_by';

-- Add the column only if it doesn't exist
SET @query = IF(@col_exists = 0,
    'ALTER TABLE `partner_contributions` ADD COLUMN `recorded_by` INT(11) NULL DEFAULT NULL COMMENT ''Staff ID who recorded the contribution'' AFTER `approved_at`',
    'SELECT ''Column recorded_by already exists'' AS message'
);

PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add index for reference_no for faster lookups
CREATE INDEX `idx_reference_no` ON `partner_contributions` (`reference_no`);

-- Display success message
SELECT 'Partner contributions table updated successfully!' AS status;

-- Verify the changes
DESCRIBE `partner_contributions`;


