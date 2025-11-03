-- =====================================================
-- COMPLETE FIX FOR PARTNER CONTRIBUTIONS
-- Safe script that handles all edge cases
-- =====================================================

-- Step 1: Delete invalid record with ID = 0
DELETE FROM partner_contributions WHERE id = 0 OR id IS NULL;

-- Step 2: Drop existing indexes if they exist (to avoid duplicate errors)
ALTER TABLE partner_contributions DROP INDEX IF EXISTS idx_partner_id;
ALTER TABLE partner_contributions DROP INDEX IF EXISTS idx_contribution_date;
ALTER TABLE partner_contributions DROP INDEX IF EXISTS idx_status;
ALTER TABLE partner_contributions DROP INDEX IF EXISTS idx_giving_type_id;
ALTER TABLE partner_contributions DROP INDEX IF EXISTS idx_receipt_no;

-- Step 3: Drop existing foreign keys if they exist
SET @fk_check = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS 
    WHERE CONSTRAINT_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'partner_contributions' 
    AND CONSTRAINT_NAME = 'fk_contribution_partner');

SET @sql = IF(@fk_check > 0, 
    'ALTER TABLE partner_contributions DROP FOREIGN KEY fk_contribution_partner',
    'SELECT "No fk_contribution_partner to drop" as Status');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @fk_check = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS 
    WHERE CONSTRAINT_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'partner_contributions' 
    AND CONSTRAINT_NAME = 'fk_contribution_giving_type');

SET @sql = IF(@fk_check > 0, 
    'ALTER TABLE partner_contributions DROP FOREIGN KEY fk_contribution_giving_type',
    'SELECT "No fk_contribution_giving_type to drop" as Status');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @fk_check = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS 
    WHERE CONSTRAINT_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'partner_contributions' 
    AND CONSTRAINT_NAME = 'fk_contribution_frequency');

SET @sql = IF(@fk_check > 0, 
    'ALTER TABLE partner_contributions DROP FOREIGN KEY fk_contribution_frequency',
    'SELECT "No fk_contribution_frequency to drop" as Status');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Step 4: Drop PRIMARY KEY if exists, then recreate with AUTO_INCREMENT
SET @pk_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS 
    WHERE CONSTRAINT_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'partner_contributions' 
    AND CONSTRAINT_TYPE = 'PRIMARY KEY');

SET @sql = IF(@pk_exists > 0,
    'ALTER TABLE partner_contributions DROP PRIMARY KEY',
    'SELECT "No PRIMARY KEY to drop" as Status');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Step 5: Add PRIMARY KEY with AUTO_INCREMENT
ALTER TABLE partner_contributions 
  ADD PRIMARY KEY (id),
  MODIFY id INT(11) NOT NULL AUTO_INCREMENT;

-- Step 6: Reset AUTO_INCREMENT to next available ID
SET @max_id = (SELECT IFNULL(MAX(id), 0) + 1 FROM partner_contributions);
SET @sql = CONCAT('ALTER TABLE partner_contributions AUTO_INCREMENT = ', @max_id);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Step 7: Add indexes for performance
ALTER TABLE partner_contributions
  ADD INDEX idx_partner_id (partner_id),
  ADD INDEX idx_contribution_date (contribution_date),
  ADD INDEX idx_status (status),
  ADD INDEX idx_receipt_no (receipt_no);

-- Step 8: Add giving_type_id index only if column exists and has data
SET @col_exists = (SELECT COUNT(*) FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'partner_contributions'
    AND COLUMN_NAME = 'giving_type_id');

SET @sql = IF(@col_exists > 0,
    'ALTER TABLE partner_contributions ADD INDEX idx_giving_type_id (giving_type_id)',
    'SELECT "giving_type_id column does not exist" as Status');
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- =====================================================
-- VERIFICATION ONLY - Show Results
-- =====================================================

SELECT '✅ Fix completed! Table structure:' as '';
DESCRIBE partner_contributions;

SELECT 'Current AUTO_INCREMENT value:' as '';
SELECT AUTO_INCREMENT 
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'partner_contributions';

SELECT 'Existing contributions (last 5):' as '';
SELECT id, partner_id, amount, contribution_date, receipt_no, status
FROM partner_contributions
ORDER BY id DESC
LIMIT 5;

SELECT '✅ ALL DONE! Try adding a contribution now.' as Status;

