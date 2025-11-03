# 🔍 Partner Permissions Troubleshooting Guide

## ❓ **Your Issue:**
- Partner has 6 permissions granted (library, online_courses, download_centre, gmeet, zoom, events_access)
- Only 2 menu items appear in sidebar (Download Centre and Online Courses)
- Online Courses redirects to 404 page

---

## ✅ **All Issues Fixed!**

### **Fix 1: All 6 Permission Types Now Supported** ✅
Added support for all permission types in the sidebar:
1. ✅ Library (`library`)
2. ✅ Online Courses (`online_courses`)
3. ✅ Download Centre (`download_centre`)
4. ✅ Google Meet (`gmeet`)
5. ✅ Zoom (`zoom`)
6. ✅ Events & Calendar (`events_access`)

### **Fix 2: All Pages Now Exist** ✅
Created view pages for all permissions:
- ✅ `application/views/partner/library.php`
- ✅ `application/views/partner/courses.php`
- ✅ `application/views/partner/downloads.php`
- ✅ `application/views/partner/events.php`

### **Fix 3: Online Courses No Longer 404** ✅
- Fixed redirect to proper URL
- Shows custom page if module not installed
- No more 404 errors

### **Fix 4: All Controller Methods Added** ✅
Added all methods to `Partnerdashboard.php`:
- ✅ `library()` method
- ✅ `courses()` method
- ✅ `downloads()` method
- ✅ `gmeet()` method
- ✅ `zoom()` method
- ✅ `events()` method

---

## 🔍 **Debugging Tools Created**

### **Tool 1: check_partner_permissions.php**
**URL:** `http://localhost/rhemazimbabwe/check_partner_permissions.php`

This diagnostic tool shows:
- ✓ Partner information
- ✓ All available permission types in system
- ✓ What permissions are granted to specific partner
- ✓ What will show in sidebar
- ✓ SQL to grant all permissions quickly

**How to use:**
1. Open `check_partner_permissions.php`
2. Change `$PARTNER_EMAIL` to your test partner's email
3. Run the file in browser
4. See detailed permission analysis

### **Tool 2: Console Debug**
When partner logs into dashboard, check browser console (F12):
```
Partner Permissions Loaded: ["library", "online_courses", "download_centre", "gmeet", "zoom", "events_access"]
```

This shows exactly what permissions the system detected.

---

## 🧪 **Testing Steps**

### **Step 1: Check Database Permissions**
Run this SQL to see what's actually in the database:

```sql
-- Check partner by email
SELECT id, partner_code, firstname, lastname, email, status 
FROM partners 
WHERE email = 'your-partner-email@example.com';

-- Use the partner ID from above, then check permissions:
SELECT 
    pp.permission_code,
    ppt.permission_name,
    pp.is_granted,
    pp.granted_at
FROM partner_permissions pp
LEFT JOIN partner_permission_types ppt ON ppt.permission_code = pp.permission_code
WHERE pp.partner_id = YOUR_PARTNER_ID
AND pp.is_granted = 1;
```

### **Step 2: Ensure Permission Types Exist**
Run this SQL to verify all 6 permission types exist:

```sql
SELECT * FROM partner_permission_types WHERE is_active = 1 ORDER BY sort_order;
```

**Expected Results:** Should show 6 rows:
1. Library (library)
2. Online Courses (online_courses)  
3. Download Centre (download_centre)
4. Google Meet (gmeet)
5. Zoom (zoom)
6. Events Access (events_access) - **NEW!**

If "events_access" is missing, run:
```sql
-- Run this SQL file
SOURCE add_events_access_permission.sql;
```

Or manually:
```sql
INSERT INTO partner_permission_types (permission_name, permission_code, description, is_active, sort_order)
VALUES ('Events Access', 'events_access', 'Access to school events and calendar', 1, 6);
```

### **Step 3: Grant All Permissions to Test Partner**
Replace `YOUR_PARTNER_ID` with actual ID:

```sql
-- Clear existing permissions
DELETE FROM partner_permissions WHERE partner_id = YOUR_PARTNER_ID;

-- Grant all 6 permissions
INSERT INTO partner_permissions (partner_id, permission_code, is_granted, granted_by, granted_at)
VALUES
(YOUR_PARTNER_ID, 'library', 1, 1, NOW()),
(YOUR_PARTNER_ID, 'online_courses', 1, 1, NOW()),
(YOUR_PARTNER_ID, 'download_centre', 1, 1, NOW()),
(YOUR_PARTNER_ID, 'gmeet', 1, 1, NOW()),
(YOUR_PARTNER_ID, 'zoom', 1, 1, NOW()),
(YOUR_PARTNER_ID, 'events_access', 1, 1, NOW());
```

