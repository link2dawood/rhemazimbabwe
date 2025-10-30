# Partner Contributions - Complete Testing Guide

## 📋 Overview

This guide provides step-by-step instructions to test and verify the Partner Contributions system is working correctly.

---

## 🎯 Quick Start (3 Steps)

### Step 1: Run the Fix Tool
Visit: **http://localhost/rhemazimbabwe/fix_all_contributions.php**

This will:
- ✅ Check database table structure
- ✅ Add any missing columns
- ✅ Create upload directories
- ✅ Verify permissions
- ✅ Test database operations
- ✅ Run comprehensive diagnostics

**Expected Result:** Should show "✅ System Ready!" with green checkmarks

---

### Step 2: Test Adding a Contribution
1. Go to: **http://localhost/rhemazimbabwe/admin/partnercontributions/add**
2. Fill in the form (see details below)
3. Click "Save Contribution"

**Expected Result:** Redirects to contribution details page showing receipt number

---

### Step 3: Verify the Contribution
1. Go to: **http://localhost/rhemazimbabwe/admin/partnercontributions**
2. Look for your contribution in the list

**Expected Result:** Your contribution appears with all details correct

---

## 📝 Detailed Testing Steps

### Pre-Test Checklist

Before testing, ensure:

- [ ] XAMPP Apache and MySQL are running
- [ ] Database `ssdb` exists
- [ ] You can log in to the admin panel
- [ ] At least one active partner exists
- [ ] You have "Add Contribution" permission

---

### Test 1: Access the Add Page

**Steps:**
1. Login to admin panel
2. Navigate to: **Partners → Contributions**
3. Click **"Add Contribution"** button (or go directly to `/admin/partnercontributions/add`)

**What to Check:**
- ✅ Page loads without errors
- ✅ Form displays correctly
- ✅ Partner dropdown is populated
- ✅ All fields are visible
- ✅ No JavaScript errors in console (F12)

**If Issues:**
- Check browser console (F12) for JavaScript errors
- Verify you have permissions: `partners` module with `can_add`
- Check `application/logs/log-YYYY-MM-DD.php` for PHP errors

---

### Test 2: Form Validation

**Steps:**
1. Try to submit the form without filling any fields
2. Click "Save Contribution"

**Expected Result:**
- ❌ Form should NOT submit
- ❌ Validation errors appear:
  - "Please select a partner"
  - "Please select a contribution date"
  - "Please enter an amount"
  - "Please select a payment method"

**What to Check:**
- ✅ Required fields are marked with red asterisk (*)
- ✅ Error messages display in red
- ✅ Form fields are highlighted with error state

---

### Test 3: Add a Simple Contribution

**Steps:**
1. Fill in the form:
   - **Partner:** Select any active partner
   - **Contribution Date:** Select today's date
   - **Amount:** 100.00
   - **Currency:** USD (default)
   - **Payment Method:** Cash
   - **Status:** Completed (default)
   - Leave other fields empty for now

2. Click **"Save Contribution"**

**Expected Result:**
- ✅ Page redirects (Status 303 - this is CORRECT!)
- ✅ New URL: `/admin/partnercontributions/show/{number}`
- ✅ Shows contribution details page
- ✅ Receipt number is auto-generated (e.g., RCT-20241129-0001)
- ✅ All entered data displays correctly

**If Issues:**

**Issue: Redirects but shows "Contribution not found"**
- Check database: `SELECT * FROM partner_contributions ORDER BY id DESC LIMIT 1`
- If no data, insert failed silently
- Check logs: `application/logs/log-YYYY-MM-DD.php`

**Issue: Page doesn't redirect**
- Check browser Network tab (F12)
- Look for POST response
- Check for PHP errors

**Issue: Redirects to blank page**
- Check `contributionshow.php` view exists
- Verify controller `show()` method works

---

### Test 4: Add Contribution with All Fields

**Steps:**
1. Go to add page again
2. Fill in ALL fields:
   - **Partner:** Select a partner
   - **Contribution Date:** Today
   - **Amount:** 500.00
   - **Currency:** USD
   - **Payment Method:** Bank Transfer
   - **Status:** Completed
   - **Giving Type:** Select if available
   - **Giving Frequency:** Select if available
   - **Transaction ID:** TXN123456
   - **Reference Number:** REF789
   - **Notes:** "Test contribution with all fields"
   - **Attachment:** Upload a small PDF or image file

