# Partner Reports Quick Fix Guide

## 🎯 Quick Start - 3 Steps

### Step 1: Check System Status
Visit: `http://localhost/rhemazimbabwe/check_reports.php`

This will:
- ✅ Check if all files exist
- ✅ Verify database tables
- ✅ Check menu configuration
- ✅ Verify permissions
- ✅ Check for test data

### Step 2: Fix Any Issues Found

The checker will show you exactly what's wrong and provide fix buttons.

### Step 3: Test Reports
Go to: **Partners → Partner Reports**

Or: `http://localhost/rhemazimbabwe/admin/partnerreports`

---

## 📊 The 4 Reports

### 1. Partner Information Report
**Shows:** Complete list of all partners with contribution totals

**URL:** `admin/partnerreports/partner_information`

**Features:**
- Filter by status, giving type, frequency
- Shows pledged vs actual contributions
- Export to Excel/PDF

---

### 2. Giving Collection By Type Report
**Shows:** Contributions grouped by giving types

**URL:** `admin/partnerreports/giving_collection_by_type`

**Features:**
- See which types receive most contributions
- Partner count per type
- Total amounts by type

---

### 3. Partner Statement Report
**Shows:** Transaction history for specific partner

**URL:** `admin/partnerreports/partner_statement`

**Features:**
- Select partner from dropdown
- See all their contributions
- Date range filtering
- Shows totals and balances

---

### 4. Balance Giving Report
**Shows:** Partners with outstanding contributions

**URL:** `admin/partnerreports/balance_giving_report`

**Features:**
- Calculates expected vs actual contributions
- Color-coded remarks (Critical/High/Moderate/Low)
- Only shows partners with balance > 0

---

## ⚠️ Common Issues & Solutions

### Issue 1: Can't Access Reports
**Error:** "Access Denied"

**Fix:**
```sql
-- Give yourself permission
UPDATE role_permissions
SET can_view = 1
WHERE role_id = (SELECT id FROM roles WHERE name = 'Admin')
AND feature_name = 'partner_reports';
```

---

### Issue 2: Reports Menu Not Showing
**Fix:** Run this SQL:

```sql
USE ssdb;

-- Get Partners menu ID
SET @partners_menu_id = (SELECT id FROM sidebar_menus WHERE menu = 'Partners' LIMIT 1);

-- Delete if exists
DELETE FROM sidebar_sub_menus WHERE `key` = 'partner_reports';

-- Add Partner Reports menu
INSERT INTO sidebar_sub_menus
(sidebar_menu_id, menu, `key`, lang_key, url, level, access_permissions, permission_group_id, activate_controller, activate_methods, addon_permission, is_active)
VALUES
(@partners_menu_id, 'Partner Reports', 'partner_reports', 'partner_reports', 'admin/partnerreports', 6, '(\'partner_reports\', \'can_view\')', NULL, 'partnerreports', 'index', '', 1);
```

Then:
1. Clear browser cache (Ctrl + Shift + Delete)
2. Logout and login again

---

### Issue 3: "No Data Available" in Reports
**Cause:** No contributions in database

**Fix:** Add test data:

```sql
-- Add a test partner
INSERT INTO partners (partner_code, firstname, lastname, email, mobileno, giving_type_id, giving_frequency_id, contribution_amount, currency, start_date, status, is_active)
VALUES ('PTR-2024-001', 'John', 'Doe', 'john@example.com', '0771234567', 1, 1, 500.00, 'USD', '2024-01-01', 'active', 1);

-- Get the partner ID
SET @partner_id = LAST_INSERT_ID();

-- Add some contributions
INSERT INTO partner_contributions (partner_id, giving_type_id, amount, currency, contribution_date, payment_method, receipt_no, status, recorded_by)
VALUES
(@partner_id, 1, 500.00, 'USD', '2024-01-15', 'cash', 'RCT-20240115-001', 'completed', 1),
(@partner_id, 1, 500.00, 'USD', '2024-02-15', 'bank_transfer', 'RCT-20240215-002', 'completed', 1),
(@partner_id, 1, 500.00, 'USD', '2024-03-15', 'mobile_money', 'RCT-20240315-003', 'completed', 1);
```

---

### Issue 4: DataTable Not Loading
**Check:**
1. Open browser console (F12)
2. Look for JavaScript errors
3. Go to Network tab
4. Look for failed AJAX requests

**Common Fix:**
Clear browser cache and refresh

