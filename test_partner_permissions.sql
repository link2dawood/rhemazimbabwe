-- =================================================================
-- PARTNER PERMISSIONS TESTING SCRIPT
-- This script helps you test the partner permission system
-- =================================================================

-- Step 1: Check if partner_permission_types table exists and has data
SELECT 'Step 1: Checking Permission Types...' as status;
SELECT * FROM partner_permission_types WHERE is_active = 1;

-- Expected Results:
-- You should see 5 permission types:
-- 1. Library (code: library)
-- 2. Online Courses (code: online_courses)
-- 3. Download Centre (code: download_centre)
-- 4. Google Meet (code: gmeet)
-- 5. Zoom (code: zoom)

-- If no results, run the migration or insert manually:
-- INSERT INTO partner_permission_types (permission_name, permission_code, description, is_active, sort_order) VALUES
-- ('Library Access', 'library', 'Access to library resources and books', 1, 1),
-- ('Online Courses', 'online_courses', 'Access to online courses and learning materials', 1, 2),
-- ('Download Centre', 'download_centre', 'Access to download centre and digital resources', 1, 3),
-- ('Google Meet', 'gmeet', 'Access to Google Meet live classes', 1, 4),
-- ('Zoom', 'zoom', 'Access to Zoom live classes', 1, 5);

-- =================================================================

-- Step 2: Find a test partner to grant permissions to
SELECT 'Step 2: Finding Test Partner...' as status;
SELECT id, partner_code, firstname, lastname, email, status
FROM partners
WHERE status = 'active'
ORDER BY created_at DESC
LIMIT 5;

-- Copy the partner ID from above for next steps
-- =================================================================

-- Step 3: Grant ALL permissions to a specific partner
-- REPLACE 'PARTNER_ID_HERE' with actual partner ID from Step 2

SET @partner_id = 1; -- CHANGE THIS to your test partner ID
SET @admin_staff_id = 1; -- CHANGE THIS to admin's staff ID

-- First, delete any existing permissions for this partner
DELETE FROM partner_permissions WHERE partner_id = @partner_id;

-- Now grant all permissions
INSERT INTO partner_permissions (partner_id, permission_code, is_granted, granted_by, granted_at)
VALUES
(@partner_id, 'library', 1, @admin_staff_id, NOW()),
(@partner_id, 'online_courses', 1, @admin_staff_id, NOW()),
(@partner_id, 'download_centre', 1, @admin_staff_id, NOW()),
(@partner_id, 'gmeet', 1, @admin_staff_id, NOW()),
(@partner_id, 'zoom', 1, @admin_staff_id, NOW());

SELECT 'Step 3: Permissions Granted!' as status;

-- =================================================================

-- Step 4: Verify permissions were granted
SELECT 'Step 4: Verifying Granted Permissions...' as status;
SELECT 
    p.firstname,
    p.lastname,
    p.email,
    pp.permission_code,
    ppt.permission_name,
    pp.granted_at
FROM partner_permissions pp
JOIN partners p ON p.id = pp.partner_id
JOIN partner_permission_types ppt ON ppt.permission_code = pp.permission_code
WHERE pp.partner_id = @partner_id
AND pp.is_granted = 1;

-- Expected Result: Should show 5 rows with all permissions

-- =================================================================

-- Step 5: Check permission codes that will appear in sidebar
SELECT 'Step 5: Sidebar Menu Items (What Partner Will See)...' as status;
SELECT permission_code as 'Menu Item Code', permission_name as 'Menu Label'
FROM partner_permissions pp
JOIN partner_permission_types ppt ON ppt.permission_code = pp.permission_code
WHERE pp.partner_id = @partner_id
AND pp.is_granted = 1;

-- =================================================================

-- BONUS: Test with specific permissions only
-- If you want to grant only some permissions, use this:

/*
-- Example: Grant only Library and Online Courses
DELETE FROM partner_permissions WHERE partner_id = @partner_id;

INSERT INTO partner_permissions (partner_id, permission_code, is_granted, granted_by, granted_at)
VALUES
(@partner_id, 'library', 1, @admin_staff_id, NOW()),
(@partner_id, 'online_courses', 1, @admin_staff_id, NOW());
*/

-- =================================================================

-- CLEANUP: To revoke all permissions for testing
-- Uncomment below to revoke all permissions

/*
DELETE FROM partner_permissions WHERE partner_id = @partner_id;
SELECT 'All permissions revoked for testing' as status;
*/

-- =================================================================

-- FINAL VERIFICATION
SELECT 
    '✓ Permission System Test Complete!' as status,
    COUNT(*) as 'Total Permissions Granted',
    @partner_id as 'Partner ID'
FROM partner_permissions
WHERE partner_id = @partner_id
AND is_granted = 1;

-- =================================================================
-- INSTRUCTIONS FOR PARTNER TO TEST:
-- 1. Login to partner portal: https://www.rhemazimbabwe.com/partnerportal/login
-- 2. After login, check the sidebar
-- 3. Under "ADDITIONAL RESOURCES" section, you should see:
--    - Library (if granted)
--    - Online Courses (if granted)
--    - Download Centre (if granted)
--    - Google Meet (if granted)
--    - Zoom (if granted)
-- 4. If you don't see this section, no permissions have been granted yet
-- =================================================================

