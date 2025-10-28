-- =============================================
-- FINAL FIX: Giving Types Menu (Fixes the Module_lib error)
-- =============================================
-- This fixes the "Undefined array key" error by setting permission_group_id to NULL

USE ssdb;

-- Step 1: Get Partners menu ID
SET @partners_menu_id = (SELECT id FROM sidebar_menus WHERE menu = 'Partners' OR lang_key = 'partners' LIMIT 1);

-- Step 2: Delete the problematic menu
DELETE FROM sidebar_sub_menus WHERE `key` = 'giving_types';

-- Step 3: Insert with NULL permission_group_id (fixes the error)
INSERT INTO sidebar_sub_menus
(sidebar_menu_id, menu, `key`, lang_key, url, level, access_permissions, permission_group_id, activate_controller, activate_methods, addon_permission, is_active)
VALUES
(@partners_menu_id, 'Giving Types', 'giving_types', 'giving_types', 'admin/givingtypes', 4, '(\'partners\', \'can_view\')', NULL, 'givingtypes', 'index,add,edit,show,delete', '', 1);

-- Step 4: Verify
SELECT
    ssm.id,
    ssm.menu,
    ssm.url,
    ssm.permission_group_id,
    ssm.is_active
FROM sidebar_sub_menus ssm
WHERE ssm.sidebar_menu_id = @partners_menu_id
ORDER BY ssm.level;

SELECT 'Fixed! Now refresh your page (clear cache if needed)' as 'STATUS';
