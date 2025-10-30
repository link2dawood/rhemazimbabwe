-- =====================================================
-- FIX: Partner Reports Permission
-- =====================================================
-- This script creates the missing 'partner_reports' permission
-- and grants it to roles that have 'partners' permission

USE ssdb;

-- =====================================================
-- STEP 1: Add partner_reports to permission_category
-- =====================================================

SELECT '=== STEP 1: Creating partner_reports permission ===' as '';

-- Get the permission group ID from partners
SET @perm_group_id = (SELECT perm_group_id FROM permission_category WHERE short_code = 'partners' LIMIT 1);

SELECT CONCAT('Using Permission Group ID: ', @perm_group_id) as '';

-- Check if permission already exists
SET @exists = (SELECT COUNT(*) FROM permission_category WHERE short_code = 'partner_reports');

-- Add the permission if it doesn't exist
SET @sql = IF(@exists = 0,
    CONCAT('INSERT INTO permission_category (perm_group_id, name, short_code, enable_view, enable_add, enable_edit, enable_delete, created_at)
     VALUES (', @perm_group_id, ', ''Partner Reports'', ''partner_reports'', 1, 0, 0, 0, NOW())'),
    'SELECT ''partner_reports permission already exists'' as Status');

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Get the new permission ID
SET @partner_reports_id = (SELECT id FROM permission_category WHERE short_code = 'partner_reports');

SELECT CONCAT('partner_reports permission ID: ', @partner_reports_id) as '';

-- =====================================================
-- STEP 2: Grant permission to roles
-- =====================================================

SELECT '=== STEP 2: Granting permissions to roles ===' as '';

-- Check which roles have 'partners' permission
SELECT r.id, r.name, rp.can_view, rp.can_add, rp.can_edit, rp.can_delete
FROM roles r
JOIN roles_permissions rp ON rp.role_id = r.id
WHERE rp.perm_cat_id = (SELECT id FROM permission_category WHERE short_code = 'partners')
ORDER BY r.name;

-- Copy permissions from 'partners' to 'partner_reports' for all roles
INSERT INTO roles_permissions (role_id, perm_cat_id, can_view, can_add, can_edit, can_delete)
SELECT
    rp.role_id,
    @partner_reports_id,
    rp.can_view,  -- Keep same view permission as partners
    0,            -- No add permission for reports
    0,            -- No edit permission for reports
    0             -- No delete permission for reports
FROM roles_permissions rp
WHERE rp.perm_cat_id = (SELECT id FROM permission_category WHERE short_code = 'partners')
ON DUPLICATE KEY UPDATE
    can_view = VALUES(can_view);

SELECT '✓ Permissions copied from partners to partner_reports' as Status;

-- =====================================================
-- STEP 3: Verify the fix
-- =====================================================

SELECT '=== STEP 3: Verification ===' as '';

-- Check permission_category
SELECT 'Permission Category:' as '';
SELECT id, name, short_code, perm_group_id, enable_view
FROM permission_category
WHERE short_code = 'partner_reports';

-- Check roles_permissions
SELECT 'Roles with partner_reports permission:' as '';
SELECT r.name as role_name, rp.can_view, rp.can_add, rp.can_edit, rp.can_delete
FROM roles_permissions rp
JOIN roles r ON r.id = rp.role_id
WHERE rp.perm_cat_id = @partner_reports_id
ORDER BY r.name;

-- =====================================================
-- FINAL STATUS
-- =====================================================

SELECT '=== ✓ FIX COMPLETED ===' as '';
SELECT 'Now do the following:' as '';
SELECT '1. Clear browser cache (Ctrl + Shift + Delete)' as '';
SELECT '2. Logout from admin panel' as '';
SELECT '3. Login again' as '';
SELECT '4. Go to: admin/partnerreports/partner_information' as '';
SELECT '5. Reports should now show data!' as '';
