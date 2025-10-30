# Partner Reports Testing Guide

## Overview
This guide will help you test all 4 Partner Reports to ensure they're working properly with contributions.

---

## Prerequisites

### 1. Ensure Database Has Data
You need test data in these tables:
- `partners` - At least 3-5 partners
- `partner_contributions` - At least 10+ contributions
- `giving_types` - At least 3-5 giving types
- `giving_frequencies` - At least 3-4 frequencies

### 2. Check Permissions
Make sure your user role has:
- `partner_reports` → `can_view` permission

### 3. Verify Menu Access
Go to: **Partners → Partner Reports**

Or directly: `http://localhost/rhemazimbabwe/admin/partnerreports`

---

## The 4 Reports

### 1. Partner Information Report
**Purpose:** Shows comprehensive list of all partners with their details

**URL:** `http://localhost/rhemazimbabwe/admin/partnerreports/partner_information`

**Test Steps:**
1. Click "Partner Information Report" from the reports index
2. You should see filter options:
   - Status (Active/Inactive/Suspended)
   - Giving Type
   - Giving Frequency
   - Date From/To
3. Click "Search" button
4. DataTable should load with columns:
   - Partner Code
   - Name
   - Email
   - Mobile
   - Giving Type
   - Frequency
   - Pledged Amount
   - Total Contributed
   - Start Date
   - Status
5. Try filtering by status
6. Try exporting to Excel/PDF

**Expected Result:**
- Data loads in table
- Filters work properly
- Export buttons function

---

### 2. Giving Collection By Type Report
**Purpose:** Shows contributions grouped by giving types

**URL:** `http://localhost/rhemazimbabwe/admin/partnerreports/giving_collection_by_type`

**Test Steps:**
1. Click "Giving Collection By Type Report"
2. Filter options:
   - Giving Type
   - Date From/To
3. Click "Search"
4. DataTable shows:
   - Giving Type
   - Number of Partners
   - Number of Transactions
   - Total Amount
5. Try filtering by specific type
6. Try date range filter

**Expected Result:**
- Grouped data by type
- Accurate counts and totals
- Chart visualization (if implemented)

---

### 3. Partner Statement Report
**Purpose:** Shows detailed transaction history for a specific partner

**URL:** `http://localhost/rhemazimbabwe/admin/partnerreports/partner_statement`

**Test Steps:**
1. Click "Partner Statement Report"
2. Select a partner from dropdown
3. Optionally set date range
4. Click "Search"
5. Should display:
   - Partner summary (name, code, totals)
   - Transaction table:
     - Date
     - Receipt No
     - Notes
     - Payment Method
     - Amount
     - Status
6. Summary shows:
   - Total Transactions
   - Total Amount
   - Completed Amount
   - Pending Amount

**Expected Result:**
- Partner dropdown populates
- Statement loads correctly
- All transactions visible
- Accurate totals

---

### 4. Balance Giving Report
**Purpose:** Shows partners with outstanding/expected contributions

**URL:** `http://localhost/rhemazimbabwe/admin/partnerreports/balance_giving_report`

**Test Steps:**
1. Click "Balance Giving Report"
2. Filter by:
   - Giving Type
   - Giving Frequency
3. Click "Search"
4. Table shows partners with balance:
   - Partner Code
   - Name
   - Contact Info
   - Giving Type/Frequency
   - Expected Amount
   - Contributed Amount
   - Balance (Outstanding)
   - Remark (Critical/High/Moderate/Low)
5. Only shows partners with balance > 0

**Expected Result:**
- Partners with outstanding balance appear
- Expected amount calculated correctly based on frequency
- Remark colors indicate severity
- Balance = Expected - Contributed

---

## Common Issues & Solutions

### Issue 1: "Access Denied" Error
**Solution:**
```sql
-- Grant permission to your role
UPDATE role_permissions
SET can_view = 1
WHERE role_id = YOUR_ROLE_ID
AND feature_name = 'partner_reports';
```

