-- =============================================
-- FIX ALL PARTNER MENUS
-- =============================================
-- This will properly set up all Partners menu items
-- Run this entire script

USE ssdb;

-- Step 1: Update all existing Partners submenus to be active
UPDATE sidebar_sub_menus
SET is_active = 1
WHERE sidebar_menu_id IN (
    SELECT id FROM sidebar_menus WHERE menu LIKE '%Partner%'
);

-- Step 2: Delete existing giving_types menu to recreate it fresh
DELETE FROM sidebar_sub_menus WHERE `key` = 'giving_types';

-- Step 3: Insert Giving Types menu
-- First, let's get the correct sidebar_menu_id
SET @partners_menu_id = (SELECT id FROM sidebar_menus WHERE menu = 'Partners' OR lang_key = 'partners' LIMIT 1);

-- Insert the Giving Types menu with correct sidebar_menu_id
-- Set permission_group_id to NULL to avoid Module_lib error
INSERT INTO `sidebar_sub_menus`
(`sidebar_menu_id`, `menu`, `key`, `lang_key`, `url`, `level`, `access_permissions`, `permission_group_id`, `activate_controller`, `activate_methods`, `addon_permission`, `is_active`)
SELECT
    @partners_menu_id,
    'Giving Types',
    'giving_types',
    'giving_types',
    'admin/givingtypes',
    4,
    '(\'partners\', \'can_view\')',
    NULL,
    'givingtypes',
    'index,add,edit,show,delete',
    '',
    1;

-- Step 4: Update Add Partner menu if exists
UPDATE sidebar_sub_menus
SET is_active = 1, level = 2
WHERE `key` = 'add_partner' OR menu = 'Add Partner';

-- Step 5: Update Giving Frequencies menu if exists
UPDATE sidebar_sub_menus
SET is_active = 1, level = 5
WHERE `key` = 'giving_frequencies' OR menu LIKE '%Giving Freq%';

-- Step 6: Make sure all Partners permission categories are active
UPDATE permission_category
SET is_active = 1
WHERE perm_group_id = 32;

-- Step 7: Show results
SELECT '=== Partners Main Menu ===' as '';
SELECT id, menu, is_active FROM sidebar_menus WHERE menu LIKE '%Partner%';

SELECT '=== All Partners Submenus (Final) ===' as '';
SELECT
    ssm.id,
    ssm.sidebar_menu_id,
    ssm.menu,
    ssm.url,
    ssm.level,
    ssm.is_active
FROM sidebar_sub_menus ssm
WHERE ssm.sidebar_menu_id = @partners_menu_id
ORDER BY ssm.level;

SELECT 'DONE! Clear cache, logout and login to see changes.' as 'STATUS';
