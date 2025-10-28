-- =============================================
-- Add Giving Types Menu to Sidebar
-- =============================================
-- This SQL will add the Giving Types menu item under Partners
-- Run this if you don't see "Giving Types" in the Partners menu

-- First, check if the menu already exists
SELECT * FROM sidebar_sub_menus WHERE `key` = 'giving_types';

-- If the above returns no results, run the following INSERT:
-- (If it exists but is_active = 0, use the UPDATE at the bottom instead)

INSERT INTO `sidebar_sub_menus`
(`sidebar_menu_id`, `menu`, `key`, `lang_key`, `url`, `level`, `access_permissions`, `permission_group_id`, `activate_controller`, `activate_methods`, `addon_permission`, `is_active`)
VALUES
(40, 'Giving Types', 'giving_types', 'giving_types', 'admin/givingtypes', 4, '(\'partners\', \'can_view\')', NULL, 'givingtypes', 'index,add,edit,show,delete', '', 1);

-- If the menu already exists but is inactive, activate it with this UPDATE:
-- UPDATE `sidebar_sub_menus` SET `is_active` = 1 WHERE `key` = 'giving_types';

-- =============================================
-- Verify the menu was added
-- =============================================
-- Run this to confirm:
SELECT sm.menu as 'Main Menu', ssm.menu as 'Sub Menu', ssm.url, ssm.is_active
FROM sidebar_sub_menus ssm
JOIN sidebar_menus sm ON sm.id = ssm.sidebar_menu_id
WHERE ssm.key = 'giving_types';

-- =============================================
-- Alternative: Update existing menu item
-- =============================================
-- If the menu exists but has wrong URL or settings, update it:
/*
UPDATE `sidebar_sub_menus`
SET
    `url` = 'admin/givingtypes',
    `activate_controller` = 'givingtypes',
    `activate_methods` = 'index,add,edit,show,delete',
    `is_active` = 1
WHERE `key` = 'giving_types';
*/

-- =============================================
-- Check all Partners submenus
-- =============================================
-- To see all menu items under Partners:
SELECT
    ssm.id,
    ssm.menu,
    ssm.key,
    ssm.url,
    ssm.level,
    ssm.is_active
FROM sidebar_sub_menus ssm
WHERE ssm.sidebar_menu_id = 40
ORDER BY ssm.level;