---

### Issue 5: Export Buttons Not Working
**Cause:** PDF/Excel libraries not configured

**Check:**
- File exists: `application/libraries/Pdf.php`
- PHPExcel is installed in `application/third_party/`

---

## 🔍 Testing Checklist

Run through this checklist:

- [ ] Navigate to `admin/partnerreports`
- [ ] See 4 report cards
- [ ] Click "Partner Information Report"
- [ ] Page loads without errors
- [ ] Can see filter dropdowns
- [ ] Click "Search" button
- [ ] DataTable shows data
- [ ] Try filtering by status
- [ ] Go back and test other 3 reports

---

## 📝 Quick Test Commands

### Check Database Status
```sql
-- See if you have data for reports
SELECT
    (SELECT COUNT(*) FROM partners) as total_partners,
    (SELECT COUNT(*) FROM partner_contributions) as total_contributions,
    (SELECT SUM(amount) FROM partner_contributions WHERE status='completed') as total_collected,
    (SELECT COUNT(*) FROM giving_types) as giving_types_count;
```

### Check Reports Menu
```sql
-- Verify menu exists and is active
SELECT menu, url, is_active
FROM sidebar_sub_menus
WHERE `key` = 'partner_reports';
```

### Check Permissions
```sql
-- Check if partner_reports permission exists
SELECT * FROM permission_category WHERE short_code = 'partner_reports';
```

---

## 📞 Still Having Issues?

### Debug Steps:
1. **Run:** `http://localhost/rhemazimbabwe/check_reports.php`
2. **Read:** `PARTNER_REPORTS_TESTING_GUIDE.md` (full testing guide)
3. **Check logs:** `application/logs/log-YYYY-MM-DD.php`
4. **Browser console:** Press F12 and check for errors

### Common Error Messages:

| Error | Cause | Solution |
|-------|-------|----------|
| "Call to undefined method Contribution_model::getTotalByPartner" | Model missing method | Check model file exists |
| "Access denied" | No permission | Run SQL to grant permission |
| "404 Not Found" | Wrong URL | Check controller name is `Partnerreports` |
| "DataTable error" | No data/JS error | Check browser console, add test data |

---

## ✅ Success Criteria

Reports are working if:

1. ✅ Can access `/admin/partnerreports`
2. ✅ See 4 report cards
3. ✅ Each report opens without errors
4. ✅ DataTables load with data
5. ✅ Filters work and update results
6. ✅ No JavaScript errors in console
7. ✅ Export buttons function

---

## 🚀 All Working? Next Steps

Once reports are working:

1. **Add Real Data:** Add your actual partners and contributions
2. **Test with Filters:** Try different filter combinations
3. **Generate Reports:** Export to PDF/Excel to test
4. **Train Users:** Show staff how to use reports

---

## 📚 Additional Resources

- **Full Testing Guide:** `PARTNER_REPORTS_TESTING_GUIDE.md`
- **Giving Types CRUD:** `GIVING_TYPES_CRUD_DOCUMENTATION.md`
- **Partner Module:** `TESTING_GUIDE_PARTNER_GIVING_SETTINGS.md`

---

## 🛠️ Emergency Fix Script

If nothing else works, run this complete fix:

```sql
USE ssdb;

-- 1. Ensure permission exists
INSERT IGNORE INTO permission_category (perm_group_id, name, short_code, enable_view)
VALUES (32, 'Partner Reports', 'partner_reports', 1);

-- 2. Get Partners menu ID
SET @menu_id = (SELECT id FROM sidebar_menus WHERE menu LIKE '%Partner%' LIMIT 1);

-- 3. Add reports menu
DELETE FROM sidebar_sub_menus WHERE `key` = 'partner_reports';
INSERT INTO sidebar_sub_menus (sidebar_menu_id, menu, `key`, lang_key, url, level, access_permissions, activate_controller, activate_methods, is_active)
VALUES (@menu_id, 'Partner Reports', 'partner_reports', 'partner_reports', 'admin/partnerreports', 6, '(''partner_reports'', ''can_view'')', 'partnerreports', 'index', 1);

-- 4. Grant permission to admin role
UPDATE role_permissions
SET can_view = 1
WHERE feature_name = 'partner_reports';

-- Done!
SELECT 'Reports fixed! Clear cache, logout, and login again.' as STATUS;
```

---

**Good luck! Your reports should now be working properly.** 🎉
