-- =====================================================
-- REMOVE PARTNER SETTINGS AND GIVING FREQUENCIES FROM SIDEBAR
-- Keep only: Partner List, Partner Requests, Contributions, Partner Reports, Giving Types
-- =====================================================

-- Get the Partners menu ID
SET @partners_menu_id = (SELECT id FROM sidebar_menus WHERE menu = 'Partners' LIMIT 1);

-- Show current submenus BEFORE deletion
SELECT 'Current Partner Submenus (BEFORE):' as status;
SELECT id, menu, `key`, url, level FROM sidebar_sub_menus WHERE sidebar_menu_id = @partners_menu_id ORDER BY level;

-- Delete Partner Settings from sidebar
DELETE FROM sidebar_sub_menus WHERE `key` = 'partner_settings';
SELECT 'Deleted Partner Settings menu item' as status;

-- Delete Giving Frequencies from sidebar
DELETE FROM sidebar_sub_menus WHERE `key` = 'giving_frequencies';
SELECT 'Deleted Giving Frequencies menu item' as status;

-- Show remaining submenus AFTER deletion
SELECT 'Partner Submenus (AFTER):' as status;
SELECT id, menu, `key`, url, level FROM sidebar_sub_menus WHERE sidebar_menu_id = @partners_menu_id ORDER BY level;

-- =====================================================
SELECT '✓ DONE!' as status;
SELECT 'Removed: Partner Settings & Giving Frequencies' as removed;
SELECT 'Kept: Partner List, Partner Requests, Contributions, Partner Reports, Giving Types' as kept;
SELECT 'Admin needs to LOGOUT and LOGIN again to see changes!' as important_note;
-- =====================================================

-- Note: The pages still exist and can be accessed via direct URL:
-- Partner Settings: admin/partner_settings
-- Giving Frequencies: admin/givingfrequencies
-- They just won't show in the sidebar menu