3. Click **"Save Contribution"**

**Expected Result:**
- ✅ All data saves correctly
- ✅ File uploads successfully
- ✅ Receipt number generated
- ✅ Details page shows all information
- ✅ Attachment link is displayed

**What to Check:**
- File was uploaded to: `uploads/partner_contributions/`
- File name in database: `SELECT attachment FROM partner_contributions WHERE id = {id}`
- File is accessible via browser

**If File Upload Fails:**
- Run: http://localhost/rhemazimbabwe/setup_uploads.php
- Check directory exists: `uploads/partner_contributions`
- Check directory is writable (permissions 777)
- Verify `enctype="multipart/form-data"` in form tag
- Check PHP settings: `upload_max_filesize` and `post_max_size`

---

### Test 5: Verify in Database

**Steps:**
1. Open phpMyAdmin or MySQL client
2. Run this query:

```sql
SELECT
    pc.*,
    p.firstname,
    p.lastname,
    gt.name as giving_type
FROM partner_contributions pc
LEFT JOIN partners p ON p.id = pc.partner_id
LEFT JOIN giving_types gt ON gt.id = pc.giving_type_id
ORDER BY pc.created_at DESC
LIMIT 5;
```

**Expected Result:**
- ✅ Your test contributions appear
- ✅ All fields have correct values
- ✅ `receipt_no` is populated
- ✅ `created_at` timestamp is set
- ✅ Foreign keys link correctly

---

### Test 6: View Contributions List

**Steps:**
1. Go to: **http://localhost/rhemazimbabwe/admin/partnercontributions**

**Expected Result:**
- ✅ Page loads
- ✅ DataTable displays contributions
- ✅ Search box works
- ✅ Pagination works (if > 10 contributions)
- ✅ Sorting works (click column headers)
- ✅ All columns display correctly:
  - Receipt No
  - Partner Name
  - Amount
  - Currency
  - Date
  - Payment Method
  - Status
  - Actions (View/Edit/Delete buttons)

---

### Test 7: View Contribution Details

**Steps:**
1. From contributions list, click **"View"** (eye icon) on any contribution

**Expected Result:**
- ✅ Details page loads
- ✅ Shows all contribution information
- ✅ Partner name displayed
- ✅ Receipt number shown
- ✅ Amount formatted correctly
- ✅ Status badge displayed
- ✅ If attachment exists, download link works
- ✅ Created/Updated timestamps shown

---

### Test 8: Edit a Contribution

**Steps:**
1. From details page or list, click **"Edit"** (pencil icon)
2. Change the amount to 250.00
3. Change notes to "Updated test contribution"
4. Click **"Update Contribution"**

**Expected Result:**
- ✅ Updates save successfully
- ✅ Redirects to details page
- ✅ Shows updated information
- ✅ `updated_at` timestamp changes
- ✅ Original `receipt_no` remains unchanged

**Important:** Receipt numbers should NEVER change after creation!

---

### Test 9: Delete a Contribution

**Steps:**
1. From list, click **"Delete"** (trash icon) on a test contribution
2. Confirm deletion in popup

**Expected Result:**
- ✅ Confirmation dialog appears
- ✅ After confirm, contribution is removed
- ✅ Success message displays
- ✅ List updates automatically
- ✅ Database record deleted (or soft-deleted)

**Note:** Check if system uses hard delete or soft delete (is_deleted flag)

---

### Test 10: Filter & Search

**Steps:**
1. Go to contributions list
2. Use search box:
   - Search by partner name
   - Search by receipt number
   - Search by amount
3. Use filters (if available):
   - Filter by status
   - Filter by date range
   - Filter by payment method

**Expected Result:**
- ✅ Search updates results in real-time
- ✅ Only matching contributions display
- ✅ Clear search shows all again

---

### Test 11: Test Reports Integration

**Steps:**
1. Add at least 3 contributions (if not already)
2. Go to: **Partners → Partner Reports**
3. Try each report:
   - **Partner Information Report** - should show contribution totals
   - **Giving Collection By Type** - should aggregate by type
   - **Partner Statement** - select a partner, should show their contributions
   - **Balance Giving Report** - should calculate expected vs actual

**Expected Result:**
- ✅ All reports load without errors
- ✅ Contribution data appears in reports
- ✅ Totals calculate correctly
- ✅ Filters work
- ✅ Export buttons work (PDF/Excel)

---

## 🔍 Troubleshooting Guide