### Issue 2: Reports Menu Not Showing
**Solution:**
```sql
-- Check if menu exists and is active
SELECT * FROM sidebar_sub_menus WHERE `key` = 'partner_reports';

-- Activate if exists
UPDATE sidebar_sub_menus SET is_active = 1 WHERE `key` = 'partner_reports';

-- If doesn't exist, add it
INSERT INTO sidebar_sub_menus
(sidebar_menu_id, menu, `key`, lang_key, url, level, access_permissions, permission_group_id, activate_controller, activate_methods, addon_permission, is_active)
VALUES
(40, 'Partner Reports', 'partner_reports', 'partner_reports', 'admin/partnerreports', 6, '(\'partner_reports\', \'can_view\')', NULL, 'partnerreports', 'index', '', 1);
```

### Issue 3: DataTable Not Loading
**Possible Causes:**
1. JavaScript error in console
2. AJAX endpoint returning error
3. No data in database

**Check:**
```javascript
// Open browser console (F12)
// Look for errors when clicking "Search"
// Check Network tab for AJAX call response
```

### Issue 4: "No Data Available" in Table
**Solution:**
1. Check if you have contributions in database:
```sql
SELECT COUNT(*) FROM partner_contributions;
```

2. Check if partners exist:
```sql
SELECT COUNT(*) FROM partners;
```

3. Add test data if needed (see below)

---

## Adding Test Data

### Add Test Partners
```sql
INSERT INTO partners (partner_code, firstname, lastname, email, mobileno, giving_type_id, giving_frequency_id, contribution_amount, currency, start_date, status, is_active)
VALUES
('PTR-2024-001', 'John', 'Doe', 'john@example.com', '0771234567', 1, 2, 500.00, 'USD', '2024-01-01', 'active', 1),
('PTR-2024-002', 'Jane', 'Smith', 'jane@example.com', '0779876543', 2, 3, 1000.00, 'USD', '2024-01-15', 'active', 1),
('PTR-2024-003', 'Bob', 'Johnson', 'bob@example.com', '0775551234', 1, 1, 250.00, 'USD', '2024-02-01', 'active', 1);
```

### Add Test Contributions
```sql
INSERT INTO partner_contributions (partner_id, giving_type_id, giving_frequency_id, amount, currency, contribution_date, payment_method, receipt_no, status, recorded_by)
VALUES
(1, 1, 2, 500.00, 'USD', '2024-01-15', 'bank_transfer', 'RCT-20240115-001', 'completed', 1),
(1, 1, 2, 500.00, 'USD', '2024-02-15', 'bank_transfer', 'RCT-20240215-001', 'completed', 1),
(2, 2, 3, 1000.00, 'USD', '2024-01-20', 'cash', 'RCT-20240120-001', 'completed', 1),
(3, 1, 1, 250.00, 'USD', '2024-02-05', 'mobile_money', 'RCT-20240205-001', 'completed', 1);
```

---

## Testing Checklist

### Pre-Testing
- [ ] Database has partners
- [ ] Database has contributions
- [ ] Database has giving types
- [ ] Database has giving frequencies
- [ ] User has `partner_reports` permission
- [ ] Reports menu visible in sidebar

### Report 1: Partner Information
- [ ] Page loads without errors
- [ ] Filters display correctly
- [ ] DataTable loads data
- [ ] All columns show correct data
- [ ] Status filter works
- [ ] Type filter works
- [ ] Frequency filter works
- [ ] Date range filter works
- [ ] Export to Excel works
- [ ] Export to PDF works

### Report 2: Giving Collection By Type
- [ ] Page loads without errors
- [ ] Filters display correctly
- [ ] DataTable groups by type correctly
- [ ] Transaction counts accurate
- [ ] Total amounts correct
- [ ] Type filter works
- [ ] Date range filter works
- [ ] Export functions work

### Report 3: Partner Statement
- [ ] Page loads without errors
- [ ] Partner dropdown populates
- [ ] Select partner loads statement
- [ ] All transactions display
- [ ] Summary shows correct totals
- [ ] Date range filter works
- [ ] Receipt numbers visible
- [ ] Payment methods show correctly
- [ ] Status labels correct