### **Step 4: Clear Partner Session**
After granting permissions, partner must:
1. Logout from partner portal
2. Clear browser cache (Ctrl+Shift+Delete)
3. Login again
4. Check sidebar

---

## 🎯 **Expected Results After Fix**

### **Partner Sidebar Should Show:**

**PARTNER PORTAL**
- 🏠 Dashboard
- 💰 My Contributions
- ⚙️ Giving Settings
- 👤 My Profile
- 🔒 Change Password

**ADDITIONAL RESOURCES** (only if permissions granted)
- 📚 Library
- 🎓 Online Courses
- 📥 Download Centre
- 📹 Google Meet
- 📹 Zoom
- 📅 Events & Calendar

---

## 🚨 **Common Issues & Solutions**

### **Issue 1: Only 2 permissions showing instead of 6**

**Possible Causes:**
1. ❌ Permission codes in database don't match sidebar codes
2. ❌ `events_access` permission type doesn't exist
3. ❌ Partner session cached old permissions
4. ❌ Admin granted wrong permission codes

**Solutions:**
- ✅ Run `add_events_access_permission.sql`
- ✅ Clear partner session (logout/login)
- ✅ Check permission codes match exactly
- ✅ Use diagnostic tool to verify

### **Issue 2: Online Courses shows 404**

**Cause:** Was redirecting to non-existent URL

**Solution:** ✅ **FIXED!** Now shows custom page

### **Issue 3: GMeet/Zoom not showing**

**Possible Causes:**
1. ❌ Modules not installed
2. ❌ Permission codes wrong

**Solution:**
- ✅ Check if addons are installed
- ✅ Verify permission codes are `gmeet` and `zoom` (not `gmeet_access` or `zoom_access`)

---

## 📊 **Permission Code Reference**

| What Admin Sees | Actual Code in DB | Sidebar Checks For | Status |
|----------------|-------------------|-------------------|--------|
| Library Access | `library` | `library` | ✅ Match |
| Online Courses | `online_courses` | `online_courses` | ✅ Match |
| Download Centre | `download_centre` | `download_centre` | ✅ Match |
| Google Meet | `gmeet` | `gmeet` | ✅ Match |
| Zoom | `zoom` | `zoom` | ✅ Match |
| Events Access | `events_access` | `events_access` | ✅ Match |

---

## 🔧 **Quick Fix Commands**

### **For Live Server:**
Access via phpMyAdmin or SSH and run:

```sql
-- 1. Add events_access permission type (if missing)
INSERT INTO partner_permission_types (permission_name, permission_code, description, is_active, sort_order)
VALUES ('Events Access', 'events_access', 'Access to school events and calendar', 1, 6)
ON DUPLICATE KEY UPDATE permission_name = permission_name;

-- 2. Check a specific partner's permissions
SELECT p.email, pp.permission_code, ppt.permission_name
FROM partners p
LEFT JOIN partner_permissions pp ON pp.partner_id = p.id AND pp.is_granted = 1
LEFT JOIN partner_permission_types ppt ON ppt.permission_code = pp.permission_code
WHERE p.email = 'partner@example.com';
```

---

## ✅ **Verification Checklist**

After implementing fixes, verify:

- [ ] Run SQL to add `events_access` permission type
- [ ] Check `partner_permission_types` table has all 6 types
- [ ] Admin can see all 6 checkboxes in permissions page
- [ ] Grant all 6 permissions to test partner
- [ ] Partner logs out and back in
- [ ] Check browser console shows all 6 permissions loaded
- [ ] Sidebar shows all 6 menu items under "ADDITIONAL RESOURCES"
- [ ] Clicking each menu item loads a page (no 404)
- [ ] Download Centre works
- [ ] Library works
- [ ] Online Courses works
- [ ] Events works
- [ ] GMeet shows error if module not installed (expected)
- [ ] Zoom shows error if module not installed (expected)

---

## 🎉 **Summary**

All issues have been fixed:

1. ✅ **Added `events_access` permission type** - SQL file created
2. ✅ **All 6 permissions now in sidebar** - Updated header.php
3. ✅ **All controller methods created** - No more 404 errors
4. ✅ **All view pages created** - Library, Courses, Events, Downloads
5. ✅ **Online Courses fixed** - No more redirect to 404
6. ✅ **Debug tools created** - Easy to troubleshoot
7. ✅ **Permission codes aligned** - Everything matches

**All 6 permissions should now appear in the sidebar when granted!** 🚀

---

*Last Updated: November 2025*