### Issue: 303 Status Code

**What it means:** This is CORRECT behavior!

**Explanation:**
- HTTP 303 "See Other" is used in POST-Redirect-GET pattern
- Form submits via POST
- Server processes and responds with 303
- Browser automatically redirects to details page (GET)
- This prevents duplicate submissions on page refresh

**When to worry:**
- ✅ If you see 303 AND data saves = Perfect!
- ❌ If you see 303 BUT no data saves = Problem

---

### Issue: "Contribution not found" After Submit

**Possible Causes:**
1. Insert failed but returned false ID
2. Show method can't find the record
3. Database transaction failed

**Debug Steps:**
```sql
-- Check if contribution was actually saved
SELECT * FROM partner_contributions
ORDER BY id DESC LIMIT 1;

-- Check the exact timestamp
SELECT * FROM partner_contributions
WHERE created_at >= NOW() - INTERVAL 5 MINUTE;
```

**Fix:**
- Check `application/logs/log-YYYY-MM-DD.php`
- Verify `generateReceiptNumber()` isn't causing issues
- Ensure `created_at` has default `CURRENT_TIMESTAMP`

---

### Issue: Receipt Number Not Generated

**Check Model:**
```php
// In Contribution_model.php
public function generateReceiptNumber()
{
    do {
        $receipt_no = 'RCT-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $exists = $this->db->where('receipt_no', $receipt_no)
                           ->count_all_results('partner_contributions');
    } while ($exists > 0);

    return $receipt_no;
}
```

**Verify Usage:**
```php
// In controller add() method
$contribution_data['receipt_no'] = $this->contribution_model->generateReceiptNumber();
```

---

### Issue: File Upload Fails

**Check List:**
1. Directory exists: `uploads/partner_contributions`
2. Directory is writable (chmod 777 on Linux/Mac)
3. PHP settings allow uploads:
   ```ini
   upload_max_filesize = 10M
   post_max_size = 10M
   ```
4. Form has: `enctype="multipart/form-data"`
5. File input name matches: `name="attachment"`

**Test Upload:**
```php
// Add to controller temporarily
echo "<pre>";
print_r($_FILES);
print_r($_POST);
exit;
```

---

### Issue: Validation Errors Don't Show

**Check View:**
```php
<?php if (validation_errors()) { ?>
    <div class="alert alert-danger">
        <?php echo validation_errors(); ?>
    </div>
<?php } ?>
```

**Check Controller:**
```php
$this->form_validation->set_rules('amount', 'Amount', 'required|numeric|greater_than[0]');

if ($this->form_validation->run() == false) {
    $this->load->view('admin/contributions/contributionadd', $data);
} else {
    // Process...
}
```

---

### Issue: Partner Dropdown is Empty

**Possible Causes:**
1. No active partners in database
2. Controller not passing `$partners` to view
3. View using wrong variable name

**Check Database:**
```sql
SELECT COUNT(*) FROM partners WHERE is_active = 1;
```

**Check Controller:**
```php
$data['partners'] = $this->partner_model->getAll(); // or similar
$this->load->view('admin/contributions/contributionadd', $data);
```

---

### Issue: Permission Denied

**Check Permissions:**
```sql
-- Check if permission exists
SELECT * FROM permission_category
WHERE short_code = 'partners';

-- Check user's role permissions
SELECT rp.*
FROM role_permissions rp
JOIN roles r ON r.id = rp.role_id
JOIN user_roles ur ON ur.role_id = r.id
WHERE ur.user_id = YOUR_USER_ID
AND rp.feature_name = 'partners';
```

**Grant Permission:**
```sql
UPDATE role_permissions
SET can_add = 1, can_edit = 1, can_delete = 1, can_view = 1
WHERE feature_name = 'partners'
AND role_id = (SELECT id FROM roles WHERE name = 'Admin');
```

---

## ✅ Success Criteria

The contributions system is working correctly if:

### Basic Functionality
- [x] Add page loads without errors
- [x] Can select partner from dropdown
- [x] Form validation works (required fields)
- [x] Can add contribution with minimum required fields
- [x] Receipt number auto-generates
- [x] Data saves to database
- [x] Redirects to details page after save (303 status is OK!)
- [x] Details page displays all information correctly

### Advanced Functionality
- [x] Can add contribution with all fields
- [x] File upload works
- [x] Can view list of contributions
- [x] DataTable features work (search, sort, paginate)
- [x] Can edit existing contributions
- [x] Can delete contributions
- [x] Reports show contribution data

