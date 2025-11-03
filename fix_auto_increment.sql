-- =====================================================
-- FIX AUTO_INCREMENT for partner_contributions table
-- =====================================================

-- First, check the current AUTO_INCREMENT value
SELECT AUTO_INCREMENT 
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'partner_contributions';

-- Find the highest ID in the table
SELECT MAX(id) as max_id FROM partner_contributions;

-- Reset AUTO_INCREMENT to start from the next available ID
-- Get the max ID and add 1
SET @max_id = (SELECT IFNULL(MAX(id), 0) + 1 FROM partner_contributions);

-- Prepare and execute the ALTER TABLE statement
SET @sql = CONCAT('ALTER TABLE partner_contributions AUTO_INCREMENT = ', @max_id);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Also ensure the id column is set as AUTO_INCREMENT
ALTER TABLE partner_contributions 
MODIFY COLUMN id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY;

-- Verify the fix
SELECT AUTO_INCREMENT 
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'partner_contributions';

SELECT 'AUTO_INCREMENT fixed successfully!' as Status;

