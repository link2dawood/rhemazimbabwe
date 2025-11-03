-- Add Events Access permission type (without sort_order column)
INSERT INTO partner_permission_types (permission_name, permission_code, description, is_active)
VALUES ('Events Access', 'events_access', 'Access to school events and calendar', 1)
ON DUPLICATE KEY UPDATE 
    permission_name = 'Events Access',
    description = 'Access to school events and calendar',
    is_active = 1;

-- Verify all permission types exist
SELECT * FROM partner_permission_types WHERE is_active = 1 ORDER BY id;

