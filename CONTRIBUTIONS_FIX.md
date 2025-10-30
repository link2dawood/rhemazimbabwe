# Partner Contributions - Issue Fix Guide

## Understanding the 303 Status Code

**Status 303 "See Other"** is **NORMAL** behavior when adding a contribution.

### What's Happening:
1. You submit the form (POST request) to `/admin/partnercontributions/add`
2. Server processes the data
3. Server responds with **303 redirect** to `/admin/partnercontributions/show/{id}`
4. Browser automatically follows redirect to show the contribution details

**This is the expected flow!**

---

## Common Issues & Solutions

### Issue 1: 303 Redirect but No Data Saved
**Symptom:** Form submits, redirects, but contribution doesn't appear in list

**Causes:**
- Database insert failed silently
- Validation passed but insert returned false
- Receipt number generation failed

**Fix:**
```sql
-- Check if data is actually being inserted
SELECT * FROM partner_contributions ORDER BY created_at DESC LIMIT 5;

-- If no data, check table structure
DESCRIBE partner_contributions;

-- Ensure these columns exist:
-- id, partner_id, amount, currency, contribution_date,
-- payment_method, receipt_no, status, recorded_by
```

---

### Issue 2: Error After Redirect
**Symptom:** Redirects to show page but shows "Contribution not found"

**Cause:** Insert succeeded but returned wrong ID, or show method can't find record

**Fix:**
```sql
-- Check last inserted contribution
SELECT * FROM partner_contributions ORDER BY id DESC LIMIT 1;

-- If it exists, the issue is with the show method
-- Check application/logs/log-YYYY-MM-DD.php for errors
```

---

### Issue 3: File Upload Fails
**Symptom:** Form submits but attached file doesn't save

**Fix:**
```bash
# Check if directory exists
ls uploads/partner_contributions

# If not, create it
mkdir -p uploads/partner_contributions
chmod 777 uploads/partner_contributions

# On Windows:
# Create folder: C:\xampp\htdocs\rhemazimbabwe\uploads\partner_contributions
# Right-click → Properties → Security → Give full control
```

---

### Issue 4: Receipt Number Not Generated
**Symptom:** Contribution saves but receipt_no is empty

**Fix:** The model auto-generates receipt numbers. Check if method exists:

```php
// In Contribution_model.php, ensure this exists:
public function generateReceiptNumber()
{
    do {
        $receipt_no = 'RCT-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $exists = $this->db->where('receipt_no', $receipt_no)->count_all_results('partner_contributions');
    } while ($exists > 0);

    return $receipt_no;
}
```

---

### Issue 5: Validation Errors Don't Show
**Symptom:** Form has errors but doesn't display them

**Fix:** Check the add view has validation error display:

```php
<?php if (validation_errors()) { ?>
    <div class="alert alert-danger alert-dismissible">
        <?php echo validation_errors(); ?>
    </div>
<?php } ?>
```

---

## Quick Diagnostic Tool

### Option 1: Use the Checker
Visit: `http://localhost/rhemazimbabwe/check_contributions.php`

This will:
- Check database table structure
- Verify upload directories
- Show recent contributions
- Test data insertion
- Provide fix buttons

### Option 2: Manual Checks

#### Check Database
```sql
-- 1. Check table exists
SHOW TABLES LIKE 'partner_contributions';

-- 2. Check structure
DESCRIBE partner_contributions;

-- 3. Check recent records
SELECT * FROM partner_contributions ORDER BY created_at DESC LIMIT 5;

-- 4. Check partners exist
SELECT COUNT(*) FROM partners WHERE is_active = 1;
```

#### Check Files
```bash
# Controller exists?
ls application/controllers/admin/Partnercontributions.php

# Model exists?
ls application/models/Contribution_model.php

# View exists?
ls application/views/admin/contributions/contributionadd.php
```

---

## Test the System

### Step 1: Add Test Contribution
1. Go to: `http://localhost/rhemazimbabwe/admin/partnercontributions/add`
2. Fill in form:
   - Select a partner
   - Enter date (today)
   - Enter amount (e.g., 100.00)
   - Select payment method (e.g., Cash)
   - Optionally add notes
3. Click "Save"

