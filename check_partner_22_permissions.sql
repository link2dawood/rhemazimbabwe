-- =====================================================
-- CHECK PARTNER ID 22 PERMISSIONS
-- Diagnostic script to see what's wrong
-- =====================================================

SELECT '=== STEP 1: Check Permission Types in Database ===' as '';
SELECT id, permission_name, permission_code, is_active 
FROM partner_permission_types 
ORDER BY id;

SELECT '=== STEP 2: Check Permissions Granted to Partner 22 ===' as '';
SELECT 
    pp.id,
    pp.partner_id,
    ppt.permission_name,
    ppt.permission_code,
    pp.is_active as permission_active,
    pp.granted_at
FROM partner_permissions pp
JOIN partner_permission_types ppt ON pp.permission_type_id = ppt.id
WHERE pp.partner_id = 22
ORDER BY ppt.permission_code;

SELECT '=== STEP 3: Check Partner 22 Details ===' as '';
SELECT 
    id,
    partner_code,
    firstname,
    lastname,
    email,
    status,
    is_active
FROM partners
WHERE id = 22;

SELECT '=== STEP 4: Count Active Permissions for Partner 22 ===' as '';
SELECT 
    COUNT(*) as total_permissions_granted
FROM partner_permissions pp
WHERE pp.partner_id = 22 
AND pp.is_active = 1;

SELECT '=== STEP 5: List Missing Permissions (if any) ===' as '';
-- Show permission types that partner 22 does NOT have
SELECT 
    ppt.id,
    ppt.permission_name,
    ppt.permission_code
FROM partner_permission_types ppt
WHERE ppt.id NOT IN (
    SELECT permission_type_id 
    FROM partner_permissions 
    WHERE partner_id = 22
)
AND ppt.is_active = 1
ORDER BY ppt.permission_code;

SELECT '=== DIAGNOSIS COMPLETE ===' as '';

