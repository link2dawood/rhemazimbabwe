-- Add Events Access permission type
INSERT INTO partner_permission_types (permission_name, permission_code, description, is_active, sort_order)
VALUES ('Events Access', 'events_access', 'Access to school events and calendar', 1, 6)
ON DUPLICATE KEY UPDATE 
    permission_name = 'Events Access',
    description = 'Access to school events and calendar',
    is_active = 1,
    sort_order = 6;

-- Verify all permission types exist
SELECT * FROM partner_permission_types WHERE is_active = 1 ORDER BY sort_order;