### Data Integrity
- [x] All fields save correctly
- [x] Foreign keys link properly (partner_id, giving_type_id)
- [x] Timestamps populate automatically
- [x] Receipt numbers are unique
- [x] Currency and amounts are correct
- [x] Status updates work

---

## 📊 Test Data Set

### Recommended Test Contributions

Add these test contributions to verify system fully:

```sql
-- Contribution 1: Cash payment
Partner: John Doe
Date: Today
Amount: 500.00
Currency: USD
Payment Method: Cash
Status: Completed

-- Contribution 2: Bank transfer with file
Partner: Jane Smith
Date: Yesterday
Amount: 1000.00
Currency: USD
Payment Method: Bank Transfer
Transaction ID: TXN001
Attachment: receipt.pdf
Status: Completed

-- Contribution 3: Pending mobile money
Partner: John Doe
Date: Today
Amount: 250.00
Currency: ZWL
Payment Method: Mobile Money
Status: Pending

-- Contribution 4: With giving type
Partner: Jane Smith
Date: 3 days ago
Amount: 750.00
Currency: USD
Payment Method: Cheque
Giving Type: Tithe
Status: Completed
```

---

## 🔧 Diagnostic Commands

### Check Table Structure
```sql
DESCRIBE partner_contributions;
```

### Check Recent Contributions
```sql
SELECT * FROM partner_contributions
ORDER BY created_at DESC LIMIT 5;
```

### Check Contribution Totals
```sql
SELECT
    COUNT(*) as total_contributions,
    SUM(amount) as total_amount,
    COUNT(DISTINCT partner_id) as unique_partners
FROM partner_contributions
WHERE status = 'completed';
```

### Find Missing Receipt Numbers
```sql
SELECT * FROM partner_contributions
WHERE receipt_no IS NULL OR receipt_no = '';
```

### Check Upload Files
```bash
# Windows
dir uploads\partner_contributions

# Linux/Mac
ls -la uploads/partner_contributions
```

---

## 📚 Additional Resources

### Related Documentation
- **CONTRIBUTIONS_FIX.md** - Troubleshooting specific issues
- **fix_all_contributions.php** - Automated fix tool
- **setup_uploads.php** - Directory setup tool
- **check_contributions.php** - System diagnostic tool

### Related Modules
- **Partners Module** - Manage partners
- **Giving Types** - Configure contribution types
- **Partner Reports** - View contribution reports

---

## 🎯 Final Verification Checklist

After completing all tests, verify:

### Database
- [ ] All test contributions in database
- [ ] Receipt numbers are unique
- [ ] All foreign keys valid
- [ ] Timestamps populated
- [ ] Attachments recorded

### Files
- [ ] Controller file exists and works
- [ ] Model file exists and works
- [ ] Views render correctly
- [ ] Upload directory exists
- [ ] Uploaded files accessible

### User Interface
- [ ] Forms display correctly
- [ ] Validation works
- [ ] DataTables load
- [ ] Buttons function
- [ ] Responsive design works

### Permissions
- [ ] Admin can add contributions
- [ ] Proper users can access
- [ ] Menu items visible
- [ ] Reports accessible

### Integration
- [ ] Links to partners work
- [ ] Reports show data
- [ ] Dashboard widgets update
- [ ] Notifications work (if applicable)

---

## 🎉 Testing Complete!

Once all tests pass and checklists are complete:

1. ✅ **System is working correctly**
2. 🧹 **Clean up test data** (optional - delete test contributions)
3. 🗑️ **Delete diagnostic files** for security:
   - `fix_all_contributions.php`
   - `check_contributions.php`
   - `setup_uploads.php`
4. 📝 **Document any customizations** made
5. 👥 **Train users** on how to add contributions

---

## 💡 Tips & Best Practices

### For Development
- Keep logs enabled during testing
- Use browser DevTools to monitor network requests
- Test with different user roles
- Try edge cases (very large amounts, special characters, etc.)

### For Production
- Regular database backups
- Monitor upload directory size
- Periodically verify receipt number uniqueness
- Review failed contributions regularly
- Set up automated reports

### For Users
- Always verify partner before submitting
- Double-check amount and currency
- Add notes for context
- Attach receipts when available
- Verify receipt number after submission

---

**Document Version:** 1.0
**Last Updated:** 2024-11-29
**Status:** Complete and Ready for Use
