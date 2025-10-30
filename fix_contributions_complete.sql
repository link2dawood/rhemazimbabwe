-- =============================================
-- COMPLETE FIX: Partner Contributions System
-- =============================================
-- Run this entire script to fix all contribution issues

USE ssdb;

-- =============================================
-- 1. CHECK TABLE STRUCTURE
-- =============================================
SELECT '=== Step 1: Checking Table Structure ===' as '';

-- Show current structure
DESCRIBE partner_contributions;

-- =============================================
-- 2. ADD MISSING COLUMNS (if they don't exist)
-- =============================================
SELECT '=== Step 2: Adding Missing Columns ===' as '';

-- Add receipt_no column if missing
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = 'ssdb'
    AND TABLE_NAME = 'partner_contributions'
    AND COLUMN_NAME = 'receipt_no');

SET @sql = IF(@col_exists = 0,
    'ALTER TABLE partner_contributions ADD COLUMN receipt_no VARCHAR(50) NULL AFTER reference_no',
    'SELECT "receipt_no column already exists" as Status');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add recorded_by column if missing
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = 'ssdb'
    AND TABLE_NAME = 'partner_contributions'
    AND COLUMN_NAME = 'recorded_by');

SET @sql = IF(@col_exists = 0,
    'ALTER TABLE partner_contributions ADD COLUMN recorded_by INT NULL AFTER status',
    'SELECT "recorded_by column already exists" as Status');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add attachment column if missing
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = 'ssdb'
    AND TABLE_NAME = 'partner_contributions'
    AND COLUMN_NAME = 'attachment');

SET @sql = IF(@col_exists = 0,
    'ALTER TABLE partner_contributions ADD COLUMN attachment VARCHAR(255) NULL AFTER notes',
    'SELECT "attachment column already exists" as Status');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add created_at column if missing
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = 'ssdb'
    AND TABLE_NAME = 'partner_contributions'
    AND COLUMN_NAME = 'created_at');

SET @sql = IF(@col_exists = 0,
    'ALTER TABLE partner_contributions ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
    'SELECT "created_at column already exists" as Status');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add updated_at column if missing
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = 'ssdb'
    AND TABLE_NAME = 'partner_contributions'
    AND COLUMN_NAME = 'updated_at');

SET @sql = IF(@col_exists = 0,
    'ALTER TABLE partner_contributions ADD COLUMN updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP',
    'SELECT "updated_at column already exists" as Status');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- =============================================
-- 3. VERIFY REQUIRED COLUMNS
-- =============================================
SELECT '=== Step 3: Verifying Required Columns ===' as '';

SELECT
    COLUMN_NAME,
    COLUMN_TYPE,
    IS_NULLABLE,
    COLUMN_DEFAULT,
    CASE
        WHEN COLUMN_NAME IN ('id', 'partner_id', 'amount', 'currency', 'contribution_date', 'payment_method', 'status')
        THEN 'REQUIRED'
        ELSE 'OPTIONAL'
    END as IMPORTANCE
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = 'ssdb'
AND TABLE_NAME = 'partner_contributions'
ORDER BY ORDINAL_POSITION;

-- =============================================
-- 4. CHECK FOREIGN KEY RELATIONSHIPS
-- =============================================
SELECT '=== Step 4: Checking Foreign Keys ===' as '';

SELECT
    CONSTRAINT_NAME,
    COLUMN_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = 'ssdb'
AND TABLE_NAME = 'partner_contributions'
AND REFERENCED_TABLE_NAME IS NOT NULL;

-- =============================================
-- 5. CHECK IF PARTNERS EXIST
-- =============================================
SELECT '=== Step 5: Checking Partners ===' as '';

SELECT
    COUNT(*) as total_partners,
    SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_partners
FROM partners;

-- If no partners, show warning
SELECT CASE
    WHEN (SELECT COUNT(*) FROM partners WHERE is_active = 1) = 0
    THEN 'WARNING: No active partners found! Add partners before adding contributions.'
    ELSE 'OK: Active partners exist'
END as partner_status;

-- =============================================
-- 6. CHECK GIVING TYPES
-- =============================================
SELECT '=== Step 6: Checking Giving Types ===' as '';

SELECT
    COUNT(*) as total_types,
    SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_types
FROM giving_types;

-- =============================================
-- 7. ADD TEST CONTRIBUTION (Optional)
-- =============================================
-- Uncomment the following lines to add a test contribution

/*
SELECT '=== Step 7: Adding Test Contribution ===' as '';

-- Get first active partner
SET @partner_id = (SELECT id FROM partners WHERE is_active = 1 ORDER BY id LIMIT 1);
SET @giving_type_id = (SELECT id FROM giving_types WHERE is_active = 1 ORDER BY id LIMIT 1);

-- Generate receipt number
SET @receipt_no = CONCAT('RCT-', DATE_FORMAT(NOW(), '%Y%m%d'), '-', LPAD(FLOOR(RAND() * 9999), 4, '0'));

-- Insert test contribution
INSERT INTO partner_contributions
(partner_id, giving_type_id, amount, currency, contribution_date, payment_method, receipt_no, status, recorded_by, notes)
VALUES
(@partner_id, @giving_type_id, 500.00, 'USD', CURDATE(), 'cash', @receipt_no, 'completed', 1, 'Test contribution from SQL fix script');

SELECT 'Test contribution added!' as Status, @receipt_no as receipt_number;
*/

-- =============================================
-- 8. VERIFY RECENT CONTRIBUTIONS
-- =============================================
SELECT '=== Step 8: Recent Contributions ===' as '';

SELECT
    pc.id,
    pc.receipt_no,
    p.partner_code,
    p.firstname,
    p.lastname,
    pc.amount,
    pc.currency,
    pc.contribution_date,
    pc.status
FROM partner_contributions pc
JOIN partners p ON p.id = pc.partner_id
ORDER BY pc.created_at DESC
LIMIT 5;

-- =============================================
-- 9. CHECK CONTRIBUTION TOTALS
-- =============================================
SELECT '=== Step 9: Contribution Summary ===' as '';

SELECT
    COUNT(*) as total_contributions,
    COUNT(DISTINCT partner_id) as partners_with_contributions,
    SUM(CASE WHEN status = 'completed' THEN amount ELSE 0 END) as total_completed_amount,
    SUM(CASE WHEN status = 'pending' THEN amount ELSE 0 END) as total_pending_amount,
    MIN(contribution_date) as first_contribution,
    MAX(contribution_date) as latest_contribution
FROM partner_contributions;

-- =============================================
-- FINAL STATUS
-- =============================================
SELECT '=== FINAL STATUS ===' as '';
SELECT 'Database fixes completed! Check results above.' as Status;
SELECT 'Next: Run check_contributions.php to verify system' as Next_Step;
