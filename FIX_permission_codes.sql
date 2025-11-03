-- =====================================================
-- FIX PARTNER PERMISSION CODES
-- Updates permission codes to match sidebar expectations
-- =====================================================

-- The sidebar checks for these codes:
-- 'library', 'online_courses', 'download_centre', 'gmeet', 'zoom', 'events_access'

-- Step 1: Update incorrect permission codes
UPDATE partner_permission_types 
SET permission_code = 'library' 
WHERE permission_code IN ('library_access', 'Library Access', 'LibraryAccess')
AND permission_code != 'library';

UPDATE partner_permission_types 
SET permission_code = 'gmeet' 
WHERE permission_code IN ('gmeet_access', 'Google Meet Access', 'GMeetAccess')
AND permission_code != 'gmeet';

UPDATE partner_permission_types 
SET permission_code = 'zoom' 
WHERE permission_code IN ('zoom_access', 'Zoom Access', 'ZoomAccess')
AND permission_code != 'zoom';

UPDATE partner_permission_types 
SET permission_code = 'online_courses' 
WHERE permission_code IN ('online_course_access', 'Online Course Access', 'OnlineCourseAccess')
AND permission_code != 'online_courses';

UPDATE partner_permission_types 
SET permission_code = 'download_centre' 
WHERE permission_code IN ('download_center_access', 'Download Center Access', 'DownloadCenterAccess')
AND permission_code != 'download_centre';

UPDATE partner_permission_types 
SET permission_code = 'events_access' 
WHERE permission_code IN ('event_access', 'Events Access', 'EventsAccess')
AND permission_code != 'events_access';

-- Step 2: Verify the changes
SELECT '=== Updated Permission Codes ===' as '';
SELECT id, permission_name, permission_code, is_active 
FROM partner_permission_types 
ORDER BY permission_code;

-- Step 3: Show Partner 22's permissions with new codes
SELECT '=== Partner 22 Permissions (After Fix) ===' as '';
SELECT 
    pp.partner_id,
    ppt.permission_name,
    ppt.permission_code,
    pp.is_active
FROM partner_permissions pp
JOIN partner_permission_types ppt ON pp.permission_type_id = ppt.id
WHERE pp.partner_id = 22
ORDER BY ppt.permission_code;

SELECT '✅ Permission codes fixed! Partner should now see all menus in sidebar.' as Status;

