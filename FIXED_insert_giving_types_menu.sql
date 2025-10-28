-- =============================================
-- CORRECTED: Add Giving Types Menu to Sidebar
-- =============================================
-- Run this SQL in phpMyAdmin

USE ssdb;

-- Step 1: Get the Partners menu ID
SET @partners_menu_id = (SELECT id FROM sidebar_menus WHERE menu = 'Partners' OR lang_key = 'partners' LIMIT 1);

-- Step 2: Show what we found (for verification)
SELECT @partners_menu_id as 'Partners Menu ID';

-- Step 3: Delete any existing Giving Types menu
DELETE FROM sidebar_sub_menus WHERE `key` = 'giving_types';

-- Step 4: Insert the Giving Types menu (WITHOUT short_code column)
-- Set permission_group_id to NULL to avoid module check error
INSERT INTO sidebar_sub_menus
(sidebar_menu_id, menu, `key`, lang_key, url, level, access_permissions, permission_group_id, activate_controller, activate_methods, addon_permission, is_active)
VALUES
(@partners_menu_id, 'Giving Types', 'giving_types', 'giving_types', 'admin/givingtypes', 4, '(\'partners\', \'can_view\')', NULL, 'givingtypes', 'index,add,edit,show,delete', '', 1);

-- Step 5: Activate all Partners submenus
UPDATE sidebar_sub_menus SET is_active = 1 WHERE sidebar_menu_id = @partners_menu_id;

-- Step 6: Verify the menu was added
SELECT
    ssm.id,
    ssm.menu,
    ssm.url,
    ssm.level,
    ssm.is_active
FROM sidebar_sub_menus ssm
WHERE ssm.sidebar_menu_id = @partners_menu_id
ORDER BY ssm.level;

-- SUCCESS MESSAGE
SELECT 'Menu added successfully! Now clear cache, logout and login.' as 'STATUS';
