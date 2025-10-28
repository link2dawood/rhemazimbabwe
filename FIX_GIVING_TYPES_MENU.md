# Fix Giving Types Menu in Sidebar

## Problem
The "Giving Types" menu item is not showing under the Partners section in the sidebar.

## Solution
Run the SQL query below to add the menu item.

---

## Step 1: Check if Menu Exists

Open your database tool (phpMyAdmin, MySQL Workbench, etc.) and run:

```sql
USE ssdb;

SELECT * FROM sidebar_sub_menus WHERE `key` = 'giving_types';
```

### If Menu Exists (Returns 1 row)
The menu exists but may be inactive. **Run Step 2A**.

### If Menu Does NOT Exist (Returns 0 rows)
The menu needs to be created. **Run Step 2B**.

---

## Step 2A: Activate Existing Menu

If the menu exists but is inactive, run:

```sql
UPDATE `sidebar_sub_menus`
SET
    `is_active` = 1,
    `url` = 'admin/givingtypes',
    `activate_controller` = 'givingtypes',
    `activate_methods` = 'index,add,edit,show,delete'
WHERE `key` = 'giving_types';
```

---

## Step 2B: Create New Menu Item

If the menu doesn't exist, run:

```sql
-- First, get the next available ID
SELECT MAX(id) + 1 as next_id FROM sidebar_sub_menus;

-- Then insert the menu (replace <NEXT_ID> with the number from above)
INSERT INTO `sidebar_sub_menus`
(`id`, `sidebar_menu_id`, `menu`, `key`, `lang_key`, `url`, `level`, `access_permissions`, `permission_group_id`, `activate_controller`, `activate_methods`, `addon_permission`, `is_active`, `short_code`)
VALUES
(<NEXT_ID>, 40, 'Giving Types', 'giving_types', 'giving_types', 'admin/givingtypes', 4, '(\'partners\', \'can_view\')', 32, 'givingtypes', 'index,add,edit,show,delete', '', 1, '');
```

**Example:** If `next_id` is 285, run:
```sql
INSERT INTO `sidebar_sub_menus`
(`id`, `sidebar_menu_id`, `menu`, `key`, `lang_key`, `url`, `level`, `access_permissions`, `permission_group_id`, `activate_controller`, `activate_methods`, `addon_permission`, `is_active`, `short_code`)
VALUES
(285, 40, 'Giving Types', 'giving_types', 'giving_types', 'admin/givingtypes', 4, '(\'partners\', \'can_view\')', 32, 'givingtypes', 'index,add,edit,show,delete', '', 1, '');
```

---

## Step 3: Verify the Menu

Run this to confirm the menu is now active:

```sql
SELECT
    sm.menu as 'Main Menu',
    ssm.menu as 'Sub Menu',
    ssm.url,
    ssm.is_active,
    ssm.level
FROM sidebar_sub_menus ssm
JOIN sidebar_menus sm ON sm.id = ssm.sidebar_menu_id
WHERE ssm.key = 'giving_types';
```

**Expected Result:**
```
Main Menu  | Sub Menu      | url                  | is_active | level
-----------|---------------|----------------------|-----------|------
Partners   | Giving Types  | admin/givingtypes    | 1         | 4
```

---

## Step 4: View All Partners Menu Items

To see all menu items under Partners (to verify the order):

```sql
SELECT
    ssm.id,
    ssm.menu,
    ssm.url,
    ssm.level,
    ssm.is_active
FROM sidebar_sub_menus ssm
WHERE ssm.sidebar_menu_id = 40
ORDER BY ssm.level;
```

**Expected Output:**
```
id  | menu                  | url                      | level | is_active
----|-----------------------|--------------------------|-------|----------
... | Partner List          | admin/partners           | 1     | 1
... | Add Partner           | admin/partners/add       | 2     | 1
... | Contributions         | admin/partnercontributions| 3    | 1
... | Giving Types          | admin/givingtypes        | 4     | 1
... | Giving Frequencies    | admin/givingfrequencies  | 5     | 1
... | Partner Reports       | admin/partnerreports     | 6     | 1
```

---

## Step 5: Clear Cache & Refresh

1. **Clear browser cache** (Ctrl + F5)
2. **Logout and login again** to refresh session
3. Navigate to the Partners menu in the sidebar

---

## Troubleshooting

### Menu Still Not Showing?

**Check Permission:**
```sql
-- Verify you have the partners permission
SELECT r.name as role_name, p.feature_name, p.can_view
FROM roles r
JOIN role_permissions p ON p.role_id = r.id
WHERE p.feature_name = 'partners'
AND r.id = <YOUR_ROLE_ID>;
```

**Check If Partners Main Menu is Active:**
```sql
SELECT id, menu, is_active FROM sidebar_menus WHERE id = 40;
```

**Make sure Partners main menu is active:**
```sql
UPDATE sidebar_menus SET is_active = 1 WHERE id = 40;
```

---

## Alternative: Run Migration

If you prefer to use CodeIgniter migrations:

```bash
# Navigate to your project root
cd C:\xampp\htdocs\rhemazimbabwe

# Run migration (if available)
php index.php migrate/version/136
```

---

## Quick SQL Script (All-in-One)

Run this if you want to do everything at once:

```sql
USE ssdb;

-- Delete if exists (to start fresh)
DELETE FROM sidebar_sub_menus WHERE `key` = 'giving_types';

-- Insert the menu
INSERT INTO `sidebar_sub_menus`
(`sidebar_menu_id`, `menu`, `key`, `lang_key`, `url`, `level`, `access_permissions`, `permission_group_id`, `activate_controller`, `activate_methods`, `addon_permission`, `is_active`, `short_code`)
VALUES
(40, 'Giving Types', 'giving_types', 'giving_types', 'admin/givingtypes', 4, '(\'partners\', \'can_view\')', 32, 'givingtypes', 'index,add,edit,show,delete', '', 1, '');

-- Verify
SELECT
    sm.menu as 'Main Menu',
    ssm.menu as 'Sub Menu',
    ssm.url,
    ssm.is_active
FROM sidebar_sub_menus ssm
JOIN sidebar_menus sm ON sm.id = ssm.sidebar_menu_id
WHERE ssm.key = 'giving_types';
```

---

## Done!

After running the SQL:
1. ✅ Clear browser cache
2. ✅ Logout and login again
3. ✅ Check Partners menu in sidebar
4. ✅ You should see "Giving Types" submenu

The menu should now appear under Partners → Giving Types

---

**Need More Help?**
- Check `application/sql/add_giving_types_menu.sql` for additional queries
- Review the full documentation: `GIVING_TYPES_CRUD_DOCUMENTATION.md`
