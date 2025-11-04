-- Fix duplicate Events Access permissions
-- First, let's see what we have
SELECT 
    id, 
    permission_code, 
    permission_name, 
    description, 
    is_active, 
    created_at 
FROM partner_permission_types 
WHERE permission_code LIKE '%event%' OR permission_name LIKE '%Event%'
ORDER BY id;

-- Delete duplicate 'events_access' entries, keeping only the oldest one (lowest ID)
DELETE FROM partner_permission_types 
WHERE permission_code = 'events_access' 
AND id NOT IN (
    SELECT * FROM (
        SELECT MIN(id) FROM partner_permission_types 
        WHERE permission_code = 'events_access'
    ) AS temp
);

-- Verify the fix
SELECT 
    id, 
    permission_code, 
    permission_name, 
    description, 
    is_active 
FROM partner_permission_types 
WHERE permission_code = 'events_access';

-- Show all active permissions to ensure we only have one of each
SELECT 
    permission_code,
    COUNT(*) as count,
    GROUP_CONCAT(id) as ids,
    GROUP_CONCAT(permission_name) as names
FROM partner_permission_types 
WHERE is_active = 1
GROUP BY permission_code
HAVING COUNT(*) > 1;

