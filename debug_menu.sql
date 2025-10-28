-- =============================================
-- DEBUG: Check Partners Menu Structure
-- =============================================
-- Run this to see what's in your database

USE ssdb;

-- 1. Find the Partners main menu
SELECT '=== STEP 1: Partners Main Menu ===' as '';
SELECT id, menu, access_permissions, is_active, sidebar_display
FROM sidebar_menus
WHERE menu LIKE '%Partner%' OR lang_key LIKE '%partner%'
ORDER BY id;

-- 2. Check all submenus under Partners (sidebar_menu_id might not be 40)
SELECT '=== STEP 2: All Partners Submenus ===' as '';
SELECT
    ssm.id,
    ssm.sidebar_menu_id,
    ssm.menu,
    ssm.key,
    ssm.url,
    ssm.level,
    ssm.is_active,
    ssm.access_permissions
FROM sidebar_sub_menus ssm
WHERE ssm.sidebar_menu_id IN (
    SELECT id FROM sidebar_menus WHERE menu LIKE '%Partner%'
)
ORDER BY ssm.sidebar_menu_id, ssm.level;

-- 3. Check if giving_types menu exists
SELECT '=== STEP 3: Giving Types Menu ===' as '';
SELECT * FROM sidebar_sub_menus WHERE `key` = 'giving_types' OR menu LIKE '%Giving Type%';

-- 4. Check Add Partner menu
SELECT '=== STEP 4: Add Partner Menu ===' as '';
SELECT * FROM sidebar_sub_menus WHERE menu LIKE '%Add Partner%';

-- 5. Check Giving Frequencies menu
SELECT '=== STEP 5: Giving Frequencies Menu ===' as '';
SELECT * FROM sidebar_sub_menus WHERE menu LIKE '%Giving Freq%' OR `key` = 'giving_frequencies';

-- 6. Get the highest level number for Partners submenus
SELECT '=== STEP 6: Highest Level ===' as '';
SELECT MAX(level) as max_level, MIN(level) as min_level
FROM sidebar_sub_menus ssm
WHERE ssm.sidebar_menu_id IN (
    SELECT id FROM sidebar_menus WHERE menu LIKE '%Partner%'
);
