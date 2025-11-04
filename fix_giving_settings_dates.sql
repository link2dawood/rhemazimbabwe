-- Fix partner_giving_settings records with missing or invalid updated_at dates
-- Set updated_at to created_at for records where updated_at is null or invalid

UPDATE partner_giving_settings 
SET updated_at = created_at 
WHERE updated_at IS NULL 
   OR updated_at = '0000-00-00 00:00:00' 
   OR updated_at = '';

-- Verify the fix
SELECT 
    id,
    partner_id,
    giving_type_id,
    amount,
    currency,
    created_at,
    updated_at,
    CASE 
        WHEN updated_at IS NULL OR updated_at = '0000-00-00 00:00:00' THEN 'NEEDS FIX'
        ELSE 'OK'
    END as status
FROM partner_giving_settings
ORDER BY created_at DESC
LIMIT 20;

