=====================================================
ESSENTIAL SQL FILES - CLEANED UP
=====================================================

ROOT DIRECTORY SQL FILES:
-------------------------

1. FIX_CONTRIBUTIONS.sql
   Purpose: Fix partner_contributions table AUTO_INCREMENT issue
   When to run: Run this NOW to fix the "Failed to add contribution" error
   Action: Open in phpMyAdmin and execute

2. u226341643_ssdb1.sql
   Purpose: Your database backup/dump
   When to run: For reference or restoration only
   Action: Keep as backup

3. SIMPLE_fix_giving_types_sidebar.sql
   Purpose: Add "Giving Types" and "Giving Frequencies" to admin sidebar
   When to run: Run this NOW to add both menus to admin sidebar
   Action: Run in phpMyAdmin (removes old Partner Settings menu)

4. add_events_access_permission.sql
   Purpose: Add Events Access permission type
   When to run: Already run (adds events_access to partner_permission_types)
   Action: Keep for reference

5. partner_reminder_templates_schema.sql
   Purpose: Create partner_reminder_templates table
   When to run: If table doesn't exist
   Action: Run if you get "Table doesn't exist" error

6. partner_registration_schema_updates.sql
   Purpose: Schema updates for partner registration
   Action: Keep for reference

7. partner_message_templates.sql
   Purpose: Message templates for partners
   Action: Keep for reference

8. partners_database_schema.sql
   Purpose: Complete partners database schema
   Action: Keep for reference

9. partner_login_schema.sql
   Purpose: Partner login system schema
   Action: Keep for reference

10. partner_giving_settings_schema.sql
    Purpose: Partner giving settings schema
    Action: Keep for reference

11. db.sql
    Purpose: General database file
    Action: Keep for reference


APPLICATION SQL FILES (application/sql/):
-----------------------------------------
These are kept for system reference and setup.


ADDONS SQL FILES (addons/*/):
------------------------------
These are part of installed addons - DO NOT DELETE.


DELETED FILES (Cleanup Complete):
----------------------------------
✓ Removed all duplicate SQL files
✓ Removed all test/debug SQL files
✓ Removed Debug_contribution.php controller
✓ Removed old fix/quick-fix files
✓ Removed outdated menu fix files

=====================================================
NEXT STEPS:
=====================================================

1. Run FIX_CONTRIBUTIONS.sql in phpMyAdmin NOW
2. Test adding a contribution
3. If successful, you can delete FIX_CONTRIBUTIONS.sql

=====================================================

