-- =====================================================
-- FIX PARTNER CONTRIBUTIONS TABLE
-- Add all missing columns needed for admin to add contributions
-- =====================================================

-- Add receipt_no column if missing
SET @col_exists_receipt = (SELECT COUNT(*) 
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'partner_contributions'
    AND COLUMN_NAME = 'receipt_no');

SET @sql_receipt = IF(@col_exists_receipt = 0,
    'ALTER TABLE partner_contributions ADD COLUMN receipt_no VARCHAR(50) NULL AFTER reference_no',
    'SELECT "receipt_no column already exists" as Status');

PREPARE stmt FROM @sql_receipt;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add recorded_by column if missing  
SET @col_exists_recorded = (SELECT COUNT(*) 
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'partner_contributions'
    AND COLUMN_NAME = 'recorded_by');

SET @sql_recorded = IF(@col_exists_recorded = 0,
    'ALTER TABLE partner_contributions ADD COLUMN recorded_by INT(11) NULL COMMENT ''Staff ID who recorded the contribution''',
    'SELECT "recorded_by column already exists" as Status');

PREPARE stmt FROM @sql_recorded;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add attachment column if missing
SET @col_exists_attachment = (SELECT COUNT(*) 
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'partner_contributions'
    AND COLUMN_NAME = 'attachment');

SET @sql_attachment = IF(@col_exists_attachment = 0,
    'ALTER TABLE partner_contributions ADD COLUMN attachment VARCHAR(500) NULL COMMENT ''File path for contribution receipt/proof''',
    'SELECT "attachment column already exists" as Status');

PREPARE stmt FROM @sql_attachment;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add reference_no column if missing
SET @col_exists_reference = (SELECT COUNT(*) 
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'partner_contributions'
    AND COLUMN_NAME = 'reference_no');

SET @sql_reference = IF(@col_exists_reference = 0,
    'ALTER TABLE partner_contributions ADD COLUMN reference_no VARCHAR(100) NULL COMMENT ''Reference number for the contribution'' AFTER transaction_id',
    'SELECT "reference_no column already exists" as Status');

PREPARE stmt FROM @sql_reference;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add created_at column if missing
SET @col_exists_created = (SELECT COUNT(*) 
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'partner_contributions'
    AND COLUMN_NAME = 'created_at');

SET @sql_created = IF(@col_exists_created = 0,
    'ALTER TABLE partner_contributions ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
    'SELECT "created_at column already exists" as Status');

PREPARE stmt FROM @sql_created;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add updated_at column if missing
SET @col_exists_updated = (SELECT COUNT(*) 
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'partner_contributions'
    AND COLUMN_NAME = 'updated_at');

SET @sql_updated = IF(@col_exists_updated = 0,
    'ALTER TABLE partner_contributions ADD COLUMN updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP',
    'SELECT "updated_at column already exists" as Status');

PREPARE stmt FROM @sql_updated;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Create uploads directory check reminder
SELECT '✓ Columns added successfully!' as Status;
SELECT '⚠️  IMPORTANT: Make sure the following directory exists and is writable:' as Reminder;
SELECT './uploads/partner_contributions/' as Directory;

-- Show final table structure
SELECT 'Final Table Structure:' as '';
DESCRIBE partner_contributions;

-- Show column summary
SELECT 
    COLUMN_NAME,
    COLUMN_TYPE,
    IS_NULLABLE,
    COLUMN_DEFAULT
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
AND TABLE_NAME = 'partner_contributions'
ORDER BY ORDINAL_POSITION;