### Step 2: Check Result
**Expected:** Should redirect to contribution details page showing:
- Receipt number (RCT-YYYYMMDD-XXXX)
- Partner name
- Amount
- Date
- Payment method
- Status

### Step 3: Verify in List
1. Go to: `http://localhost/rhemazimbabwe/admin/partnercontributions`
2. Should see your contribution in the list

---

## Required Fields

These fields are **required** when adding a contribution:

| Field | Required | Validation | Default |
|-------|----------|------------|---------|
| Partner | ✓ Yes | Must exist | - |
| Contribution Date | ✓ Yes | Valid date | - |
| Amount | ✓ Yes | Numeric > 0 | - |
| Payment Method | ✓ Yes | Must select | - |
| Currency | No | - | USD |
| Status | No | - | completed |
| Receipt No | No | Auto-generated | RCT-... |

---

## Understanding the Flow

```
User fills form
    ↓
POST to /admin/partnercontributions/add
    ↓
Validation runs
    ↓
    ├─→ FAIL: Reload form with errors
    │
    └─→ PASS: Process data
            ↓
        Insert into database
            ↓
        Generate receipt number
            ↓
        Upload file (if any)
            ↓
        303 Redirect to /admin/partnercontributions/show/{id}
            ↓
        Display contribution details
```

---

## Debug Mode

### Enable Detailed Errors

1. **Edit:** `application/config/config.php`
2. **Set:**
   ```php
   $config['log_threshold'] = 4;
   ```
3. **Check logs:** `application/logs/log-YYYY-MM-DD.php`

### Check AJAX Response

1. Open DevTools (F12)
2. Go to Network tab
3. Submit form
4. Look for POST request to `/admin/partnercontributions/add`
5. Check:
   - Status (should be 303)
   - Response headers (should have Location header)
   - Any errors in console

---

## SQL Fix Scripts

### Ensure Table Has All Columns
```sql
-- Add missing columns if needed
ALTER TABLE partner_contributions
ADD COLUMN IF NOT EXISTS receipt_no VARCHAR(50) NULL AFTER transaction_id;

ALTER TABLE partner_contributions
ADD COLUMN IF NOT EXISTS recorded_by INT NULL AFTER status;

ALTER TABLE partner_contributions
ADD COLUMN IF NOT EXISTS attachment VARCHAR(255) NULL AFTER notes;
```

### Add Test Data
```sql
-- Add a test contribution
INSERT INTO partner_contributions
(partner_id, amount, currency, contribution_date, payment_method, receipt_no, status, recorded_by, notes)
VALUES
(1, 500.00, 'USD', CURDATE(), 'cash', 'RCT-TEST-001', 'completed', 1, 'Test contribution');

-- Verify it was added
SELECT * FROM partner_contributions WHERE receipt_no = 'RCT-TEST-001';
```

---

## What the 303 Status Means

### HTTP Status Codes:
- **200 OK** - Page loaded successfully
- **302 Found** - Temporary redirect
- **303 See Other** - **Used after POST to redirect to GET** ✓ (This is what you have!)
- **404 Not Found** - Page doesn't exist
- **500 Error** - Server error

**303 is the CORRECT status for form submissions!**

It's a POST-Redirect-GET pattern to prevent duplicate submissions.

---

## Still Having Issues?

### Checklist:
- [ ] Run `check_contributions.php` diagnostic tool
- [ ] Check `application/logs/` for PHP errors
- [ ] Verify database table structure
- [ ] Ensure upload directory exists and is writable
- [ ] Test with browser DevTools open to see actual errors
- [ ] Try adding a contribution manually via SQL
- [ ] Check if partners exist in database

### Get Help:
1. Note the exact error message (if any)
2. Check browser console (F12)
3. Check `application/logs/log-YYYY-MM-DD.php`
4. Run the diagnostic tool
5. Check if data is actually in database

---

## Success Criteria

✅ **Contributions working if:**
1. Form loads without errors
2. Can select partner from dropdown
3. Form submits and redirects (303)
4. See contribution details after redirect
5. Contribution appears in list
6. Receipt number is generated
7. Data is in database

---

**The 303 status is normal! If you're seeing an error AFTER the redirect, that's the actual issue to investigate.**
