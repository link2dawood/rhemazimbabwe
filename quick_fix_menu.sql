-- =============================================
-- QUICK FIX: Add Giving Types Menu to Sidebar
-- =============================================
-- Run this entire file in your MySQL/phpMyAdmin

USE ssdb;

-- Step 1: Check if menu already exists
SELECT 'Checking if Giving Types menu exists...' as 'Step 1';
SELECT id, menu, url, is_active
FROM sidebar_sub_menus
WHERE `key` = 'giving_types';

-- Step 2: Delete existing entry (if any) to start fresh
DELETE FROM sidebar_sub_menus WHERE `key` = 'giving_types';

-- Step 3: Insert the Giving Types menu
SELECT 'Inserting Giving Types menu...' as 'Step 2';

INSERT INTO `sidebar_sub_menus`
(`sidebar_menu_id`, `menu`, `key`, `lang_key`, `url`, `level`, `access_permissions`, `permission_group_id`, `activate_controller`, `activate_methods`, `addon_permission`, `is_active`)
VALUES
(40, 'Giving Types', 'giving_types', 'giving_types', 'admin/givingtypes', 4, '(\'partners\', \'can_view\')', NULL, 'givingtypes', 'index,add,edit,show,delete', '', 1);

-- Step 4: Verify the menu was added successfully
SELECT 'Verifying menu was added...' as 'Step 3';

SELECT
    sm.menu as 'Main Menu',
    ssm.menu as 'Sub Menu',
    ssm.url as 'URL',
    ssm.is_active as 'Active',
    ssm.level as 'Level'
FROM sidebar_sub_menus ssm
JOIN sidebar_menus sm ON sm.id = ssm.sidebar_menu_id
WHERE ssm.key = 'giving_types';

-- Step 5: Show all Partners submenus for verification
SELECT 'All Partners submenus:' as 'Step 4';

SELECT
    ssm.id,
    ssm.menu as 'Sub Menu',
    ssm.url as 'URL',
    ssm.level as 'Level',
    ssm.is_active as 'Active'
FROM sidebar_sub_menus ssm
WHERE ssm.sidebar_menu_id = 40
ORDER BY ssm.level;

-- Done!
SELECT 'DONE! Now logout, clear cache, and login again to see the menu.' as 'RESULT';