### Report 4: Balance Giving Report
- [ ] Page loads without errors
- [ ] Filters display correctly
- [ ] Only shows partners with balance
- [ ] Expected amount calculated correctly
- [ ] Balance = Expected - Contributed
- [ ] Remark colors appropriate
- [ ] Type filter works
- [ ] Frequency filter works
- [ ] Export functions work

---

## Manual Test Scenarios

### Scenario 1: Active Partners Report
1. Go to Partner Information Report
2. Filter: Status = "Active"
3. Click Search
4. **Expected:** Only active partners show

### Scenario 2: Tuition Contributions
1. Go to Giving Collection By Type
2. Filter: Type = "Tuition Support"
3. Click Search
4. **Expected:** Only tuition contributions grouped

### Scenario 3: Partner Statement
1. Go to Partner Statement
2. Select "John Doe" from dropdown
3. Set date range: This Month
4. Click Search
5. **Expected:** John's transactions for this month

### Scenario 4: Outstanding Balances
1. Go to Balance Giving Report
2. Filter: All types, All frequencies
3. Click Search
4. **Expected:** Partners owing money appear with red/yellow badges

---

## Performance Testing

### Test Large Data Sets
```sql
-- Check record counts
SELECT
    (SELECT COUNT(*) FROM partners) as partners_count,
    (SELECT COUNT(*) FROM partner_contributions) as contributions_count,
    (SELECT COUNT(*) FROM giving_types) as types_count;

-- Test query performance
-- Each report should load in < 3 seconds with 1000+ records
```

---

## Debug Mode

### Enable Debug Output
To see SQL queries and errors:

1. **Edit:** `application/config/config.php`
2. **Set:** `$config['log_threshold'] = 4;`
3. **Check logs:** `application/logs/log-YYYY-MM-DD.php`

### Check AJAX Responses
1. Open browser DevTools (F12)
2. Go to Network tab
3. Click "Search" on report
4. Look for AJAX call
5. Check Response tab for errors

---

## Success Criteria

✅ **Reports Working If:**
1. All 4 reports load without errors
2. DataTables display data correctly
3. Filters apply and update results
4. Export buttons function
5. No JavaScript errors in console
6. No PHP errors in logs
7. Data accuracy: Totals match database
8. Performance: Loads in < 3 seconds

---

## Troubleshooting Commands

### Check Report URL Access
```bash
# Direct URLs to test:
http://localhost/rhemazimbabwe/admin/partnerreports
http://localhost/rhemazimbabwe/admin/partnerreports/partner_information
http://localhost/rhemazimbabwe/admin/partnerreports/giving_collection_by_type
http://localhost/rhemazimbabwe/admin/partnerreports/partner_statement
http://localhost/rhemazimbabwe/admin/partnerreports/balance_giving_report
```

### Check Database Relationships
```sql
-- Verify foreign keys
SELECT
    p.id,
    p.partner_code,
    p.firstname,
    gt.name as giving_type,
    gf.name as frequency,
    COUNT(pc.id) as contribution_count
FROM partners p
LEFT JOIN giving_types gt ON p.giving_type_id = gt.id
LEFT JOIN giving_frequencies gf ON p.giving_frequency_id = gf.id
LEFT JOIN partner_contributions pc ON pc.partner_id = p.id
GROUP BY p.id;
```

### Verify Contributions Data
```sql
-- Check contributions with all details
SELECT
    pc.id,
    pc.receipt_no,
    p.partner_code,
    p.firstname,
    pc.amount,
    pc.status,
    pc.contribution_date,
    gt.name as giving_type
FROM partner_contributions pc
JOIN partners p ON p.id = pc.partner_id
LEFT JOIN giving_types gt ON gt.id = pc.giving_type_id
ORDER BY pc.contribution_date DESC
LIMIT 20;
```

---

## Contact & Support

If reports still don't work after following this guide:

1. Check browser console for JavaScript errors
2. Check `application/logs/` for PHP errors
3. Verify all database tables exist
4. Ensure data relationships are correct
5. Test with sample data provided above

**Common Error Messages:**
- "Call to undefined method" → Model method missing
- "Access denied" → Permission issue
- "Table doesn't exist" → Database migration needed
- "Cannot read property" → JavaScript/jQuery issue

---

**Last Updated:** 2024
**Version:** 1.0
